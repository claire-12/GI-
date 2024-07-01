jQuery(document).ready(function () {

	var isAlreadyClickedMigrationConsent = false;
	var isAlreadyClickedRegistrationConsent = false;

	window.addEventListener("message", (event) => {

        // Extract the data from the message event
        const { data } = event;
		
		if (data && data.clientId) {

			let key_data = {
				action: 'ph_fedex_update_registration_data',
				clientId: data.clientId,
				clientSecret: data.secret,
				licenseHash: data.externalClientId,
			}
	
			jQuery.post(ph_fedex_registration_js.ajaxurl, key_data, function (result, status) {
				
				let response2 = JSON.parse(result);

				console.log('response2: ', response2);

			});
		}
		
    });

	ph_disable_confirmation_button();

	jQuery("#ph_fedex_account_migration_form").on("click", function () {

		if (isAlreadyClickedMigrationConsent == false) {

			isAlreadyClickedMigrationConsent = true;

			return true;
		}

		if (isAlreadyClickedMigrationConsent) {
			
			jQuery(this).attr('disabled', 'disabled').css({ "cursor": "not-allowed" });
		}
	});

	jQuery("#ph_fedex_registration_consent_form").on("click", function () {

		if (isAlreadyClickedRegistrationConsent == false) {

			isAlreadyClickedRegistrationConsent = true;

			return true;
		}

		if (isAlreadyClickedRegistrationConsent) {
			
			jQuery(this).attr('disabled', 'disabled').css({ "cursor": "not-allowed" });
		}
	});

	jQuery("#ph_fedex_registration_agreement").on("click", function () {

		ph_disable_confirmation_button();
	});


	jQuery("#ph_fedex_re_registration").on("click", function (e) {

		confirmation = prompt('Please enter "YES" to confirm:');

		if ( confirmation === null || confirmation != 'YES' ) {

			if ( confirmation != null ) {

				alert("Please enter a correct value");
			}

			e.preventDefault();
		}
	});

});

function ph_disable_confirmation_button() {

	if (jQuery('#ph_fedex_registration_agreement').is(':checked')) {

		jQuery("#ph_fedex_registration_consent_form").removeAttr("disabled").css({ "cursor": "pointer" });
	} else {

		jQuery("#ph_fedex_registration_consent_form").attr('disabled', 'disabled').css({ "cursor": "not-allowed" });
	}


}