<?php

defined('ABSPATH') || exit;

class PH_WC_Fedex_Pickup
{
	private $_pickup_confirmation_number	= '_pickup_confirmation_number';
	private $_pickup_location				= '_pickup_location';
	private $_pickup_scheduled_date			= '_pickup_scheduled_date';

	/**
	 * Settings
	 */
	public $settings;
	/**
	 * Pickup Enabled
	 */
	public $pickup_enabled;

	/**
	 * PH_WC_Fedex_Pickup constructor
	 */
	public function __construct()
	{
		$this->settings			= get_option('woocommerce_' . WF_Fedex_ID . '_settings', null);
		$this->pickup_enabled	= (isset($this->settings['pickup_enabled']) && $this->settings['pickup_enabled'] == 'yes') ? true : false;
		
		if ($this->pickup_enabled) {
			$this->init();
		}
	}

	/**
	 * Initialize the required hooks
	 */
	private function init()
	{
		// Custom column headers - Legacy Post Table View
		add_filter('manage_edit-shop_order_columns', array($this, 'ph_add_column_header'), 20);
		add_action('manage_shop_order_posts_custom_column', array($this, 'add_order_status_column_content'), 10, 2);

		// Custom column headers - HPOS View
		add_filter('woocommerce_shop_order_list_table_columns', [$this, 'ph_add_column_header']);
		add_action('woocommerce_shop_order_list_table_custom_column', [$this, 'add_order_status_column_content'], 10, 2);

		// Pickup details - Legacy Post Table View
		add_action('manage_shop_order_posts_custom_column', array($this, 'display_order_list_pickup_status'), 10, 2);

		// Pickup details - HPOS View
		add_action('woocommerce_shop_order_list_table_custom_column', array($this, 'display_order_list_pickup_status'), 10, 2);

		// Bulk actions - HPOS View
		add_filter('bulk_actions-woocommerce_page_wc-orders', [$this, 'ph_add_pickup_bulk_actions']);
		add_action('admin_init', [$this, 'ph_handle_bulk_actions_new_screen']);

		// Bulk actions - Legacy Post Table View
		add_filter('bulk_actions-edit-shop_order', [$this, 'ph_add_pickup_bulk_actions']);
		add_filter('handle_bulk_actions-edit-shop_order', [$this, 'ph_hndle_bulk_actions_old_screen'], 10, 3);

	}

	/**
	 * Add column content
	 *
	 * @param string $column_name
	 * @param $order_data
	 */
	public function add_order_status_column_content($column_name, $order_data)
	{
		$order_id = ($order_data instanceof WC_Order) ? $order_data->get_id() : $order_data;

		if ('fedex_pickup_info' == $column_name) {
			
			if ($this->is_pickup_requested($order_id)) {
				echo ('<span class="dashicons dashicons-yes"></span>') . __('Requested', 'ph-fedex-woocommerce-shipping');
			} else {
				echo ('<span class="dashicons dashicons-marker"></span>') . __('Not Requested', 'ph-fedex-woocommerce-shipping');
			}
		}
	}

	/**
	 * Add custom column header
	 *
	 * @param array $columns
	 * @return array $modified_columns
	 */
	public function ph_add_column_header($columns)
	{
		$modified_columns = [];

		foreach ($columns as $column_name => $column_info) {

			$modified_columns[$column_name] = $column_info;

			if ('shipping_address' == $column_name) {
				$modified_columns['fedex_pickup_info'] = __('FedEx Pickup', 'ph-fedex-woocommerce-shipping');
			}
		}

		return $modified_columns;
	}

	/**
	 * Add pickup bulk actions
	 *
	 * @param array $actions
	 * @return array $actions
	 */
	public function ph_add_pickup_bulk_actions($actions)
	{
		$actions['fedex_pickup_request']	= __('Request FedEx Pickup', 'ph-fedex-woocommerce-shipping');
		$actions['fedex_pickup_cancel']		= __('Cancel FedEx Pickup', 'ph-fedex-woocommerce-shipping');

		return $actions;
	}

	/**
	 * Handle bulk actions
	 */
	public function ph_handle_bulk_actions_new_screen()
	{
		$action		= isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
		$action		= empty($action) ? (isset($_GET['action2']) && !empty($_GET['action2']) ? $_GET['action2'] : '') : $action;
		$order_ids	= isset($_GET['id']) && is_array($_GET['id']) ? $_GET['id'] : [];

		$this->ph_perform_bulk_action($action, $order_ids);
	}

	/**
	 * Handle bulk actions Post table
	 */
	public function ph_hndle_bulk_actions_old_screen($redirect_to, $action, $post_ids)
	{
		if (!empty($post_ids) && is_array($post_ids)) {

			$this->ph_perform_bulk_action($action, $post_ids);
		}

		return $redirect_to;
	}

