<?php // phpcs:ignore
/**
 * Plugin Name: Repeat Order for Woocommerce
 * Plugin URI: https://poly-res.com/plugins/repeat-order-for-woocommerce/
 * Description: Add an "order again" button in Recent Orders list
 * Version: 1.3.3
 * Author: polyres
 * Author URI: https://poly-res.com/
 * Text Domain: repeat-order-for-woocommerce
 * Domain Path: /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/PolyRes/repeat-order-for-woocommerce
 * GitHub Branch:     master
 * Requires WP:       4.8
 * Requires PHP:      5.3
 * Tested up to: 6.4.3
 * WC requires at least: 3.4.0
 * WC tested up to: 8.5.2
 *
 * @link      https://poly-res.com
 * @author    Frank Neumann-Staude
 * @license   GPL-2.0+
 * @package   Repeat_Order_For_Woocommerce
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PRRO_VERSION', '1.3.3' );


/**
 * Main class
 *
 * @since    1.0.0
 */
class RepeatOrderForWoocommerce {

	/**
	 * Constructor
	 *
	 * @since    1.0.0
	 * @access  public
	 * @action repeat_order_for_woocommerce_init
	 */
	public function __construct() {
		$plugin = plugin_basename( __FILE__ );
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_repeat_order', array( $this, 'settings_tab' ) );
		add_action( 'woocommerce_update_options_repeat_order', array( $this, 'update_settings' ) );
		add_filter( 'woocommerce_my_account_my_orders_actions', array( $this, 'add_order_again_action' ), 10, 2 );
		add_action( 'woocommerce_ordered_again', array( $this, 'ordered_again' ) );
		add_action( 'woocommerce_thankyou', array( $this, 'create_order_note' ), 10, 1 );
		add_action( 'woocommerce_cart_is_empty', array( $this, 'reset_session_flag' ) );
		add_filter( "plugin_action_links_$plugin", array( $this, 'plugin_add_settings_link' ) );
		add_action( 'init', array( $this, 'reactivate_action' ), 9999 );
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'current_screen', array( $this, 'current_screen' ) );
		add_filter( 'woocommerce_admin_order_actions', array( $this, 'add_custom_order_status_actions_button' ), 100, 2 );
		add_action( 'init', array( $this, 'allow_data_order_id' ) );
		add_filter( 'woocommerce_account_orders_columns', array( $this, 'add_my_account_orders_column' ) );
		add_filter( 'repeat_order_for_woocommerce_settings_extend', array( $this, 'add_settings' ) );
		add_action( 'woocommerce_my_account_my_orders_column_reorder', array( $this, 'my_orders_reorder_column' ) );
		add_filter( 'repeat_order_for_woocommerce_order_status', array( $this, 'repeat_order_for_woocommerce_order_status' ), 10, 2 );
		add_action( 'upgrader_process_complete', array( $this, 'upgrade_completed' ), 10, 2 );
		add_filter( 'woocommerce_valid_order_statuses_for_order_again', array( $this, 'woocommerce_valid_order_statuses_for_order_again' ), 10 );
		add_filter( 'storeabill_document_shortcodes', array( $this, 'add_shortcodes_for_germanized' ), 10, 2 );
		register_activation_hook( __FILE__, array( 'RepeatOrderForWoocommerce', 'install' ) );
		register_uninstall_hook( __FILE__, array( 'RepeatOrderForWoocommerce', 'uninstall' ) );

		// Declare WooCommerce HPOS compatibility.
		add_action( 'before_woocommerce_init', array( $this, 'declare_compatibility' ) );

