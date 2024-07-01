<?php

if (!class_exists('PH_FedEx_Registration_Menu')) {

	class PH_FedEx_Registration_Menu
	{
		public function __construct()
		{
			add_action('admin_menu', array($this, 'PH_FedEx_Registration_Menu'));
		}

		/**
		 * Admin Menu
		 */
		public function PH_FedEx_Registration_Menu()
		{

			// Add Menu Page for Settings
			add_menu_page(
				__('FedEx Registration', 'ph-fedex-woocommerce-shipping'),
				__('FedEx Registration', 'ph-fedex-woocommerce-shipping'),
				'manage_options',
				'ph_fedex_registration',
				array($this, 'ph_fedex_registration_page'),
				'dashicons-clipboard',
				56
			);

			add_submenu_page(
				'ph_fedex_registration',
				__('Registration', 'ph-fedex-woocommerce-shipping'),
				__('Registration', 'ph-fedex-woocommerce-shipping'),
				'manage_woocommerce',
				'ph_fedex_registration',
				array($this, 'ph_fedex_registration_page')
			);

			add_submenu_page(
				'ph_fedex_registration',
				__('License Activation', 'ph-fedex-woocommerce-shipping'),
				__('License Activation', 'ph-fedex-woocommerce-shipping'),
				'manage_woocommerce',
				'ph_fedex_license_activation',
				array($this, 'ph_fedex_license_activation_page')
			);


			add_submenu_page(
				'ph_fedex_registration',
				__('Settings', 'ph-fedex-woocommerce-shipping'),
				__('Settings', 'ph-fedex-woocommerce-shipping'),
				'manage_woocommerce',
				'ph_fedex_plugin_settings',
				array($this, 'ph_fedex_plugin_setting_page')
			);
		}

		/**
		 * FedEx Registration
		 */
		public function ph_fedex_registration_page()
		{
			include_once('html-ph-fedex-registration-page-content.php');
		}

		/**
		 * FedEx License Activation
		 */
		public function ph_fedex_license_activation_page()
		{

			if (!headers_sent() && is_admin()) {

				wp_redirect(admin_url('/options-general.php?page=wc_am_client_fedex_woocommerce_shipping_dashboard'));
				exit;
			}
		}

		/**
		 * FedEx Plugin Settings
		 */
		public function ph_fedex_plugin_setting_page()
		{

			if (!headers_sent() && is_admin()) {

				wp_redirect(admin_url('/admin.php?page=wc-settings&tab=shipping&section=wf_fedex_woocommerce_shipping'));
				exit;
			}
		}
	}

	new PH_FedEx_Registration_Menu();
}