	/**
	 * Perform bulk actions
	 *
	 * @param string $action
	 * @param array $order_ids
	 */
	public function ph_perform_bulk_action($action, $order_ids)
	{
		if ($action == 'fedex_pickup_request') { // Pickup Request

			if (!class_exists('wf_fedex_woocommerce_shipping_admin_helper'))
			include_once 'class-wf-fedex-woocommerce-shipping-admin-helper.php';
			$helper			=	new wf_fedex_woocommerce_shipping_admin_helper();
			$result_array	=	$helper->request_pickup($order_ids);

			if (is_array($result_array) && !empty($result_array)) {
				$count = true;
				foreach ($result_array as $order_id => $result) {

					if (!empty($result)) {

						$pickup_array 		= array();
						$location_array 	= array();
						$date_array 		= array();

						foreach ($result as $service => $pickup_data) {

							if (isset($pickup_data) && isset($pickup_data['data'])) {

								$pickup_array[$service] 	= $pickup_data['data']['PickupConfirmationNumber'];
								$location_array[$service] 	= $pickup_data['data']['Location'];
								$date_array[$service] 		= $pickup_data['PickupTime'];

								if (isset($pickup_data['for_orders']) && !empty($pickup_data['for_orders']) && count($pickup_data['for_orders']) > 1) {

									$associate_pickup_orders = array_unique($pickup_data['for_orders']);

									foreach ($associate_pickup_orders as $pickup_order_id) {

										$order = wc_get_order($pickup_order_id);
										$order_meta_handler = new PH_WC_Fedex_Storage_Handler($order);

										$order_meta_handler->ph_update_meta_data($this->_pickup_confirmation_number, $pickup_array);
										$order_meta_handler->ph_update_meta_data($this->_pickup_location, $location_array);
										$order_meta_handler->ph_update_meta_data($this->_pickup_scheduled_date, $date_array);

										$ph_all_associate_order_ids[$pickup_data['data']['PickupConfirmationNumber']] = array_diff($associate_pickup_orders, array($pickup_order_id));

										$order_meta_handler->ph_update_meta_data('ph_associate_pickup_orders', $ph_all_associate_order_ids);

										$order_meta_handler->ph_save_meta_data();
									}
								} else {

									$order = wc_get_order($order_id);
									$order_meta_handler = new PH_WC_Fedex_Storage_Handler($order);

									$order_meta_handler->ph_update_meta_data($this->_pickup_confirmation_number, $pickup_array);
									$order_meta_handler->ph_update_meta_data($this->_pickup_location, $location_array);
									$order_meta_handler->ph_update_meta_data($this->_pickup_scheduled_date, $date_array);

									$order_meta_handler->ph_save_meta_data();
								}

								if ($count) {
									wf_admin_notice::add_notice('FedEx Pickup Requested for Order ID(s): ' . implode(", ", $order_ids), 'notice');
									$count = false;
								}

								if (isset($associate_pickup_orders) && is_array($associate_pickup_orders)) {

									$associate_pickup_orders_string = implode(', ', $associate_pickup_orders);
									wf_admin_notice::add_notice('FedEx Pickup Scheduled Succesfully for Order ID(s): ' . $associate_pickup_orders_string, 'notice');
								} else {
									wf_admin_notice::add_notice('FedEx Pickup Scheduled Succesfully for Order ID: ' . $order_id, 'notice');
								}
							} else if (isset($pickup_data['error']) && $pickup_data['error'] > 0) {
								wf_admin_notice::add_notice('Order #' . $order_id . ' - Pickup Request Error: ' . $pickup_data['message'], 'error');
							}
						}
					}
				}
			} 
		} else if ($action == 'fedex_pickup_cancel') { //Cancel Pickup
			foreach ($order_ids as $order_id) {
				$result	=	$this->pickup_cancel($order_id);
				if (isset($result) && isset($result['error']) && $result['error'] == 0) {
					$pickup_canceled_order_ids = $this->delete_pickup_details($order_id);
					wf_admin_notice::add_notice('FedEx Pickup Cancelled for Order #' . $order_id . '.', 'notice');

					if (is_array($pickup_canceled_order_ids) && !empty($pickup_canceled_order_ids)) {

						$associate_pickup_orders_string = implode(', #', $pickup_canceled_order_ids);

						wf_admin_notice::add_notice('FedEx Pickup Cancelled for Associate Orders #' . $associate_pickup_orders_string . '.', 'notice');
					}
				} else {
					wf_admin_notice::add_notice('Order #' . $order_id . ': ' . $result['message'], 'error');
				}
			}
		}
	}

