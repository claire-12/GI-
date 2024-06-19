<?php

defined('ABSPATH') || exit;

use Automattic\WooCommerce\Utilities\OrderUtil;

class PH_WC_Fedex_Storage_Handler
{
    /**
     * @var WC_Order $order
     */
    public $order;

    /**
     * @var int $order_id
     */
    public $order_id = null;

    /**
     * @var array $ph_meta_data
     */
    public $ph_meta_data = null;

    /**
     * @var string
     */
    public const META_TABLE_CREATED = 'ph_fedex_custom_meta_data_table_created';

    /**
     * @var array $internal_meta_keys Meta keys that are to be stored in our custom table
     */
    public static $internal_meta_keys = [
        'wf_woo_fedex_shippingLabel_',
        'wf_woo_fedex_shippingLabel_image_type_',
        'wf_fedex_additional_label_',
        'wf_fedex_additional_label_image_type_',
        'wf_woo_fedex_returnLabel_',
        'wf_woo_fedex_returnLabel_image_type_',
        'ph_fedex_additional_return_label_',
        'ph_fedex_additional_return_label_image_type_'
    ];

    /**
     * Constructor
     *
     * @param object $order
     */
    public function __construct($order)
    {
        $this->order    = $order;
        $this->order_id = $order->get_id();

        if (!$this->ph_get_table_exists()) {
            $this->ph_create_database_tables();
        }
    }

    /**
     * Add meta data to order instance
     *
     * @param string $meta_key
     * @param mixed $meta_data
     * @param bool $unique Should this be a unique key?
     */
    public function ph_add_meta_data($meta_key, $meta_data, $unique = false)
    {
        if (self::ph_check_if_custom_table_meta($meta_key)) {
            $this->ph_add_meta($meta_key, $meta_data);
        } else {
            $this->order->add_meta_data($meta_key, $meta_data, $unique);
        }
    }

    /**
     * Add meta data to order instance
     *
     * @param string $meta_key
     * @param mixed $meta_data
     */
    public function ph_update_meta_data($meta_key, $meta_data)
    {
        if (self::ph_check_if_custom_table_meta($meta_key)) {

            if ($this->ph_get_table_exists()) {

                $this->ph_maybe_read_meta_data();

                $matches = array();

                foreach ($this->ph_meta_data as $meta_data_array_key => $meta) {

                    if ($meta->meta_key === $meta_key) {
                        $matches[] = $meta_data_array_key;
                    }
                }

                if (!empty($matches)) {

                    foreach ($matches as $meta_data_array_key) {

                        $this->ph_meta_data[$meta_data_array_key]->meta_value = null;
                    }

                    $array_key  = current($matches);

                    $meta       = $this->ph_meta_data[$array_key];

                    $this->ph_update_meta($meta, $meta_data);
                } else {

                    $this->ph_add_meta($meta_key, $meta_data);
                }
            } else {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("PH FedEx Custom Order Meta table is missing in the database and couldn't be created. The missing table: ph_fedex_wc_orders_meta", true);
            }
        } else {

            $this->order->update_meta_data($meta_key, $meta_data);
        }
    }

    /**
     * Delete meta data from within the order instance
     *
     * @param string $meta_key
     */
    public function ph_delete_meta_data($meta_key)
    {

        if (self::ph_check_if_custom_table_meta($meta_key)) {

            $this->delete_meta_data($meta_key);
        } else {

            $this->order->delete_meta_data($meta_key);
        }
    }

    /**
     * Save meta data to the order instance
     */
    public function ph_save_meta_data()
    {
        if (!empty($this->ph_meta_data)) {

            $this->ph_save_meta();
        }

        $this->order->save();
    }

    /**
     * Check if WooCommerce HPOS enabled in the store
     *
     * @return bool Returns true if HPOS is enabled else returns false
     */
    public static function ph_check_if_hpo_enabled()
    {
        return OrderUtil::custom_orders_table_usage_is_enabled();
    }

