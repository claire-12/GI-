<div class="ph_fedex_registration_consent">

	<form method="post" action="" id="">

		<h2><?php echo __('Important Updates Regarding FedEx Account Integration with PluginHive', 'ph-fedex-woocommerce-shipping') ?></h2>


		<div class="ph_fedex_registration_consent_data">

			<p><?php echo __('In order to enhance customer safety, reduce fraud, and offer advanced API features, FedEx Web Services will be retired on May 15, 2024. The SOAP based FedEx Web Services is in development containment and has been replaced with FedEx RESTful APIs.', 'ph-fedex-woocommerce-shipping') ?></p>
			<p><?php echo __('PluginHive, as a FedEx Compatible Solution provider, is committed to ensuring a seamless transition for all merchants, ensuring the use of the latest FedEx APIs without any disruption to your business. This requires few significant changes to our business model:', 'ph-fedex-woocommerce-shipping') ?></p>

			<span></span>
			<p><?php echo __(' All FedEx API calls will now be routed through PluginHive.io. This ensures secure communication between customers and FedEx, with customer data, including FedEx account details, exclusively transmitted through PluginHive.io. Rest assured that PluginHive guarantees the safety of this data and it will not be shared with any other entity for any other purpose.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' To continue using FedEx services, it is essential to maintain an up-to-date plugin license. Once your current license expires, the plugin will no longer function. Therefore, customers must renew their plugin license in order to continue utilizing the shipping capabilities offered by the plugin.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' The existing licenses for the 5 site and 25 site plugins will no longer be valid. Instead, customers will need to purchase individual licenses for each website they wish to integrate with FedEx.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' Once customers opt for the new integration method using Registration, it will not be possible to revert back to the previous method that involved manually getting FedEx Meter Number.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' Please note that the plugin license serves as authorization and is strictly non-transferable. It is intended solely for the customer who initially acquired it and cannot be transferred or assigned to any other individual or entity.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' PluginHive will utilize FedEx Compatible Solutions to create FedEx Meter Number and migrate to FedEx RESTful APIs by February 1, 2024.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' The plugin license grants you the ability to process up to 15,000 orders per month. This allocation is ideal for SMALL and MEDIUM-sized eCommerce businesses. However, if you operate a LARGE business that handles more than 500 orders per day, please reach out to <a href="https://www.pluginhive.com/support/" target="_BLANK">PluginHive Support</a> to receive a personalized quote.', 'ph-fedex-woocommerce-shipping') ?></p>
			<span></span>
			<p><?php echo __(' After completing the registration process for your FedEx Account, all shipping rate requests and shipment creations will be performed in Production Mode. It is important to keep in mind that Test Credentials will not be available.', 'ph-fedex-woocommerce-shipping') ?></p>
			<hr />

			<p><?php echo __('We appreciate your cooperation in making these necessary updates.', 'ph-fedex-woocommerce-shipping') ?></p>
			<p><b><?php echo __('NOTE: If you have already received FedEx Meter Number, your current plugin will continue to function without any issues until May 15, 2024.', 'ph-fedex-woocommerce-shipping') ?></b></p>
			<p><?php echo __('If you have any further questions or require assistance with the transition, please don’t hesitate to contact <a href="https://www.pluginhive.com/support/" target="_BLANK">PluginHive Support</a>', 'ph-fedex-woocommerce-shipping') ?></p>

		</div>

		<?php

		if ($phLicenseActivationStatus != "Activated") {

			echo "<p style='color: red; text-align: left;'>" . __(' It seems that your plugin license has been deactivated, which means you will not be able to utilize the plugin\'s functionality. Please reactivate your license to regain access. If your license has expired, we kindly request you to renew it in order to proceed further.', 'ph-fedex-woocommerce-shipping') . "</p>";

			echo '<p style="text-align: left;"><a target="_BLANK" href="'.admin_url('/options-general.php?page=wc_am_client_fedex_woocommerce_shipping_dashboard') .'">'.__(' FedEx License Activation ', 'ph-fedex-woocommerce-shipping').'</a></p>';
		} else {
		?>
			<div class="ph_fedex_registration_agreement_check">

				<p class="ph_fedex_registration_agreement_statement">
					<input type="checkbox" id="ph_fedex_registration_agreement" name="ph_fedex_registration_agreement">
					<?php echo __(' By checking this box, you acknowledge and agree to the above-mentioned changes to FedEx account integration with PluginHive applications.', 'ph-fedex-woocommerce-shipping'); ?>
				</p>

			</div>

			<p class="submit">
				<button name="ph_fedex_registration_consent_form" id="ph_fedex_registration_consent_form" type="submit" value="Agree & Continue"><?php echo __('Agree & Continue', 'ph-fedex-woocommerce-shipping'); ?></button>
			</p>

		<?php } ?>

	</form>

</div>