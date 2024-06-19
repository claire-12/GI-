<?php
/*
	Plugin Name: WooCommerce FedEx Shipping Plugin with Print Label
	Plugin URI: https://www.pluginhive.com/product/woocommerce-fedex-shipping-plugin-with-print-label/
	Description: This plugin helps you completely automate FedEx shipping. It displays live shipping rates on WooCommerce cart page, helps you pay postage & print labels from within WooCommerce, and track your shipments.
	Version: 7.0.6
	Author: PluginHive
	Author URI: http://pluginhive.com/about/
	WC requires at least: 3.0.0
	WC tested up to: 8.7.0
	Requires Plugins: woocommerce
	Text Domain: ph-fedex-woocommerce-shipping
*/

if ( ! defined( 'WF_Fedex_ID' ) ) {
	define( 'WF_Fedex_ID', 'wf_fedex_woocommerce_shipping' );
}

if ( ! defined( 'WF_FEDEX_ADV_DEBUG_MODE' ) ) {
	define( 'WF_FEDEX_ADV_DEBUG_MODE', 'on' ); // Turn 'off' to disable advanced debug mode.
}

// Define PH_FEDEX_PLUGIN_VERSION
if ( ! defined( 'PH_FEDEX_PLUGIN_VERSION' ) ) {
	define( 'PH_FEDEX_PLUGIN_VERSION', '7.0.6' );
}

// Define PH_FEDEX_DEBUG_LOG_FILE_NAME
if (!defined( 'PH_FEDEX_DEBUG_LOG_FILE_NAME' )) {
	define( 'PH_FEDEX_DEBUG_LOG_FILE_NAME', 'PluginHive-FedEx-Error-Debug-Log' );
}

// Include API Manager
if ( ! class_exists( 'PH_FedEx_API_Manager' ) ) {

	include_once 'ph-api-manager/ph_api_manager_fedex.php';
}

if ( ! defined( 'PH_FEDEX_PROXY_API_BASE_URL' ) ) {
	define( 'PH_FEDEX_PROXY_API_BASE_URL', 'https://ship-rate-track-proxy.pluginhive.io' );
}

if ( class_exists( 'PH_FedEx_API_Manager' ) ) {

	$ph_fedex_api_obj = new PH_FedEx_API_Manager( __FILE__, '', PH_FEDEX_PLUGIN_VERSION, 'plugin', 'https://www.pluginhive.com/', 'FedEx', 'ph-fedex-woocommerce-shipping' );
}

if ( ! defined( 'PH_FEDEX_LABEL_MIGRATION_OPTION' ) ) {
	define( 'PH_FEDEX_LABEL_MIGRATION_OPTION', 'ph_fedex_post_table_label_migrated' );
}

// WooCommerce HPOS Compatibility declaration
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {

			$ph_fedex_postmeta_label_migrated = get_option( PH_FEDEX_LABEL_MIGRATION_OPTION, false );

			if ( $ph_fedex_postmeta_label_migrated ) {

				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			} else {

				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, false );
			}
		}
	}
);

/**
 * Plugin activation check
 */
function wf_fedex_pre_activation_check() {
	// check if basic version is there
	if ( is_plugin_active( 'fedex-woocommerce-shipping-method/fedex-woocommerce-shipping.php' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __( 'Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete FedEx(Basic) Woocommerce Extension and then try again', 'ph-fedex-woocommerce-shipping' ), '', array( 'back_link' => 1 ) );
	}
	set_transient( 'wf_fedex_welcome_screen_activation_redirect', true, 30 );
}

register_activation_hook( __FILE__, 'wf_fedex_pre_activation_check' );

/**
 * Common Class.
 */
if ( ! class_exists( 'Ph_Fedex_Woocommerce_Shipping_Common' ) ) {
	require_once 'class-ph-fedex-woocommerce-shipping-common.php';
}

/**
 * Check if WooCommerce is active
 */
