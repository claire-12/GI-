<?php

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

class WF_Tracking_Admin_FedEx {

	const SHIPPING_METHOD_DISPLAY = 'Tracking';
	const TRACKING_TITLE_DISPLAY  = 'FedEx Shipment Tracking';

	const TRACK_SHIPMENT_KEY   = 'wf_fedex_shipment'; // Note: If this key is getting changed, do the same change in JS code below.
	const SHIPMENT_SOURCE_KEY  = 'wf_fedex_shipment_source';
	const SHIPMENT_RESULT_KEY  = 'wf_fedex_shipment_result';
	const TRACKING_MESSAGE_KEY = 'wffedextrackingmsg';
	const TRACKING_METABOX_KEY = 'WF_Tracking_Metabox_FedEx';

	/**
	 * Settings
	 */
	public $settings;
	/**
	 * Display FedDx meta box on order
	 */
	public $display_fedex_meta_box_on_order;
	/**
	 * Disable for customer
	 */
	public $disable_for_customer;
	/**
	 * Tracking data
	 */
	public $tracking_data;

	private function wf_init() {
		global $xa_fedex_settings;
		$this->settings = $xa_fedex_settings;

		if ( ! is_array( $this->settings ) || ! isset( $this->settings['account_number'] ) ) {
			$this->settings = get_option( 'woocommerce_' . WF_Fedex_ID . '_settings', null );
		}
		$this->display_fedex_meta_box_on_order = isset( $this->settings['display_fedex_meta_box_on_order'] ) ? $this->settings['display_fedex_meta_box_on_order'] : 'yes';
		$this->disable_for_customer            = ( isset( $this->settings['disable_customer_tracking'] ) && ! empty( $this->settings['disable_customer_tracking'] ) ) ? $this->settings['disable_customer_tracking'] : 'no';

		if ( ! class_exists( 'WfTrackingFactory' ) ) {
			include_once 'track/class-wf-tracking-factory.php';
		}
		if ( ! class_exists( 'Ph_FedEx_Tracking_Util' ) ) {
			include_once 'track/class-wf-tracking-util.php';
		}

		// Sorted tracking data.
		$this->tracking_data = Ph_FedEx_Tracking_Util::load_tracking_data( true );
	}

	function __construct() {
		$this->wf_init();

		if ( is_admin() ) {
			if ( $this->display_fedex_meta_box_on_order != 'no' ) {

				add_action( 'add_meta_boxes', array( $this, 'wf_add_tracking_metabox' ), 15, 2 );
			}
			add_action( 'admin_notices', array( $this, 'wf_admin_notice' ), 15 );

			if ( isset( $_GET[ self::TRACK_SHIPMENT_KEY ] ) ) {
				add_action( 'init', array( $this, 'wf_display_admin_track_shipment' ), 15 );
			}
		}

		// Shipment Tracking - Customer Order Details Page.
		if ( $this->disable_for_customer == 'no' ) {

			add_action( 'woocommerce_view_order', array( $this, 'wf_display_tracking_info_for_customer' ), 6 );
			add_action( 'woocommerce_view_order', array( $this, 'wf_display_tracking_api_info_for_customer' ), 20 );
			add_action( 'woocommerce_email_order_meta', array( $this, 'wf_add_tracking_info_to_email' ), 20 );
		}

		// To get shipment tracking details outside
		add_action( 'ph_fedex_fetch_shipment_tracking_details', array( $this, 'wf_add_tracking_info_to_email' ) );
	}

	function wf_add_tracking_info_to_email( $order, $sent_to_admin = false, $plain_text = false ) {
		$order_id              = $order->get_id();
		$shipment_result_array = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, self::SHIPMENT_RESULT_KEY );

