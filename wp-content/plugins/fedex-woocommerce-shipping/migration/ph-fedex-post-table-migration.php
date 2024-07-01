<?php

defined('ABSPATH') || exit;

if (!class_exists("PH_Fedex_WP_Post_Table_Label_Migration")) {

    class PH_Fedex_WP_Post_Table_Label_Migration
    {
        public static $migration_limit  = "100";
        public static $post_table_name  = "posts";
        public static $meta_table_name  = "postmeta";
        public static $post_meta_key    = "wf_woo_fedex_shipmentId";

        private static $ph_migration_banner_option  = "ph_fedex_display_migration_banner";
        private static $ph_label_migration_option   = "ph_fedex_label_migration_data";

        private static $ph_label_migration = [
            'started'               => 'no',
            'fetched_data'          => 'no',
            'table_prepared'        => 'no',
            'completed'             => 'no',
            'partial'               => 'no',
            'order_ids'             => [],
            'order_ids_left'        => [],
            'order_ids_migrated'    => [],
            'invalid_order_ids'     => [],
        ];

        public $ph_wp_postmeta_label_migrated;
        public $ph_display_migration_banner;
        public $ph_wp_postmeta_migration_data;
        public $ph_label_orders_data;

        public function __construct()
        {
            $this->ph_wp_postmeta_label_migrated    = get_option(PH_FEDEX_LABEL_MIGRATION_OPTION, false);
            $this->ph_display_migration_banner      = get_option(self::$ph_migration_banner_option, "yes");
            $this->ph_wp_postmeta_migration_data    = get_option(self::$ph_label_migration_option, self::$ph_label_migration);

            // Cron 
            add_action('ph_fedex_wp_post_table_label_migration', [$this, 'ph_fedex_run_label_migration']);

            // Ajax
            add_action('wp_ajax_ph_fedex_closing_migration_banner', [$this, 'ph_fedex_close_migration_info_banner'], 10);

            // Add Banner
            if ($this->ph_display_migration_banner == "yes") {

                add_action('admin_notices', [$this, 'ph_fedex_add_migration_info_banner']);
            }

            // Start Label Migration
            if ($this->ph_fedex_is_migration_required()) {

                add_action('wp_loaded', [$this, 'ph_fedex_migrate_postmeta_labels']);
            }
        }

        /**
         * Add Banner for FedEx 5.0.0 Changes
         * 
         */
        function ph_fedex_add_migration_info_banner()
        {

            $total_orders           = is_array($this->ph_wp_postmeta_migration_data) ? count($this->ph_wp_postmeta_migration_data['order_ids']) : 0;
            $total_orders_left      = is_array($this->ph_wp_postmeta_migration_data) ? count($this->ph_wp_postmeta_migration_data['order_ids_left']) : 0;
            $total_orders_migrated  = is_array($this->ph_wp_postmeta_migration_data) ? count($this->ph_wp_postmeta_migration_data['order_ids_migrated']) : 0;
            $migration_progress     = $total_orders_left == 0 ? "Completed" : "In Progress";
?>
            <div class="notice ph-fedex-notice-banner">
                <h3><strong>&#127881; WooCommerce FedEx Shipping Plugin with Print Label &#128640;</strong></h3>
                <p>Exciting news!<br /> Our plugin now fully supports <a href='https://www.pluginhive.com/woocommerce-hpos/?utm_source=plugin&utm_medium=dash&utm_id=ups_hpos' target='_blank'>WooCommerce's HPOS (High Performance Order Storage)</a> feature!</p>
                <p>With this update, we've introduced a dedicated storage system specifically designed to store shipping-related data for new orders.<br /> To ensure backward compatibility, we'll be automatically syncing past orders up to the last year in the background. The order sync will run automatically and will be completed within some time.</p>

                <div class="ph-fedex-view-progress ph-fedex-view-symbol">Migration Progress</div>
                <div class="ph-fedex-progress-details" style="display: none;">
                    <p>&#x1F4CA; Total Orders: <?= $total_orders; ?></p>
                    <p>&#x23F3; Orders Pending: <?= $total_orders_left; ?></p>
                    <p>&#x2705; Orders Completed: <?= $total_orders_migrated; ?></p>
                    <p>&#x2139; Status: <b><?= $migration_progress; ?></b></p>
                </div>

                <button class="ph-fedex-close-migration-banner ph-fedex-close-notice"><?php _e('Close', 'ph-fedex-woocommerce-shipping') ?></button>
                <button class="ph-fedex-contact-us"><a href="https://www.pluginhive.com/support/" target='_blank'><?php _e('Contact Us', 'ph-fedex-woocommerce-shipping') ?></a></button>
            </div>
<?php
        }

        /**
         * Close Banner for FedEx Post table migration
         * 
         */
        public function ph_fedex_close_migration_info_banner()
        {

            update_option(self::$ph_migration_banner_option, "no");

            wp_die(json_encode(true));
        }

        /**
         * Check if migration is required
         *
         * @return bool
         */
        private function ph_fedex_is_migration_required()
        {
            if ($this->ph_wp_postmeta_label_migrated || $this->ph_wp_postmeta_migration_data['completed'] == 'yes') {

                return false;
            }

            return true;
        }

        /**
         * Migrate FedEx Labels from WP Post Meta to PH Custom Table
         * 
         */
        public function ph_fedex_migrate_postmeta_labels()
        {

            // Schedule label migration if order ids left
            if (!empty($this->ph_wp_postmeta_migration_data['order_ids_left']) && !wp_next_scheduled('ph_fedex_wp_post_table_label_migration')) {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Scheduling Migration for the remaining Orders ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($this->ph_wp_postmeta_migration_data, 1), true);

                $this->ph_schedule_fedex_migration_event();

                return;
            }

            // Fetch Orders from DB for which label is already created
            if ($this->ph_wp_postmeta_migration_data['fetched_data'] != 'yes') {

                $this->ph_get_fedex_labels_order_data();

                // Return if no Orders found with Label Data
                if (empty($this->ph_label_orders_data)) {

                    $this->ph_wp_postmeta_migration_data['started'] = $this->ph_wp_postmeta_migration_data['completed'] = 'yes';

                    update_option(self::$ph_label_migration_option, $this->ph_wp_postmeta_migration_data);
                    update_option(PH_FEDEX_LABEL_MIGRATION_OPTION, true);

                    return;
                }

                $this->ph_filter_fedexs_orders();
            }

            // Return if no order ids found
            if (empty($this->ph_wp_postmeta_migration_data['order_ids'])) {
                return;
            }

            // Return if no data is fetched
            if ($this->ph_wp_postmeta_migration_data['fetched_data'] != 'yes') {
                return;
            }

            if (!wp_next_scheduled('ph_fedex_wp_post_table_label_migration')) {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Migration Data ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($this->ph_wp_postmeta_migration_data, 1), true);

                $this->ph_schedule_fedex_migration_event();
            }
        }

        /**
         * Filter invalid Orders
         * 
         */
        private function ph_filter_fedexs_orders()
        {
            $order_ids          = [];
            $invalid_order_ids  = [];

            foreach ($this->ph_label_orders_data as $order_data) {

                $order_id = $order_data->post_id;

                $order = wc_get_order($order_id);

                if (!$order instanceof WC_Order) {

                    $invalid_order_ids[] = $order_id;

                    continue;
                }

                $order_ids[] = $order_id;
            }

            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Invalid Order Ids ---#", true);
            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($invalid_order_ids, 1), true);

            $this->ph_wp_postmeta_migration_data['invalid_order_ids'] = $invalid_order_ids;

            if (empty($order_ids)) {

                $this->ph_wp_postmeta_migration_data['order_ids'] = $this->ph_wp_postmeta_migration_data['order_ids_left'] = [];
                $this->ph_wp_postmeta_migration_data['fetched_data'] = $this->ph_wp_postmeta_migration_data['started'] = $this->ph_wp_postmeta_migration_data['completed'] = 'yes';

                update_option(PH_FEDEX_LABEL_MIGRATION_OPTION, true);
            } else {

                $this->ph_wp_postmeta_migration_data['order_ids'] = $this->ph_wp_postmeta_migration_data['order_ids_left'] = $order_ids;
                $this->ph_wp_postmeta_migration_data['fetched_data'] = 'yes';
            }

            update_option(self::$ph_label_migration_option, $this->ph_wp_postmeta_migration_data);
        }

        /**
         * Schedule Migration
         * 
         */
        private function ph_schedule_fedex_migration_event()
        {
            try {

                $start_time_stamp = strtotime("now +5 minutes");
                wp_schedule_single_event($start_time_stamp, 'ph_fedex_wp_post_table_label_migration');

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- FedEx Label Migration Cron : Time $start_time_stamp ---#", true);
            } catch (Exception $e) {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- FedEx Label Migration Cron Scheduling Error ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(
                    print_r(
                        [
                            'code'      => $e->getCode(),
                            'message'   => $e->getMessage(),
                            'file'      => $e->getFile(),
                            'line'      => $e->getLine(),
                            'time'      => date('Y-m-d H:i:s'),
                        ],
                        1
                    ),
                    true
                );
            }
        }

        /**
         * Run DB Migration
         * 
         */
        public function ph_fedex_run_label_migration()
        {

            try {

                $this->ph_wp_postmeta_migration_data['started'] = 'yes';

                $order_ids          = $this->ph_wp_postmeta_migration_data['order_ids'];
                $migrated_order_ids = $this->ph_wp_postmeta_migration_data['order_ids_migrated'];

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Started Label Migration ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($this->ph_wp_postmeta_migration_data, 1), true);

                $order_ids_migrated     = array_values(array_intersect($order_ids, $migrated_order_ids));
                $order_ids_to_migrate   = $order_ids_left = array_values(array_diff($order_ids, $migrated_order_ids));

                // Partially Updated
                if (count($order_ids_migrated) > 0) {

                    $this->ph_wp_postmeta_migration_data['partial']             = 'yes';
                    $this->ph_wp_postmeta_migration_data['order_ids_migrated']  = array_values(array_unique(array_merge($migrated_order_ids, $order_ids_migrated)));
                    $this->ph_wp_postmeta_migration_data['order_ids_left']      = $order_ids_left;

                    update_option(self::$ph_label_migration_option, $this->ph_wp_postmeta_migration_data);
                }

                // Migration Complete
                if (count($order_ids_to_migrate) == 0) {

                    $this->ph_wp_postmeta_migration_data['partial']             = 'no';
                    $this->ph_wp_postmeta_migration_data['completed']           = 'yes';
                    $this->ph_wp_postmeta_migration_data['order_ids_migrated']  = array_values(array_unique(array_merge($migrated_order_ids, $order_ids_migrated)));
                    $this->ph_wp_postmeta_migration_data['order_ids_left']      = [];

                    update_option(self::$ph_label_migration_option, $this->ph_wp_postmeta_migration_data);
                    update_option(PH_FEDEX_LABEL_MIGRATION_OPTION, true);
                } else {

                    $ph_internal_meta_keys  = PH_WC_Fedex_Storage_Handler::$internal_meta_keys;

                    foreach ($order_ids_to_migrate as $key => $order_id) {

                        if ($key == self::$migration_limit) {

                            break;
                        }

                        if (!empty($order_id)) {

                            $order              = wc_get_order($order_id);
                            $storage_handler    = new PH_WC_Fedex_Storage_Handler($order);

                            foreach ($ph_internal_meta_keys as $meta_key) {

                               // Get Meta Data from Legacy Post Table for Orders
                                $shipment_ids = get_post_meta($order_id, self::$post_meta_key);

                                foreach ($shipment_ids as $shipment_id) {

                                    $actual_key = $meta_key . $shipment_id;
                                    $meta_value = get_post_meta($order_id, $actual_key, true);
                                    
                                    if (!empty($meta_value)) {
    
                                        $result = $storage_handler->ph_add_meta($actual_key, $meta_value);
    
                                        if (!$result) {
    
                                            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Update failed for the Order #$order_id: Meta Key $actual_key  ---#", true);
                                        }
                                    }
                                }
                            }

                            $order_ids_migrated[] = $order_id;
                        }
                    }
                }

                // Migration Complete
                if ((count($order_ids_migrated) > 0) && !(array_diff($order_ids, $order_ids_migrated))) {

                    $this->ph_wp_postmeta_migration_data['partial']             = 'no';
                    $this->ph_wp_postmeta_migration_data['completed']           = 'yes';
                    $this->ph_wp_postmeta_migration_data['order_ids_migrated']  = array_values(array_unique(array_merge($migrated_order_ids, $order_ids_migrated)));
                    $this->ph_wp_postmeta_migration_data['order_ids_left']      = [];

                    update_option(self::$ph_label_migration_option, $this->ph_wp_postmeta_migration_data);
                    update_option(PH_FEDEX_LABEL_MIGRATION_OPTION, true);
                }

                if (array_diff($order_ids, $order_ids_migrated)) {

                    $this->ph_wp_postmeta_migration_data['order_ids_left']      = array_diff($order_ids, $order_ids_migrated);
                    $this->ph_wp_postmeta_migration_data['order_ids_migrated']  = array_values(array_unique(array_merge($migrated_order_ids, $order_ids_migrated)));
                    $this->ph_wp_postmeta_migration_data['partial']             = 'yes';
                    $this->ph_wp_postmeta_migration_data['completed']           = 'no';
                }

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Label Migration End ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($this->ph_wp_postmeta_migration_data, 1), true);

                update_option(self::$ph_label_migration_option, $this->ph_wp_postmeta_migration_data);
                // Re-display migration banner
                update_option(self::$ph_migration_banner_option, "yes");
            } catch (Exception $e) {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- FedEx Label Migration Cron Error ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(
                    print_r(
                        [
                            'code'      => $e->getCode(),
                            'message'   => $e->getMessage(),
                            'file'      => $e->getFile(),
                            'line'      => $e->getLine(),
                            'time'      => date('Y-m-d H:i:s'),
                        ],
                        1
                    ),
                    true
                );
            }
        }

        /**
         * Get Orders from DB for FedEx Labels
         */
        private function ph_get_fedex_labels_order_data()
        {
            global $wpdb;

            $post_table = $wpdb->prefix . self::$post_table_name;
            $meta_table = $wpdb->prefix . self::$meta_table_name;
            $last_year  = gmdate('Y-m-d 00:00:00', strtotime("-1 year"));

            try {

                $orders_list = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT DISTINCT MetaTable.post_id 
                        FROM {$meta_table} as MetaTable
                        JOIN {$post_table} as PostTable ON PostTable.ID = MetaTable.post_id AND PostTable.post_type = 'shop_order'
                        WHERE MetaTable.meta_key = %s AND PostTable.post_modified_gmt >= %s
                        ORDER BY MetaTable.meta_id",
                        self::$post_meta_key,
                        $last_year
                    )
                );

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Last one year Orders for Successful Shipments ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($orders_list, 1), true);

                $this->ph_label_orders_data = $orders_list;
            } catch (Exception $e) {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("#--- Orders fetching Failed ---#", true);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(
                    print_r(
                        [
                            'code'      => $e->getCode(),
                            'message'   => $e->getMessage(),
                            'file'      => $e->getFile(),
                            'line'      => $e->getLine(),
                            'time'      => date('Y-m-d H:i:s'),
                        ],
                        1
                    ),
                    true
                );
            }
        }
    }

    new PH_Fedex_WP_Post_Table_Label_Migration();
}