		do_action( 'repeat_order_for_woocommerce_init' );
	}


	/**
	 * Setup plugin environment to new version.
	 *
	 * @since  1.3.0
	 * @access public
	 * @param  obj   $upgrader_object  Upgrader object.
	 * @param  array $options          Options.
	 * @return void
	 */
	public function upgrade_completed( $upgrader_object, $options ) {
		$our_plugin = plugin_basename( __FILE__ );
		// If an update has taken place and the updated type is plugins and the plugins element exists.
		if ( 'update' === $options['action']
			&& 'plugin' === $options['type']
			&& isset( $options['plugins'] )
			&& is_array( $options['plugins'] )
			&& in_array( $our_plugin, $options['plugins'], true )
		) {
			// Retrieve the current version from the database.
			$current_version = get_option( 'prro_version' );
			// Compare the current version with the new version.
			// If the current version is less than the new version, run the update logic.
			if ( version_compare( $current_version, PRRO_VERSION, '<' ) ) {
				// Specific update logic for version 1.2.0 to the new version.
				if ( version_compare( $current_version, '1.2.0', '<=' ) ) {
					// Perform operations for the new version here.
					foreach ( wc_get_order_statuses() as $key => $name ) {
						if ( false === get_option( 'prro_order_status_' . $key ) ) {
							add_option( 'prro_order_status_' . $key, 'wc-completed' === $key ? 'yes' : 'no' );
						}
					}
				}
				// Update the stored version to the new version after all updates are completed.
				update_option( 'prro_version', PRRO_VERSION );
			}
		}
	}

	/**
	 * Load the translation
	 *
	 * @since    1.0.0
	 * @access  public
	 * @filter plugin_locale
	 */
	public function load_plugin_textdomain() {
		$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'repeat-order-for-woocommerce' );

		load_textdomain( 'repeat-order-for-woocommerce', trailingslashit( WP_LANG_DIR ) . 'repeat-order-for-woocommerce/repeat-order-for-woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'repeat-order-for-woocommerce', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * @param $shortcodes
	 * @param $shortcode_instance
	 * @return void
	 */
	public function add_shortcodes_for_germanized( $shortcodes, $shortcode_instance ) {

		$shortcodes['reorder_id'] = function( $atts, $content = '' ) use ( $shortcode_instance ) {
			$order = $shortcode_instance->get_document()->get_order();
			if ( $order !== false ) {
				$reorderID = $order->get_meta( '_reorder_from_id', true );
				return $reorderID;
			} else {
				return '';
			}
		};

		$shortcodes['if_reorder'] = function( $atts, $content = '' ) use ( $shortcode_instance ) {
			$order = $shortcode_instance->get_document()->get_order();
			if ( $order !== false ) {
				$reorderID = $order->get_meta( '_reorder_from_id', true );
				if ( $reorderID === false ) {
					return '';
				} else {
					return do_shortcode($content);
				}
			} else {
				return '';
			}
		};
		return $shortcodes;
	}

	/**
	 * Reactivate the reorder link in order details
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	public function reactivate_action() {
		if ( 'yes' == get_option( 'prro_reactivate_action' ) ) {
			add_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button', 9999 );
		}
	}

	/**
	 * Add settings to the settings page
	 *
	 * @since    1.3.0
	 * @access  public
	 * @filters repeat_order_for_woocommerce_settings_extend
	 */
	public function add_settings( $settings ) {
		$count = 1;
		$title = __('Activation and deactivation of order status', 'repeat-order-for-woocommerce');
		foreach (wc_get_order_statuses() as $key => $name) {
			if ($count > 1) {
				$title = '';
			}
			$settings['order_status_' . $key] = array(
				'name' => $title,
				'type' => 'checkbox',
				'desc' => $name,
				'id' => 'prro_order_status_' . $key,
			);
			$count++;
		}
		return $settings;
	}

	/**
	 * Check order status
	 *
	 * @since   1.3.0
	 * @access public
	 *
	 * @param $status boolean 
	 * @param $order object
	 *
	 * @return bool
	 */
	public function repeat_order_for_woocommerce_order_status( $status, $order ) {
		$status = false;
		foreach (wc_get_order_statuses() as $key => $name) {
			if ('yes' == get_option('prro_order_status_' . $key)) {
				if ($order->has_status(('wc-' === substr($key, 0, 3) ? substr($key, 3) : $key))) {
					$status = true;
				}
			}
		}
		return $status;
	}

	/**
	 * Extend the woo filter with allowed status for reorder
	 * 
	 * @since   1.3.0
	 *
	 * @param $status
	 * @return $status
	 */
	public function woocommerce_valid_order_statuses_for_order_again( $status ) {
		$status = array();
		foreach (wc_get_order_statuses() as $key => $name) {
			if ('yes' == get_option('prro_order_status_' . $key)) {
				$status[] = substr($key, 3);
			}
		}
		return $status;
	}

	/**
	 * Add a link to plugin settings to the plugin list
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	public function plugin_add_settings_link( $links ) {
		$settings_link = '<a href="'. admin_url( 'admin.php?page=wc-settings&tab=repeat_order' ) .'">' . __( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	/**
	 * Save old order id to woocommerce session
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	public function ordered_again( $order_id ) {
		WC()->session->set( 'reorder_from_orderid', $order_id );
		$notice = get_option( 'prro_cart_notice' );
		if ( $notice != '' ) {
			wc_add_notice( $notice, 'notice' );
		}
	}

	/**
	 * Create a order note with link to the old order
	 *
	 * @since    1.0.0
	 * @access  public
	 * @filters repeat_order_for_woocommerce_order_note
	 */
	public function create_order_note( $order_id ) {
		$reorder_id = WC()->session->get( 'reorder_from_orderid');
		if ($reorder_id != '' ) {
			$order = wc_get_order( $order_id );
			if ( ! is_a( $order, 'WC_Order' ) ) {
				return;
			}
			$order->add_meta_data( '_reorder_from_id', $reorder_id, true );
			$order->save();
		}
		if ( get_option( 'prro_create_order_note' ) != 'yes' ) {
			return;
		}
		if ($reorder_id != '' ) {
			$order = wc_get_order( $order_id );
			$url = get_edit_post_link( $reorder_id );
			$note = sprintf( wp_kses( __( 'This is an reorder of order #<a href="%1s">%2s</a> <a href="#" class="order-preview" data-order-id="%3s" title="Vorschau"></a>. As a rule, customers can access items that have already been saved and linked to the selected delivery address when placing a "new order". Please note, however, that customers may have changed the number and quantity of items during the ordering process.', 'repeat-order-for-woocommerce' ), array(  'a' => array( 'href' => array(), 'class' => array(), 'data-order-id' => array() ) ) ), esc_url( $url ), $reorder_id,  $reorder_id );
			$order->add_order_note( apply_filters( 'repeat_order_for_woocommerce_order_note', $note, $reorder_id, $order_id ) );
		}
		WC()->session->set( 'reorder_from_orderid' , '' );
	}

	/**
	 * Add a reorder link to the order list in user account
	 *
	 * @since    1.0.0
	 * @access  public
	 * @filter  repeat_order_for_woocommerce_order_note
	 */
	public function add_order_again_action( $actions, $order ) {
		if ( get_option( 'prro_show_repeat_order_on_order_list' ) != 'yes' ) {
			return $actions;
		}
		if ( ! $order || ! $order->has_status( apply_filters( 'woocommerce_valid_order_statuses_for_order_again', array( 'completed' ) ) ) || ! is_user_logged_in() ) {
			return $actions;
		}

		$actions['order-again'] = array(
			'url'  => wp_nonce_url( add_query_arg( 'order_again', $order->get_id() ) , 'woocommerce-order_again' ),
			'name' => __( 'Order again', 'woocommerce' )
		);

		return $actions;
	}

	/**
	 * Add a new settings tab to woocommerce/settings
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	public function add_settings_tab( $settings_tabs ) {
		$settings_tabs['repeat_order'] = _x( 'Repeat Order', 'WooCommerce Settngs Tab', 'repeat-order-for-woocommerce' );
		return $settings_tabs;
	}

	/**
	 * @ince    1.0.0
	 * @access  public
	 */
	public  function settings_tab() {
		woocommerce_admin_fields( self::get_settings() );
	}

	/**
	 * @since    1.0.0
	 * @access  public
	 */
	function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}


	/**
	 * Define the settings for this plugin
	 *
	 * @since    1.0.0
	 * @access  public
	 * @filters repeat_order_for_woocommerce_settings
	 */
	public function get_settings() {
		$settings = array(
			'section_title' => array(
				'name'     => __( 'Repeat order for woocommerce', 'repeat-order-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'prro_section_title',
			),
			'hide_in_loop' => array(
				'name' => __( 'Show link on order list', 'repeat-order-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( 'If checked, it show the repeat order link on the order list', 'repeat-order-for-woocommerce' ),
				'id'   => 'prro_show_repeat_order_on_order_list',
			),
			'hide_in_user_order_list' => array(
				'name' => __( 'Show link to old order on users my order list', 'repeat-order-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( 'If checked, it show under "My account - Orders" the order number of the reorder in a new column.', 'repeat-order-for-woocommerce' ),
				'id'   => 'prro_show_user_reorder_list',
			),
			'hide_in_cart' => array(
				'name' => __( 'Create order note', 'repeat-order-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( 'If checked, it create an order note with a link to the original order.', 'repeat-order-for-woocommerce' ),
				'id'   => 'prro_create_order_note',
			),
			'reactivate_order_again' => array(
				'name' => __( 'Reactivate order again in order detail', 'repeat-order-for-woocommerce' ),
				'type' => 'checkbox',
				'desc' => __( 'If you are using a plugin or theme who deactivate the order again link/button or you have the plugin WooCommerce Germanized (with version 2.0.4 or older) activated then check this option to reactivate the action.', 'repeat-order-for-woocommerce' ),
				'id'   => 'prro_reactivate_action',
			),
			'cart_notice' => array(
				'name' => __( 'Your own notice in cart, after an reorder', 'repeat-order-for-woocommerce' ),
				'type' => 'text',
				'desc' => __( 'Display an own notice in the cart, after the reorder link is clicked.', 'repeat-order-for-woocommerce' ),
				'id'   => 'prro_cart_notice',
			),
		);

		$settings = apply_filters( 'repeat_order_for_woocommerce_settings_extend', $settings );

		$settings['section_end'] = array(
			'type' => 'sectionend',
			'id'   => 'prro_section_end',
		);

		return apply_filters( 'repeat_order_for_woocommerce_settings', $settings );
	}

	/**
	 * Check cart, if empty reset the reorder flag in woocommerce session
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	public function reset_session_flag() {
		WC()->session->set( 'reorder_from_orderid' , '' );
	}

	/**
	 * Setup Database on installing the plugin
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	static public function install() {
		if ( false === get_option( 'prro_show_repeat_order_on_order_list' ) ) {
			add_option( 'prro_show_repeat_order_on_order_list', 'yes' );
		}
		if ( false === get_option( 'prro_create_order_note' ) ) {
			add_option( 'prro_create_order_note', 'yes' );
		}
		if ( false === get_option( 'prro_reactivate_action' ) ) {
			add_option( 'prro_reactivate_action', 'no' );
		}
		if ( false === get_option( 'prro_cart_notice' ) ) {
			add_option( 'prro_cart_notice', '' );
		}
		if ( false === get_option( 'prro_show_user_reorder_list' ) ) {
			add_option( 'prro_show_user_reorder_list', 'no' );
		}
		if ( false === get_option( 'prro_show_reorder_in_email' ) ) {
			add_option( 'prro_show_reorder_in_email', 'no' );
		}
		foreach ( wc_get_order_statuses() as $key => $name ) {
			if ( false === get_option( 'prro_order_status_' . $key ) ) {
				if ( $key == 'wc-completed' ) {
					add_option( 'prro_order_status_' . $key, 'yes' );
				} else {
					add_option( 'prro_order_status_' . $key, 'no' );
				}
			}
		}
		if ( false === get_option( 'prro_version' ) ) {
			add_option( 'prro_version', PRRO_VERSION );
		}

	}

	/**
	 * Cleanup Database on deleting the plugin
	 *
	 * @since    1.0.0
	 * @access  public
	 */
	static public function uninstall() {
		delete_option( 'prro_show_repeat_order_on_order_list' );
		delete_option( 'prro_create_order_note' );
		delete_option( 'prro_reactivate_action' );
		delete_option( 'prro_cart_notice' );
		delete_option( 'prro_show_user_reorder_list' );
		foreach ( wc_get_order_statuses() as $key => $name ) {
			delete_option( 'prro_order_status_' . $key );
		}
		delete_option( 'prro_version' );
	}

	/**
	 *
	 * @since    1.1.0
	 * @access  public
	 */
	public function allow_data_order_id() {
		global $allowedposttags, $allowedtags;
		$newattribute = "data-order-id";

		$allowedposttags["a"][$newattribute] = true;
		$allowedtags["a"][$newattribute] = true;
	}

	/**
	 *
	 * @since    1.1.0
	 * @access  public
	 */
	public function current_screen() {
		if ( function_exists( 'wc_get_page_screen_id' ) ) {
			$cs = wc_get_page_screen_id('shop-order');
			if ( $cs === 'woocommerce_page_wc-orders' || $cs === 'shop_order' ) {
				add_action( 'admin_footer', array( $this, 'order_preview_template' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			}
		}
	}

	/**
	 *
	 * @since    1.1.0
	 * @access  public
	 */
	public function admin_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wc-orders', WC()->plugin_url() . '/assets/js/admin/wc-orders' . $suffix . '.js', array( 'jquery', 'wp-util', 'underscore', 'backbone', 'jquery-blockui' ), PRRO_VERSION );
		wp_localize_script(
			'wc-orders',
			'wc_orders_params',
			array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'preview_nonce' => wp_create_nonce( 'woocommerce-preview-order' ),
			)
		);
		wp_enqueue_script( 'wc-orders' );
		wp_register_style( 'prro_admin_menu_styles', plugins_url('style.css', __FILE__), array(), PRRO_VERSION );
		wp_enqueue_style( 'prro_admin_menu_styles' );
	}

	/**
	 * add_my_account_orders_column.
	 *
	 * add the reorder column in frontend my account orders list.
	 *
	 * @param $columns array Array with Columns.
	 * @return $new_columns array Array with Columns
	 * @since    1.3.0
	 * @access  public
	 */
	static public function add_my_account_orders_column( $columns ) {
		if ( get_option( 'prro_show_user_reorder_list' ) == 'yes' ) {
			$new_columns = array();
			foreach ( $columns as $key => $name ) {
				if ( 'order-actions' === $key ) {
					$new_columns['reorder'] = __( 'Reorder from', 'repeat-order-for-woocommerce' );
				}
				$new_columns[ $key ] = $name;
			}

			return $new_columns;
		} else {
			return $columns;
		}
	}

	/**
	 * my_orders_reorder_column
	 *
	 * Output a link to the original order when current order is a reorder
	 *
	 * @param $order object Orderobject with current order
	 * @since    1.3.0
	 * @access  public
	 */
	static public function my_orders_reorder_column( $order ) {
		if ( get_option( 'prro_show_user_reorder_list' ) == 'yes' ) {

			$reorder_from  = $order->get_meta( '_reorder_from_id', true );

			if ( $reorder_from > 0 ) {
				$reorder = wc_get_order( $reorder_from );
				?>
				<a href="<?php echo esc_url( $reorder->get_view_order_url() ); ?>">
					<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $reorder->get_order_number(); ?>
				</a>
				<?php
			}
		}
	}

	/**
	 * Order Preview Template
	 *
	 * copyied from WooCommerve  class-wc-admin-list-table-orders
	 *
	 * @since    1.1.0
	 * @access  public
	 */
	static public function order_preview_template() {
		?>
		<script type="text/template" id="tmpl-wc-modal-view-order">
			<div class="wc-backbone-modal wc-order-preview">
				<div class="wc-backbone-modal-content">
					<section class="wc-backbone-modal-main" role="main">
						<header class="wc-backbone-modal-header">
							<mark class="order-status status-{{ data.status }}"><span>{{ data.status_name }}</span></mark>
							<?php /* translators: %s: order ID */ ?>
							<h1><?php echo esc_html( sprintf( __( 'Order #%s', 'woocommerce' ), '{{ data.order_number }}' ) ); ?></h1>
							<button class="modal-close modal-close-link dashicons dashicons-no-alt">
								<span class="screen-reader-text"><?php esc_html_e( 'Close modal panel', 'woocommerce' ); ?></span>
							</button>
						</header>
						<article>
							<?php do_action( 'woocommerce_admin_order_preview_start' ); ?>

							<div class="wc-order-preview-addresses">
								<div class="wc-order-preview-address">
									<h2><?php esc_html_e( 'Billing details', 'woocommerce' ); ?></h2>
									{{{ data.formatted_billing_address }}}

									<# if ( data.data.billing.email ) { #>
									<strong><?php esc_html_e( 'Email', 'woocommerce' ); ?></strong>
									<a href="mailto:{{ data.data.billing.email }}">{{ data.data.billing.email }}</a>
									<# } #>

									<# if ( data.data.billing.phone ) { #>
									<strong><?php esc_html_e( 'Phone', 'woocommerce' ); ?></strong>
									<a href="tel:{{ data.data.billing.phone }}">{{ data.data.billing.phone }}</a>
									<# } #>

									<# if ( data.payment_via ) { #>
									<strong><?php esc_html_e( 'Payment via', 'woocommerce' ); ?></strong>
									{{{ data.payment_via }}}
									<# } #>
								</div>
								<# if ( data.needs_shipping ) { #>
								<div class="wc-order-preview-address">
									<h2><?php esc_html_e( 'Shipping details', 'woocommerce' ); ?></h2>
									<# if ( data.ship_to_billing ) { #>
									{{{ data.formatted_billing_address }}}
									<# } else { #>
									<a href="{{ data.shipping_address_map_url }}" target="_blank" rel="noopener noreferrer">{{{ data.formatted_shipping_address }}}</a>
									<# } #>

									<# if ( data.shipping_via ) { #>
									<strong><?php esc_html_e( 'Shipping method', 'woocommerce' ); ?></strong>
									{{ data.shipping_via }}
									<# } #>
								</div>
								<# } #>

								<# if ( data.data.customer_note ) { #>
								<div class="wc-order-preview-note">
									<strong><?php esc_html_e( 'Note', 'woocommerce' ); ?></strong>
									{{ data.data.customer_note }}
								</div>
								<# } #>
							</div>

							{{{ data.item_html }}}

							<?php do_action( 'woocommerce_admin_order_preview_end' ); ?>
						</article>
						<footer>
							<div class="inner">
								{{{ data.actions_html }}}

								<a class="button button-primary button-large" aria-label="<?php esc_attr_e( 'Edit this order', 'woocommerce' ); ?>" href="<?php echo esc_url( admin_url( 'post.php?action=edit' ) ); ?>&post={{ data.data.id }}"><?php esc_html_e( 'Edit', 'woocommerce' ); ?></a>
							</div>
						</footer>
					</section>
				</div>
			</div>
			<div class="wc-backbone-modal-backdrop modal-close"></div>
		</script>
		<?php
	}

	public function add_custom_order_status_actions_button( $actions, $order ) {
		$reorder_from  = $order->get_meta( '_reorder_from_id', true );

		if ( $reorder_from > 0 ) {
			$actions['pr_repeat'] = array(
				'url'       => wp_nonce_url( admin_url( 'post.php?post=' . $reorder_from . '&action=edit' ), '' ),
				'name'      => __( 'Show original order', 'repeat-order-for-woocommerce' ),
				'action'    => "view-original-order",
			);
		}
		return $actions;
	}

	/**
	 * Declares compatibility with WooCommerce Custom Order Tables.
	 * 
	 * @since 1.3.0
	 */
	public function declare_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}

$polyresRepeatOrderForWoocommerce = new RepeatOrderForWoocommerce();