$xa_active_plugins = Ph_Fedex_Woocommerce_Shipping_Common::get_active_plugins();
if ( in_array( 'woocommerce/woocommerce.php', $xa_active_plugins ) ) {

	if ( ! function_exists( 'wf_get_settings_url' ) ) {
		function wf_get_settings_url() {
			return version_compare( WC()->version, '2.1', '>=' ) ? 'wc-settings' : 'woocommerce_settings';
		}
	}

	if ( ! function_exists( 'wf_plugin_override' ) ) {
		add_action( 'plugins_loaded', 'wf_plugin_override' );
		function wf_plugin_override() {
			if ( ! function_exists( 'WC' ) ) {
				function WC() {
					return $GLOBALS['woocommerce'];
				}
			}
		}
	}
	if ( ! function_exists( 'wf_get_shipping_countries' ) ) {
		function wf_get_shipping_countries() {
			$woocommerce        = WC();
			$shipping_countries = method_exists( $woocommerce->countries, 'get_shipping_countries' )
					? $woocommerce->countries->get_shipping_countries()
					: $woocommerce->countries->countries;
			return $shipping_countries;
		}
	}

	include 'includes/wf-automatic-label-generation.php';
	if ( ! class_exists( 'wf_fedEx_wooCommerce_shipping_setup' ) ) {
		class wf_fedEx_wooCommerce_shipping_setup {

			public $fedex_settings;

			public $active_plugins;

			public function __construct() {

				$this->active_plugins = Ph_Fedex_Woocommerce_Shipping_Common::get_active_plugins();     // List of active plugins
				$this->fedex_settings = get_option( 'woocommerce_' . WF_Fedex_ID . '_settings', array() );
				// Define Fedex Settings
				global $xa_fedex_settings;
				$xa_fedex_settings = $this->fedex_settings;

				add_action( 'init', array( $this, 'init' ) );

				$this->wf_init();
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
				add_action( 'woocommerce_shipping_init', array( $this, 'wf_fedEx_wooCommerce_shipping_init' ) );
				add_filter( 'woocommerce_shipping_methods', array( $this, 'wf_fedEx_wooCommerce_shipping_methods' ) );
				add_filter( 'admin_enqueue_scripts', array( $this, 'wf_fedex_scripts' ) );

				if ( isset( $this->fedex_settings['freight_enabled'] ) && 'yes' === $this->fedex_settings['freight_enabled'] ) {
					// Make the city field show in the calculator (for freight)
					add_filter( 'woocommerce_shipping_calculator_enable_city', '__return_true' );

					// Add freight class option for shipping classes (for freight)
					if ( is_admin() ) {
						include 'includes/class-wf-fedex-freight-mapping.php';
					}
				}
				// For FedEx Hold at Location
				if ( isset( $this->fedex_settings['hold_at_location'] ) && $this->fedex_settings['hold_at_location'] === 'yes' ) {
					if ( ! class_exists( 'Ph_Fedex_Woocommerce_Location_Finder' ) ) {
						require_once 'includes/class-ph-fedex-woocommerce-location-finder.php';
						$fedex_hold_at = new Ph_Fedex_Woocommerce_Location_Finder( $this->fedex_settings );
						$fedex_hold_at->init();

					}
					add_filter( 'wp_enqueue_scripts', array( $this, 'ph_fedex_checkout_scripts_for_hold_at_location' ) );
				}
			}
			public function init() {

				// WooCommerce HPO's Handler
				if ( ! class_exists( 'PH_WC_Fedex_Storage_Handler' ) ) {
					include_once 'class-ph-wc-fedex-storage-handler.php';
				}

				if ( ! class_exists( 'PH_Fedex_WP_Post_Table_Label_Migration' ) ) {
					include_once 'migration/ph-fedex-post-table-migration.php';
				}

				if ( ! class_exists( 'PH_FedEx_Help_and_Support' ) ) {
					include_once 'includes/class-ph-fedex-help-and-support.php';
				}

				if ( ! class_exists( 'wf_order' ) ) {
					include_once 'includes/class-wf-legacy.php';
				}

				// FedEx Registration
				include_once 'includes/registration/class-ph-fedex-registration-menu.php';
				include_once 'includes/registration/class-ph-fedex-registration-admin-ajax.php';
			}

			public function wf_init() {

				// To Support Third Party plugins
				$this->third_party_plugin_support();

				include_once 'includes/plugin-filters/ph-fedex-license-hash-finder.php';
				include_once 'includes/plugin-filters/ph-wc-fedex-internal-filters.php';
				include_once 'includes/class-wf-admin-notice.php';
				include_once 'includes/class-wf-fedex-woocommerce-shipping-admin.php';
				include_once 'includes/class-wf-admin-options.php';
				include_once 'includes/class-wf-tracking-admin.php';
				include_once 'includes/class-wf-request.php';
				include_once 'includes/class-xa-my-account-order-return.php';

				if ( is_admin() ) {
					include_once 'includes/class-wf-fedex-pickup-admin.php';

					include_once 'includes/class-xa-fedex-image-upload.php';
					include_once 'includes/class-ph-fedex-shipment-tracking.php';

				}
				// Localisation
				load_plugin_textdomain( 'ph-fedex-woocommerce-shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/' );
			}

			/**
			 * It will decide that which third party plugin support file has to be included depending on active plugins.
			 */
			public function third_party_plugin_support() {

				// Woocommerce Bundle Product Plugin support
				if ( in_array( 'woocommerce-product-bundles/woocommerce-product-bundles.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/wf-fedex-woocommerce-product-bundle-support.php';
				}

				// Include file to support for WooCommerce Measurement Price Calculator Plugins
				if ( in_array( 'woocommerce-measurement-price-calculator/woocommerce-measurement-price-calculator.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/wf-fedex-woocommerce-measurement-price-calculator.php';
				}

				// Support for Mixed-and-matched product plugin by woocommerce
				if ( in_array( 'woocommerce-mix-and-match-products/woocommerce-mix-and-match-products.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/ph-fedex-woocommerce-mixed-and-match-product-support.php';
				}
				// For YITH multiple shipping address
				if ( in_array( 'yith-multiple-shipping-addresses-for-woocommerce/init.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/ph-fedex-yith-multiple-shipping-addresses-for-woocommerce.php';
				}
				// For woocommerce currency switcher
				if ( in_array( 'woocommerce-multicurrency/woocommerce-multicurrency.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/ph-fedex-woocommerce-currency-switcher.php';
				}
				// For WooCommerce Ship to Multiple Addresses
				if ( in_array( 'woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-address.php', $this->active_plugins ) || in_array( 'woocommerce-shipping-multiple-addresses/woocommerce-shipping-multiple-addresses.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/ph-fedex-woocommerce-shipping-multiple-address.php';
				}
				// WOOCS - WooCommerce Currency Switcher Plugin support
				if ( in_array( 'woocommerce-currency-switcher/index.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/ph-fedex-woocs-currency-switcher.php';
				}
				// For Woocommerce Composite Product plugin
				if ( in_array( 'woocommerce-composite-products/woocommerce-composite-products.php', $this->active_plugins ) ) {
					require_once 'includes/third-party-plugin-support/ph-fedex-woocommerce-composite-product-support.php';
				}
			}

			public function wf_fedex_scripts() {

				if ( is_admin() && ! did_action( 'wp_enqueue_media' ) && isset( $_GET['section'] ) && $_GET['section'] == 'wf_fedex_woocommerce_shipping' ) {
					wp_enqueue_media();
				}

				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'wf-fedex-script', plugins_url( '/resources/js/wf_fedex.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_style( 'ph-fedex-common-style', plugins_url( '/resources/css/wf_common_style.css', __FILE__ ) );
				wp_enqueue_style( 'wf-fedex-style', plugins_url( '/resources/css/wf_fedex_style.css', __FILE__ ) );

				if ( is_admin() && isset( $_GET['page'] ) && ( $_GET['page'] == 'ph_fedex_registration' ) ) {
					wp_enqueue_script( 'ph_fedex_registration', plugins_url( '/resources/js/ph_fedex_registration.js', __FILE__ ), array( 'jquery' ) );
					wp_localize_script( 'ph_fedex_registration', 'ph_fedex_registration_js', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
				}
			}

			public function plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=' . wf_get_settings_url() . '&tab=shipping&section=wf_fedex_woocommerce_shipping' ) . '">' . __( 'Settings', 'ph-fedex-woocommerce-shipping' ) . '</a>',
					'<a href="https://www.pluginhive.com/knowledge-base/category/woocommerce-fedex-shipping-plugin-with-print-label/" target="_blank">' . __( 'Documentation', 'ph-fedex-woocommerce-shipping' ) . '</a>',
					'<a href="https://www.pluginhive.com/support/" target="_blank">' . __( 'Support', 'ph-fedex-woocommerce-shipping' ) . '</a>',
				// '<a href="'.admin_url('index.php?page=Fedex-Welcome').'" style="color:green;" target="_blank">' . __('Get Started', 'ph-fedex-woocommerce-shipping') . '</a>'
				);
				return array_merge( $plugin_links, $links );
			}

			public function wf_fedEx_wooCommerce_shipping_init() {
				include_once 'includes/class-wf-fedex-woocommerce-shipping.php';
				$shipping_obj = new wf_fedex_woocommerce_shipping_method();
				// This filer kept outside of 'wf_fedex_woocommerce_shipping_method'. Because, the scope of filter should be avail outside of calculate_shipping() method.
				add_filter( 'woocommerce_cart_shipping_method_full_label', array( $shipping_obj, 'wf_add_delivery_time' ), 10, 2 );
			}


			public function wf_fedEx_wooCommerce_shipping_methods( $methods ) {
				$methods[] = 'wf_fedex_woocommerce_shipping_method';
				return $methods;
			}
			public function ph_fedex_checkout_scripts_for_hold_at_location() {
				if ( is_checkout() ) {
					wp_enqueue_script( 'ph-fedex-checkout-script', plugins_url( '/resources/js/ph_fedex_checkout.js', __FILE__ ), array( 'jquery' ) );

					$fedex_method_available_countries = array(
						'availability' => $this->fedex_settings['availability'],
						'countries'    => $this->fedex_settings['countries'],
					);

					wp_localize_script( 'ph-fedex-checkout-script', 'fedex_method_available_countries', $fedex_method_available_countries );
				}
			}
		}
		new wf_fedEx_wooCommerce_shipping_setup();
	}
}
