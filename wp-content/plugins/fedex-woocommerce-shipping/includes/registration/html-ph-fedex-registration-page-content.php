<div class="wrap">

	<div class="ph_fedex_registration">

		<?php

		include_once('html-ph-fedex-registration-page-header.php');

		if (!class_exists('Ph_Fedex_Auth_Handler')) {

			include_once(plugin_dir_path(__DIR__) . "class-ph-fedex-auth-handler.php");
		}

		if (!class_exists('Ph_Fedex_Api_Invoker')) {
			include_once(plugin_dir_path(__DIR__) . "class-ph-fedex-api-invoker.php");
		}

		$phLicenseActivationStatus    = get_option('wc_am_client_fedex_woocommerce_shipping_activated');

		if (isset($_POST['ph_fedex_account_migration_form'])) {

			update_option('ph_fedex_account_migration_consent', true);
		}

		if (isset($_POST['ph_fedex_registration_agreement']) && $phLicenseActivationStatus == 'Activated') {

			update_option('ph_fedex_registration_consent', true);
		}

		$authProviderToken 			= null;
		$iframeURL 					= null;
		$iframeEndPoint 			= "https://carrier-registration-ui.pluginhive.io?token=";
		$phProductOrderAPIKey 		= get_option('ph_client_fedex_product_order_api_key');
		$phFedexMigrationConsent    = get_option('ph_fedex_account_migration_consent', false);
		$phFedexRegistrationConsent	= get_option('ph_fedex_registration_consent', false);

		$fedexSettings 				= get_option('woocommerce_' . WF_Fedex_ID . '_settings', []);

		$debugMode 					= isset($fedexSettings['debug']) && $fedexSettings['debug'] == 'yes' ? true : false;
		$phFedexMeterNumber			= isset($fedexSettings['meter_number']) && !empty($fedexSettings['meter_number']) ? $fedexSettings['meter_number'] : null;
		$phFedexClientCredentials 	= isset($fedexSettings['client_credentials']) && !empty($fedexSettings['client_credentials']) ? $fedexSettings['client_credentials'] : null;
		$phFedexClientLicenseHash 	= isset($fedexSettings['client_license_hash']) && !empty($fedexSettings['client_license_hash']) ? $fedexSettings['client_license_hash'] : null;

		$phPreferNewRegistration 	= false;
		
		if ( isset($_POST['ph_fedex_re_registration']) ) {

			$phPreferNewRegistration = true;
		}

		if (!empty($phProductOrderAPIKey) && $phLicenseActivationStatus == 'Activated') {

			$authProviderToken 	= 	Ph_Fedex_Auth_Handler::phGetAuthProviderToken('ph_iframe');

			if (!empty($authProviderToken)) {

				$iframeURL 			= $iframeEndPoint . $authProviderToken . "&licenseKey=" . $phProductOrderAPIKey . "&connectionMode=live" . "&carrier=FEDEX";

				if ( $phPreferNewRegistration ) {

					$iframeURL 					= $iframeURL. "&preferNew=true";
					$phFedexClientCredentials 	= '';
					$phFedexClientLicenseHash 	= '';
				}
			}
		}

		if( $debugMode ) {

			$phRegistrationPageDetails = [

				'ph_fedex_license_status'			=> $phLicenseActivationStatus,
				'product_order_api_key' 			=> $phProductOrderAPIKey,
				'ph_fedex_client_credentials'		=> $phFedexClientCredentials,
				'ph_fedex_client_license_hash'		=> $phFedexClientLicenseHash,
				'ph_fedex_iframe_url'				=> $iframeURL,
			];

			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport( "#---------------------- FedEx Registration Details ----------------------#", $debugMode);
			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport( print_r($phRegistrationPageDetails, true), $debugMode);
		}

		// When Customer Successfully Registered
		if (!empty($phFedexClientCredentials) && !empty($phFedexClientLicenseHash)) {

		?>
			<div class="phRegistrationSuceess">

				<p><?php echo __(" Congratulations on successfully registering your FedEx Account! Now, it's time to head over to the settings and make any necessary configurations. Get ready for a seamless shipping experience! ", "ph-fedex-woocommerce-shipping") ?></p>

				<?php

				// Successful Registration, But License is not active
				if ($phLicenseActivationStatus != 'Activated') {

				?>
					<p style="color: red"><?php echo __(" It appears that your plugin license is currently deactivated, which means you are unable to utilize the plugin's functionality. Please reactivate your license to regain access. If your license has expired, we kindly request you to renew it in order to continue using the plugin.", 'ph-fedex-woocommerce-shipping') ?></p>

					<a target="_BLANK" href="<?php echo admin_url('/options-general.php?page=wc_am_client_fedex_woocommerce_shipping_dashboard'); ?>"><?php echo __(' FedEx License Activation ', 'ph-fedex-woocommerce-shipping') ?></a>
					<span></span>
				<?php

				}
				?>
				<a target="_BLANK" href="<?php echo admin_url('/admin.php?page=wc-settings&tab=shipping&section=wf_fedex_woocommerce_shipping'); ?>"><?php echo __(' FedEx Plugin Settings ', 'ph-fedex-woocommerce-shipping') ?></a>
			</div>
			<?php if ( $phLicenseActivationStatus == 'Activated' ) { ?>
			<div class= "phReRegistration">
				<hr style="margin-bottom: 35px;">
				<form method="post" action="" id="">
					<p><b><?php _e("Want to change/update the FedEx Account?", "ph-fedex-woocommerce-shipping") ?></b></p>
					<button name="ph_fedex_re_registration" id="ph_fedex_re_registration" type="submit" value="yes"><?php echo __('Re-Register', 'ph-fedex-woocommerce-shipping'); ?></button>
				</form>
			</div>
			<?php } ?>
		<?php

			// Existing Customers who configured FedEx Details
		} elseif (!empty($phFedexMeterNumber) && !$phFedexMigrationConsent) {

		?>
			<div class="phFedexAccountMigration">

				<form method="post" action="" id="">

					<p><?php echo __(' Based on the FedEx Plugin Settings, it appears that you have successfully configured FedEx Details with Meter Number. As a result, your current plugin will continue to operate smoothly until May 15, 2024. Are you still interested in proceeding? ', 'ph-fedex-woocommerce-shipping') ?></p>
					
					<p class="submit" style="text-align:center">
						<button name="ph_fedex_account_migration_form" id="ph_fedex_account_migration_form" type="submit" value="Agree & Continue"><?php echo __('Agree & Continue', 'ph-fedex-woocommerce-shipping'); ?></button>
					</p>

				</form>
			</div>
		<?php

			// Existing Customers who has Active License but its not registered at PluginHive Servers
		} elseif (!empty($phFedexMeterNumber) && empty($phProductOrderAPIKey)) {

		?>
			<div class="phFedexAccountMigration">

				<p style="color: red"><?php echo __(' In order to proceed, please deactivate the Plugin License and then reactivate it. Once you have completed this step, kindly return to the Registration Page to continue.', 'ph-fedex-woocommerce-shipping') ?></p>

				<a target="_BLANK" href="<?php echo admin_url('/options-general.php?page=wc_am_client_fedex_woocommerce_shipping_dashboard'); ?>"><?php echo __(' FedEx License Activation ', 'ph-fedex-woocommerce-shipping') ?></a>

			</div>

		<?php
			// Registration Consent for all Customers
		} elseif (!$phFedexRegistrationConsent || $phLicenseActivationStatus != 'Activated' || empty($authProviderToken)) {

			include_once('html-ph-fedex-consent-and-validation.php');

			// Registration Page
		} elseif (!empty($iframeURL)) {

		?>

			<div style='width:100%; height: 100%;'>
				<iframe id="phFedExRegistration" style='width:100%; height: 100vh;' src='<?php echo $iframeURL; ?>'></iframe>
			</div>

		<?php

			
		} else {

		?>
			<div style='width:100%; height: 100%;'>
				<p><?php _e("Something went wrong. Please contact PluginHive Support for assistance", "ph-fedex-woocommerce-shipping") ?></p>
			</div>

		<?php

		}

		?>

	</div>