    /**
     * Update meta data to order and save
     *
     * @param int $order_id
     * @param string $meta_key
     * @param mixed $meta_data
     */
    public static function ph_update_and_save_meta_data($order_id, $meta_key, $meta_data)
    {
        $order = wc_get_order($order_id);

        if (!$order instanceof WC_Order) {
            return;
        }

        $order->update_meta_data($meta_key, $meta_data);
        $order->save();
    }

    /**
     * Add meta data to order and save
     *
     * @param int $order_id
     * @param string $meta_key
     * @param mixed $meta_data
     */
    public static function ph_add_and_save_meta_data($order_id, $meta_key, $meta_data, $unique = false)
    {
        $order = wc_get_order($order_id);

        if (!$order instanceof WC_Order) {
            return;
        }

        $order->add_meta_data($meta_key, $meta_data, $unique);
        $order->save();
    }

    /**
     * Get Meta Data by Key
     *
     * @param int $order_id Order Id
     * @param string $meta_key Meta Key
     * @param bool $single Return first found meta with key, or all with $key
     * @return mixed
     */
    public static function ph_get_meta_data($order_id, $meta_key, $single = true)
    {
        $order          = wc_get_order($order_id);
        $label_migrated = get_option(PH_FEDEX_LABEL_MIGRATION_OPTION, false);
        $migration_data = get_option('ph_fedex_label_migration_data', []);

        $order_ids = isset($migration_data['order_ids']) ? $migration_data['order_ids'] : [];

        if (($label_migrated || !in_array($order_id, $order_ids) ) && self::ph_check_if_custom_table_meta($meta_key)) {

            return (new PH_WC_Fedex_Storage_Handler($order))->ph_get_meta($meta_key, $single);
        }

        $meta_data = $order->get_meta($meta_key, $single);

        return apply_filters(
            'ph_wc_fedex_order_metadata',
            $meta_data,
            $order_id
        );
    }

    /**
     * Get Meta Data by Key
     *
     * @param int $order_id Order Id
     * @param string $meta_key Meta Key
     * @param bool $single Return first found meta with key, or all with $key
     * @return mixed
     */
    public static function ph_get_custom_meta_data($order_id, $meta_key, $single = true)
    {
        $order = wc_get_order($order_id);

        return $order->get_meta($meta_key, $single);
    }

    /**
     * Filter null meta values from array.
     *
     * @param mixed $meta Meta value to check.
     * @return bool
     */
    protected function ph_filter_null_meta($meta)
    {
        return !is_null($meta->meta_value);
    }

    /**
     * Get Meta Data by Key.
     *
     * @param  string $key Meta Key.
     * @param  bool   $single return first found meta with key, or all with $key.
     * @return mixed
     */
    public function ph_get_meta($key = '', $single = true)
    {
        $this->ph_maybe_read_meta_data();

        $meta_data  = array_values(array_filter($this->ph_meta_data, array($this, 'ph_filter_null_meta')));
        $array_keys = array_keys(wp_list_pluck($meta_data, 'meta_key'), $key, true);
        $value      = $single ? '' : array();

        if (!empty($array_keys)) {

            if ($single) {

                $value = $meta_data[current($array_keys)]->meta_value;
            } else {

                $value = array_intersect_key($meta_data, array_flip($array_keys));
            }
        }

        return $value;
    }

    /**
     * Update meta.
     *
     * @param  stdClass $meta (containing ->meta_id, ->meta_key and ->value).
     * @param  mixed $meta_data
     *
     * @return bool
     */
    public function ph_update_meta($meta, $meta_data): bool
    {

        global $wpdb;

        if (!isset($meta->meta_id) || empty($meta->meta_key)) {
            return false;
        }

        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_value,WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        $data = array(
            'meta_key'   => $meta->meta_key,
            'meta_value' => maybe_serialize($meta_data),
        );
        // phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_meta_value,WordPress.DB.SlowDBQuery.slow_db_query_meta_key

        $db_info = $this->ph_get_db_info();

        $result = $wpdb->update(
            $db_info['table'],
            $data,
            array($db_info['meta_id_field'] => $meta->meta_id),
            '%s',
            '%d'
        );

        return 1 === $result;
    }

