<?php

class PH_FedEx_Api_History_In_Database {

    public $ph_fedex_api_history_tablename;

    public function __construct(){

        $this->ph_fedex_api_history_tablename = 'ph_fedex_api_key_history';
    }

    /**
     * Create FedEx Api History table 
     */
    public function ph_create_api_status_table() {
        global $wpdb;

        $tablename = $wpdb->prefix.$this->ph_fedex_api_history_tablename;

        $sql = "CREATE TABLE IF NOT EXISTS $tablename (
            id bigint(20) UNSIGNED AUTO_INCREMENT,
            master_key varchar(50) NOT NULL,
            product_order_key varchar(50) NOT NULL,
            product_id varchar(50) NOT NULL,
            access_granted varchar(191) NOT NULL,
            access_expired varchar(191) NOT NULL,
            PRIMARY KEY (product_order_key),
            KEY id (id)
        );";

        if ( !function_exists('dbDelta') ) {

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        }

        dbDelta( $sql );
    }

    /**
     * Insert data into the FedEx Api History table 
     * 
     * 
	 * @param array $activate_results
	 * @return bool
     */
    public function ph_insert_data_to_fedex_api_history_table( $activate_results ) {

        if ( !$this->ph_fedex_api_history_table_exists() ) {
            $this->ph_create_api_status_table();
        }

        global $wpdb;

        $tablename          = $wpdb->prefix.$this->ph_fedex_api_history_tablename;

        // Prepare the query to check if the data already exists
        $existing_data = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $tablename WHERE product_order_key = %s",$activate_results['data']['product_order_api_key']
            )
        );

        // Check if any matching records were found
        if (!$existing_data) {

            // Data does not exist, proceed with insertion
            $wpdb->insert(
                $tablename,
                array(
                    'master_key'        => $activate_results['data']['master_api_key'],
                    'product_order_key' => $activate_results['data']['product_order_api_key'],
                    'product_id'        => '3321',
                    'access_granted'    => $activate_results['data']['access_granted'],
                    'access_expired'    => $activate_results['data']['access_expires'],
                )
            );
        } else {
            // Data already exists, proceed with update
            $wpdb->update(
                $tablename,
                array(
                    'master_key'        => $activate_results['data']['master_api_key'],
                    'product_order_key' => $activate_results['data']['product_order_api_key'],
                    'product_id'        => '3321',
                    'access_granted'    => $activate_results['data']['access_granted'],
                    'access_expired'    => $activate_results['data']['access_expires'],
                ),
                array('product_order_key' => $activate_results['data']['product_order_api_key'])
            );
        }
    }

    /**
     * Check FedEx Api History table exist or not
     * 
     * @return bool
     */
    public function ph_fedex_api_history_table_exists() {

        global $wpdb;

        $table_name = $wpdb->prefix.$this->ph_fedex_api_history_tablename;

        if ( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name ) {
            return true;
        }
        return false;
    }

    /**
     * Show the FedEx Api History table's data
     */
    public function ph_fetch_fedex_api_history_table_data() {

        global $wpdb;

        $table_name = $wpdb->prefix.$this->ph_fedex_api_history_tablename;

		$table_data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$table_name} ORDER BY id DESC"
			)
		);

        if ( !empty($table_data) ) {
            ?>
            <style>
                .ph-ups-api-history td,.ph-ups-api-history th {
                    padding: 10px;
                }
            </style>
            <h3><?php echo __(' API History ','ph-fedex-woocommerce-shipping'); ?> </h3>
            <table class="wp-list-table widefat striped ph-ups-api-history">
                <thead>
                <tr>
                    <th><?php echo __('ID','ph-fedex-woocommerce-shipping'); ?></th>
                    <th><?php echo __('Master Key','ph-fedex-woocommerce-shipping'); ?></th>
                    <th><?php echo __('Product Order Key','ph-fedex-woocommerce-shipping'); ?></th>
                    <th><?php echo __('Product Id','ph-fedex-woocommerce-shipping'); ?></th>
                    <th><?php echo __('Access Granted Date','ph-fedex-woocommerce-shipping'); ?></th>
                    <th><?php echo __('Access Expired Date','ph-fedex-woocommerce-shipping'); ?></th>
                </tr>
                </thead>
            <?php 

            $date_format = get_option( 'date_format' );

            foreach ($table_data as $row) {

                // Access row properties
                echo '<tr><td>'.$row->id.'</td>';
                echo '<td>'.$row->master_key.'</td>';
                echo '<td>'.$row->product_order_key.'</td>';
                echo '<td>'.$row->product_id.'</td>';
                echo '<td>'.date($date_format,$row->access_granted).'</td>';
                echo '<td>'.date($date_format,$row->access_expired).'</td></tr>';
            }

            echo '</table>';
        }
    }
}