		if ( ! empty( $shipment_result_array ) ) {
			echo '<h3>' . __( 'Shipping Detail', 'ph-fedex-woocommerce-shipping' ) . '</h3>';
			$shipment_source_data = $this->get_shipment_source_data( $order_id );
			$order_notice         = Ph_FedEx_Tracking_Util::get_shipment_display_message( $shipment_result_array, $shipment_source_data );
			echo '<p>' . $order_notice . '</p></br>';
		}
	}

	public function wf_display_tracking_info_for_customer( $order_id ) {

		$shipment_result_array = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, self::SHIPMENT_RESULT_KEY );

		if ( ! empty( $shipment_result_array ) ) {
			// Note: There is a bug in wc_add_notice which gives inconstancy while displaying messages.
			// Uncomment after it gets resolved.
			// $this->display_notice_message( $order_notice );
			$shipment_source_data = $this->get_shipment_source_data( $order_id );
			$order_notice         = Ph_FedEx_Tracking_Util::get_shipment_display_message( $shipment_result_array, $shipment_source_data );
			echo $order_notice;
		}
	}

	public function wf_display_tracking_api_info_for_customer( $order_id ) {
		$turn_off_api = get_option( Ph_FedEx_Tracking_Util::TRACKING_SETTINGS_TAB_KEY . Ph_FedEx_Tracking_Util::TRACKING_TURN_OFF_API_KEY );
		if ( 'yes' == $turn_off_api ) {
			return;
		}

		$shipment_result_array = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, self::SHIPMENT_RESULT_KEY );

		if ( ! empty( $shipment_result_array ) ) {
			if ( ! empty( $shipment_result_array['tracking_info_api'] ) ) {
				$this->display_api_message_table( $shipment_result_array['tracking_info_api'] );
			}
		}
	}

	function display_api_message_table( $tracking_info_api_array ) {

		echo '<h3>' . __( self::TRACKING_TITLE_DISPLAY, 'ph-fedex-woocommerce-shipping' ) . '</h3>';
		echo '<table class="shop_table wooforce_tracking_details">
			<thead>
				<tr>
					<th class="product-name">' . __( 'Shipment ID', 'ph-fedex-woocommerce-shipping' ) . '<br/>(' . __( 'Follow link for detailed status.', 'ph-fedex-woocommerce-shipping' ) . ')</th>
					<th class="product-total">' . __( 'Status', 'ph-fedex-woocommerce-shipping' ) . '</th>
				</tr>
			</thead>
			<tfoot>';

		foreach ( $tracking_info_api_array as $tracking_info_api ) {
			echo '<tr>';
			echo '<th scope="row">' . '<a href="' . $tracking_info_api['tracking_link'] . '' . $tracking_info_api['tracking_id'] . '" target="_blank">' . $tracking_info_api['tracking_id'] . '</a></th>';

			if ( '' == $tracking_info_api['api_tracking_status'] ) {
				$message = __( 'Unable to update real time status at this point of time. Please follow the link on shipment id to check status.', 'ph-fedex-woocommerce-shipping' );
			} else {
				$message = $tracking_info_api['api_tracking_status'];
			}
			echo '<td><span>' . __( $message, 'ph-fedex-woocommerce-shipping' ) . '</span></td>';
			echo '</tr>';
		}
		echo '</tfoot>
		</table>';
	}

	function display_notice_message( $message, $type = 'notice' ) {
		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '>=' ) ) {
			wc_add_notice( $message, $type );
		} else {
			global $woocommerce;
			$woocommerce->add_message( $message );
		}
	}

	function wf_admin_notice() {

		if ( ! isset( $_GET[ self::TRACKING_MESSAGE_KEY ] ) && empty( $_GET[ self::TRACKING_MESSAGE_KEY ] ) ) {
			return;
		}

		$is_hpo_enabled = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) ? wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() : false;
		$order_id       = $is_hpo_enabled ? $_GET['id'] : $_GET['post'];

		$wftrackingmsg = $_GET[ self::TRACKING_MESSAGE_KEY ];

		switch ( $wftrackingmsg ) {
			case '0':
				echo '<div class="error"><p>' . self::SHIPPING_METHOD_DISPLAY . ': ' . __( 'Sorry, Unable to proceed.', 'ph-fedex-woocommerce-shipping' ) . '</p></div>';
				break;
			case '4':
				echo '<div class="error"><p>' . self::SHIPPING_METHOD_DISPLAY . ': ' . __( 'Unable to track the shipment. Please cross check shipment id or try after some time.', 'ph-fedex-woocommerce-shipping' ) . '</p></div>';
				break;
			case '5':
				$wftrackingmsg = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, self::TRACKING_MESSAGE_KEY );
				if ( '' != trim( $wftrackingmsg ) ) {
					echo '<div class="updated"><p>' . __( $wftrackingmsg, 'ph-fedex-woocommerce-shipping' ) . '</p></div>';
				}
				break;
			case '6':
				echo '<div class="updated"><p>' . __( 'Tracking is unset.', 'ph-fedex-woocommerce-shipping' ) . '</p></div>';
				break;
			case '7':
				echo '<div class="updated"><p>' . __( 'Tracking Data is reset to default.', 'ph-fedex-woocommerce-shipping' ) . '</p></div>';
				break;
			default:
				break;
		}
	}

	function wf_add_tracking_metabox( $postType, $postObject ) {

		$is_hpo_enabled = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) ? wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() : false;
		$screen_type    = $is_hpo_enabled ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
		$order_object   = ( $postObject instanceof WP_Post ) ? wc_get_order( $postObject->ID ) : ( ( $postObject instanceof WC_Order ) ? $postObject : '' );

		if ( ! $order_object instanceof WC_Order ) {
			return;
		}

		// Shipping method is available.
		add_meta_box(
			self::TRACKING_METABOX_KEY,
			__(
				self::TRACKING_TITLE_DISPLAY,
				'ph-fedex-woocommerce-shipping'
			),
			array( $this, 'wf_tracking_metabox_content' ),
			$screen_type,
			'side',
			'default'
		);
	}

	function get_shipment_source_data( $post_id ) {
		$shipment_source_data = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $post_id, self::SHIPMENT_SOURCE_KEY );

		if ( empty( $shipment_source_data ) || ! is_array( $shipment_source_data ) ) {
			$shipment_source_data                     = array();
			$shipment_source_data['shipment_id_cs']   = '';
			$shipment_source_data['shipping_service'] = '';
			$shipment_source_data['order_date']       = '';
		}
		return $shipment_source_data;
	}

	function wf_tracking_metabox_content( $postOrOrderObject ) {
		$order = ( $postOrOrderObject instanceof WP_Post ) ? wc_get_order( $postOrOrderObject->ID ) : $postOrOrderObject;

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$shipmentId = '';

		$order_id     = $order->get_id();
		$tracking_url = admin_url( '/?post=' . ( $order_id ) );

		$shipment_source_data = $this->get_shipment_source_data( $order_id );

		// To support Shipment Tracking Integration
		if ( ! empty( $shipment_source_data['shipment_id_cs'] ) ) {
			do_action( 'ph_fedex_shipment_tracking_detail_ids', $shipment_source_data['shipment_id_cs'], $order_id );
		}

		?>
		<ul class="order_actions submitbox">
			<li id="actions" class="wide">
				<select name="shipping_service_fedex" id="shipping_service_fedex">
					<?php
					echo "<option value=''>" . __( 'None', 'ph-fedex-woocommerce-shipping' ) . '</option>';
					echo '<option value=' . 'fedex' . ' ' . selected( $shipment_source_data['shipping_service'], 'fedex' ) . ' >' . __( 'FedEx', 'ph-fedex-woocommerce-shipping' ) . '</option>';
					?>
				</select><br>
				<strong><?php _e( 'Enter Tracking IDs', 'ph-fedex-woocommerce-shipping' ); ?></strong>
				<img class="help_tip" style="float:none;" data-tip="<?php _e( 'Comma separated, in case of multiple shipment ids for this order.', 'ph-fedex-woocommerce-shipping' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /><br>
				<textarea id="tracking_fedex_shipment_ids" class="input-text" type="text" name="tracking_fedex_shipment_ids" ><?php echo $shipment_source_data['shipment_id_cs']; ?></textarea><br>
				<strong>Shipment Date</strong>
				<img class="help_tip" style="float:none;" data-tip="<?php _e( 'This field is Optional.', 'ph-fedex-woocommerce-shipping' ); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" /><br>
				<input type="text" id="order_date_fedex" class="wf-date-picker" value="<?php echo $shipment_source_data['order_date']; ?>"></p>
			</li>
			<li id="" class="wide">
				<a class="button button-primary woocommerce_shipment_fedex_tracking tips" href="<?php echo $tracking_url; ?>" data-tip="<?php _e( 'Save/Show Tracking Info', 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( 'Save/Show Tracking Info', 'ph-fedex-woocommerce-shipping' ); ?></a>
			</li>
		</ul>
		<script>
			jQuery(document).ready(function($) {
				$( ".wf-date-picker" ).datepicker();
			});
			
			jQuery("a.woocommerce_shipment_fedex_tracking").on("click", function() {
				location.href = this.href + '&wf_fedex_shipment=' + jQuery('#tracking_fedex_shipment_ids').val().replace(/ /g,'')+'&shipping_service='+ jQuery( "#shipping_service_fedex" ).val()+'&order_date='+ jQuery( "#order_date_fedex" ).val();
				return false;
			});
		</script>
		<?php
	}

	function wf_display_admin_track_shipment() {
		if ( ! $this->wf_user_check() ) {
			_e( "You don't have admin privileges to view this page.", 'ph-fedex-woocommerce-shipping' );
			exit;
		}

		$post_id          = isset( $_GET['post'] ) ? $_GET['post'] : '';
		$shipment_id_cs   = isset( $_GET[ self::TRACK_SHIPMENT_KEY ] ) ? $_GET[ self::TRACK_SHIPMENT_KEY ] : '';
		$shipping_service = isset( $_GET['shipping_service'] ) ? $_GET['shipping_service'] : '';
		$order_date       = isset( $_GET['order_date'] ) ? $_GET['order_date'] : '';
		// Setting up custom message option.
		$fedex_settings = get_option( 'woocommerce_' . WF_Fedex_ID . '_settings', null );
		if ( ! empty( $fedex_settings['custom_message'] ) ) {
			update_option( Ph_FedEx_Tracking_Util::TRACKING_SETTINGS_TAB_KEY . Ph_FedEx_Tracking_Util::TRACKING_MESSAGE_KEY, $fedex_settings['custom_message'] );
		}

		$shipment_source_data = Ph_FedEx_Tracking_Util::prepare_shipment_source_data( $post_id, $shipment_id_cs, $shipping_service, $order_date );

		$shipment_result = $this->get_shipment_info( $post_id, $shipment_source_data );

		if ( null != $shipment_result && is_object( $shipment_result ) ) {
			$shipment_result_array = Ph_FedEx_Tracking_Util::convert_shipment_result_obj_to_array( $shipment_result );

			PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data( $post_id, self::SHIPMENT_RESULT_KEY, $shipment_result_array );
			$admin_notice = Ph_FedEx_Tracking_Util::get_shipment_display_message( $shipment_result_array, $shipment_source_data );
		} else {
			$admin_notice = __( 'Unable to update tracking info.', 'ph-fedex-woocommerce-shipping' );
			PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data( $post_id, self::SHIPMENT_RESULT_KEY, '' );
		}

		self::display_admin_notification_message( $post_id, $admin_notice );
	}

	public static function display_admin_notification_message( $post_id, $admin_notice ) {
		$wftrackingmsg = 5;
		PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data( $post_id, self::TRACKING_MESSAGE_KEY, $admin_notice );
		wp_redirect( admin_url( '/post.php?post=' . $post_id . '&action=edit&' . self::TRACKING_MESSAGE_KEY . '=' . $wftrackingmsg ) );
		exit;
	}

	function get_shipment_info( $post_id, $shipment_source_data ) {

		if ( empty( $post_id ) ) {
			$wftrackingmsg = 0;
			wp_redirect( admin_url( '/post.php?post=' . $post_id . '&action=edit&' . self::TRACKING_MESSAGE_KEY . '=' . $wftrackingmsg ) );
			exit;
		}

		$order              = wc_get_order( $post_id );
		$order_meta_handler = new PH_WC_Fedex_Storage_Handler( $order );

		if ( '' == $shipment_source_data['shipping_service'] ) {

			$order_meta_handler->ph_update_meta_data( self::SHIPMENT_SOURCE_KEY, $shipment_source_data );
			$order_meta_handler->ph_update_meta_data( self::SHIPMENT_RESULT_KEY, '' );
			$order_meta_handler->ph_save_meta_data();

			$wftrackingmsg = 6;
			wp_redirect( admin_url( '/post.php?post=' . $post_id . '&action=edit&' . self::TRACKING_MESSAGE_KEY . '=' . $wftrackingmsg ) );
			exit;
		}

		$order_meta_handler->ph_update_meta_data( self::SHIPMENT_SOURCE_KEY, $shipment_source_data );

		$order_meta_handler->ph_save_meta_data();

		try {
			$shipment_result = Ph_FedEx_Tracking_Util::get_shipment_result( $shipment_source_data );
		} catch ( Exception $e ) {
			$wftrackingmsg = 0;
			wp_redirect( admin_url( '/post.php?post=' . $post_id . '&action=edit&' . self::TRACKING_MESSAGE_KEY . '=' . $wftrackingmsg ) );
			exit;
		}

		return $shipment_result;
	}

	function wf_user_check() {
		if ( is_admin() ) {
			return true;
		}
		return false;
	}
}

new WF_Tracking_Admin_FedEx();

?>