	public function get_pickup_no($order_id)
	{
		if (empty($order_id))
			return false;

		$pickup_confirmation_number 		= '';
		$pickup_confirmation_number_array	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_confirmation_number);
		$pickup_location_array				= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_location);

		if (is_array($pickup_confirmation_number_array) && !empty($pickup_confirmation_number_array)) {
			foreach ($pickup_confirmation_number_array as $service => $pickup_number) {

				if (empty($pickup_location_array[$service])) {
					$pickup_confirmation_number 	.= $pickup_number . ', ';
				} else {
					$pickup_confirmation_number 	.= $pickup_location_array[$service] . '-' . $pickup_number . ', ';
				}
			}

			if (!empty($pickup_confirmation_number)) {
				$pickup_confirmation_number = rtrim($pickup_confirmation_number, ', ');
			}
		}

		return $pickup_confirmation_number;
	}

	/**
	 * Get pickup details
	 *
	 * @param $order_id
	 */
	public function get_pickup_details($order_id)
	{
		$details	=	array(
			'pickup_confirmation_number'	=>	PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_confirmation_number),
			'pickup_location'				=>	PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_location),
			'pickup_scheduled_date'			=>	PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_scheduled_date),
		);
		return $details;
	}
	
	public function delete_pickup_details($order_id)
	{

		$pickup_confirmation_number = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_confirmation_number);

		$pickup_canceled_order_ids = [];

		foreach ($pickup_confirmation_number as $service => $pickup_no) {

			$associate_pickup_orders = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_associate_pickup_orders');

			if (isset($associate_pickup_orders[$pickup_no]) && is_array($associate_pickup_orders[$pickup_no]) && !empty($associate_pickup_orders[$pickup_no])) {

				foreach ($associate_pickup_orders[$pickup_no] as $key => $assoc_order_id) {

					$meta_key_and_remove_data_key = array(

						$this->_pickup_confirmation_number => $service,
						$this->_pickup_location => $service,
						$this->_pickup_scheduled_date => $service,
						'ph_associate_pickup_orders' => $pickup_no,
					);

					$this->ph_update_order_pickup_meta_data($assoc_order_id, $meta_key_and_remove_data_key);

					$pickup_canceled_order_ids[] = $assoc_order_id;
				}
			}

			$meta_key_and_remove_data_key = array(

				$this->_pickup_confirmation_number => $service,
				$this->_pickup_location => $service,
				$this->_pickup_scheduled_date => $service,
				'ph_associate_pickup_orders' => $pickup_no,
			);

			$this->ph_update_order_pickup_meta_data($order_id, $meta_key_and_remove_data_key);
		}

		return $pickup_canceled_order_ids;
	}

	/**
	 * Updating pickup details
	 * 
	 * @param int $order_id
	 * @param array $meta_key_and_remove_data_key
	 */
	public function ph_update_order_pickup_meta_data($order_id, $meta_key_and_remove_data_key)
	{

		foreach ($meta_key_and_remove_data_key as $meta_key => $remove_data_key) {

			$ph_meta_data = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $meta_key);

			if (isset($ph_meta_data[$remove_data_key])) {

				unset($ph_meta_data[$remove_data_key]);

				PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data($order_id, $meta_key, $ph_meta_data);
			}
		}
	}

	public function is_pickup_requested($order_id)
	{
		return $this->get_pickup_no($order_id) ? true : false;
	}

	/**
	 * Cancel pickup
	 *
	 * @param int $order_id
	 * @return array $pickup_result
	 */
	public function pickup_cancel($order_id)
	{
		if (!class_exists('wf_fedex_woocommerce_shipping_admin_helper'))
			include_once 'class-wf-fedex-woocommerce-shipping-admin-helper.php';
		$helper	=	new wf_fedex_woocommerce_shipping_admin_helper();

		$order = wc_get_order($order_id);
		if (!$order)
			return;

		$pickup_result	=	$helper->pickup_cancel($order, $order_id, $this->get_pickup_details($order_id));
		return $pickup_result;
	}

	/**
	 * Display pickup status
	 *
	 * @param string column
	 * @param int $order_id
	 */
	public function display_order_list_pickup_status($column, $order_id)
	{
		if (!$this->is_pickup_requested($order_id)) {
			return;
		}

		$pickup_number = $this->get_pickup_no($order_id);

		switch ($column) {
			case 'shipping_address':
				printf('<small class="meta">' . __('FedEx Pickup Number(s): ' . $pickup_number, 'ph-fedex-woocommerce-shipping') . '</small>');
				break;
			case 'fedex_pickup_info':
				printf('<small class="meta">' . __('Pickup Number(s): ' . $pickup_number, 'ph-fedex-woocommerce-shipping') . '</small>');
				printf('<small class="meta">' . __('Pickup Date(s): ' . $this->get_pickup_dates($order_id), 'ph-fedex-woocommerce-shipping') . '</small>');
				break;
		}
	}

	/**
	 * Get Pickup Dates
	 *
	 * @param int $order_id
	 * @return string $date_string
	 */
	private function get_pickup_dates($order_id)
	{
		$date_string = '';

		$pickup_scheduled_date = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, $this->_pickup_scheduled_date);

		if (!empty($pickup_scheduled_date) && is_array($pickup_scheduled_date)) {

			$pickup_scheduled_date = array_unique($pickup_scheduled_date);

			$date_string = implode(', ', $pickup_scheduled_date);
		}

		return $date_string;
	}
}
new PH_WC_Fedex_Pickup;