    /**
     * Add new piece of meta.
     *
     * @param  string $meta_key
     * @param  mixed $meta_value
     *
     * @return int|false meta ID
     */
    public function ph_add_meta($meta_key, $meta_value)
    {
        global $wpdb;

        $db_info    = $this->ph_get_db_info();
        $meta_key   = wp_unslash(wp_slash($meta_key));
        $meta_value = maybe_serialize(is_string($meta_value) ? wp_unslash(wp_slash($meta_value)) : $meta_value);

        // phpcs:disable WordPress.DB.SlowDBQuery.slow_db_query_meta_value,WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        $result = $wpdb->insert(
            $db_info['table'],
            array(
                $db_info['object_id_field'] => $this->order_id,
                'meta_key'                  => $meta_key,
                'meta_value'                => $meta_value,
            )
        );

        // phpcs:enable WordPress.DB.SlowDBQuery.slow_db_query_meta_value,WordPress.DB.SlowDBQuery.slow_db_query_meta_key

        return $result ? (int) $wpdb->insert_id : false;
    }

    /**
     * Delete meta data.
     *
     * @param string $key Meta key.
     */
    public function delete_meta_data($key)
    {

        $this->ph_maybe_read_meta_data();

        $array_keys = array_keys(wp_list_pluck($this->ph_meta_data, 'meta_key'), $key, true);

        if ($array_keys) {

            foreach ($array_keys as $array_key) {

                $this->ph_meta_data[$array_key]->meta_value = null;
            }
        }
    }

    /**
     * Update Meta Data in the database.
     */
    public function ph_save_meta()
    {
        if (is_null($this->ph_meta_data)) {
            return;
        }

        foreach ($this->ph_meta_data as $array_key => $meta) {

            if (is_null($meta->meta_value) && !empty($meta->meta_id)) {

                $this->ph_delete_meta($meta);
            }
        }
    }

    /**
     * Deletes meta based on meta ID.
     *
     * @param stdClass $meta
     *
     * @return bool
     */
    public function ph_delete_meta($meta): bool
    {

        global $wpdb;

        if (!isset($meta->meta_id)) {
            return false;
        }

        $db_info = $this->ph_get_db_info();
        $meta_id = absint($meta->meta_id);

        return (bool) $wpdb->delete($db_info['table'], array($db_info['meta_id_field'] => $meta_id));
    }

    /**
     * Get the orders meta data table name.
     *
     * @return string Name of order meta data table.
     */
    public static function ph_get_meta_table_name()
    {
        global $wpdb;

        return $wpdb->prefix . 'ph_fedex_wc_orders_meta';
    }

    /**
     * Returns the name of the field/column used for identifiying metadata entries.
     *
     * @return string
     */
    protected function ph_get_meta_id_field()
    {
        return 'id';
    }

    /**
     * Returns the name of the field/column used for associating meta with objects.
     *
     * @return string
     */
    protected function ph_get_object_id_field()
    {
        return 'order_id';
    }

    /**
     * Describes the structure of the metadata table.
     *
     * @return array Array elements: table, object_id_field, meta_id_field.
     */
    protected function ph_get_db_info()
    {
        return array(
            'table'           => $this->ph_get_meta_table_name(),
            'meta_id_field'   => $this->ph_get_meta_id_field(),
            'object_id_field' => $this->ph_get_object_id_field(),
        );
    }

    /**
     * Read meta data if null.
     *
     */
    protected function ph_maybe_read_meta_data()
    {
        if (is_null($this->ph_meta_data)) {

            $this->ph_read_meta_data();
        }
    }

    /**
     * Read Meta Data from the database
     *
     */
    public function ph_read_meta_data()
    {

        $this->ph_meta_data = array();

        if (!$this->order_id) {
            return;
        }

        // Add Caching

        $meta_data = $this->ph_read_meta($this->order_id);

        $this->ph_init_meta_data($meta_data);
    }

    /**
     * Helper function to initialize metadata entries
     *
     * @param array $filtered_meta_data Filtered metadata fetched from DB.
     */
    public function ph_init_meta_data(array $meta_data = array())
    {

        $this->ph_meta_data = array();

        if (!empty($meta_data)) {

            foreach ($meta_data as $meta) {

                $this->ph_meta_data[] = (object)
                array(
                    'meta_id'    => (int) $meta->meta_id,
                    'meta_key'   => $meta->meta_key,
                    'meta_value' => maybe_unserialize($meta->meta_value),
                );
            }
        }
    }

    /**
     * Returns an array of meta for an object.
     *
     * @param $order_id
     * @return array
     */
    public function ph_read_meta($order_id)
    {

        global $wpdb;

        $db_info = $this->ph_get_db_info();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $meta_data = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT {$db_info['meta_id_field']} AS meta_id, meta_key, meta_value FROM {$db_info['table']} WHERE {$db_info['object_id_field']} = %d ORDER BY meta_id",
                $order_id
            )
        );
        // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared

        return $meta_data;
    }

    /**
     * Returns the value of the order meta table created option. If it's not set, then it checks the order meta table and set it accordingly.
     *
     * @return bool Whether orders table exists.
     */
    public function ph_get_table_exists(): bool
    {
        $table_exists = get_option(self::META_TABLE_CREATED);

        switch ($table_exists) {
            case 'no':
            case 'yes':
                return 'yes' === $table_exists;
            default:
                return $this->ph_check_order_meta_table_exists();
        }
    }


    /**
     * Does the custom orders table exist in the database?
     *
     * @return bool True if the custom order tables exist in the database.
     */
    public function ph_check_order_meta_table_exists(): bool
    {
        global $wpdb;

        $table_name = $this->ph_get_meta_table_name();

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

            update_option(self::META_TABLE_CREATED, 'no');

            return false;
        } else {

            update_option(self::META_TABLE_CREATED, 'yes');

            return true;
        }
    }

    /**
     * Create the custom orders meta data table and log an error if that's not possible.
     *
     * @return bool True if the table was successfully created, false otherwise.
     */
    public function ph_create_database_tables()
    {

        $query = $this->ph_get_database_schema();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($query);

        $success = $this->ph_check_order_meta_table_exists();

        if (!$success) {

            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport("PH FedEx Custom Order Meta table are missing in the database and couldn't be created. The missing table: ph_fedex_wc_orders_meta", true);
        }

        return $success;
    }

    /**
     * Get the SQL needed to create the table needed for the Custom Meta Data
     *
     * @return string
     */
    public function ph_get_database_schema()
    {

        global $wpdb;

        $collate = $wpdb->has_cap('collation') ? $wpdb->get_charset_collate() : '';

        $ph_meta_table                      = $this->ph_get_meta_table_name();
        $max_index_length                   = 191; // Check WooCommerce get_max_index_length()
        $composite_meta_value_index_length  = max($max_index_length - 8 - 100 - 1, 20); // 8 for order_id, 100 for meta_key, 10 minimum for meta_value.

        $sql = "
CREATE TABLE IF NOT EXISTS $ph_meta_table (
	id bigint(20) unsigned auto_increment primary key,
	order_id bigint(20) unsigned null,
	meta_key varchar(255),
	meta_value longtext null,
    KEY ph_order_id (order_id),
	KEY meta_key_value (meta_key(100), meta_value($composite_meta_value_index_length)),
	KEY order_id_meta_key_meta_value (order_id, meta_key(100), meta_value($composite_meta_value_index_length))
) $collate;
";

        return $sql;
    }

    /**
     * Check if current meta to be inserted into custom table
     *
     * @param string $meta_key
     * @return bool $is_custom_data
     */
    public static function ph_check_if_custom_table_meta($meta_key)
    {
        $is_custom_data = false;

        $matching_keys = array_filter(self::$internal_meta_keys, function ($key) use ($meta_key) {
            return stripos($meta_key, $key) !== false;
        });

        if (sizeof($matching_keys)) {
            $is_custom_data = true;
        }

        return $is_custom_data;
    }
}
