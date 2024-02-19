(function ($) {
    $(document).on('click', '.keep-informed-modal', function () {
		const modalElement = document.getElementById('keepInformedModal');
		$.ajax({
			url: CABLING.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'cabling_get_keep_informed_modal',
			},
			success: function(response) {
				if (response.success) {
					$('#keepInformedModal').find('.modal-content').html(response.data);

					add_phone_validate('#mobile-phone-informed');
					add_phone_validate('#sms-phone-informed');
				}
		    },
			beforeSend: function () {
				showLoading();
			}
		})
		.done(function () {
			hideLoading();
			//generateCaptchaElement('informed-recaptcha');
			const recaptcha_element = $('#informed-recaptcha');
			const sitekey = recaptcha_element.attr('data-sitekey');
			grecaptcha.render('informed-recaptcha', {
			  'sitekey' : sitekey,
			});
			new bootstrap.Modal(modalElement).show();
		});
    })

	$(document).on('click', '.show-product-quote,.woocommerce-MyAccount-navigation-link--request-a-quote', function (e) {
		e.preventDefault();
		const modalElement = document.getElementById('quoteProductModal');
		$.ajax({
			url: CABLING.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'cabling_get_product_quote_modal',
				data: $(this).attr('data-action')
			},
			success: function(response) {
				if (response.success) {
					$('#quoteProductModal').find('.modal-content').html(response.data);
					add_phone_validate('#mobile-phone');
					if ($('#login-recaptcha').length) {
						const sitekey = $('#login-recaptcha').attr('data-sitekey');
						grecaptcha.render('login-recaptcha', {
							'sitekey': sitekey,
						});
					}
				}
		    },
			beforeSend: function () {
				showLoading();
			}
		})
		.done(function () {
			hideLoading();
			new bootstrap.Modal(modalElement).show();
		});

		return false;
    })

	$(document).on('click', '.continue-as-a-guest', function (e) {
		e.preventDefault();
		const modalElement = $(this).closest('.quote-product-content');
		modalElement.find('.login-wrapper').hide();
		modalElement.find('.form-request-quote').show();
		modalElement.find('.login-wrapper-non').css('opacity', 1);
    })

    $(document).on('submit', '#keep-informed-form', function (e) {
        e.preventDefault();

        const form = $(this);
        form.find('.woo-notice').empty().removeClass('woocommerce-error woocommerce-message').hide();
        form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
			url: CABLING.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'cabling_save_keep_informed_data',
				data: form.serialize(),
			},
			success: function(response) {
				if (response.success) {
					form.find('.woo-notice').addClass('woocommerce-message');
				} else {
					form.find('.woo-notice').addClass('woocommerce-error');
				}
				form.find('.woo-notice').html(response.data).show();
				form.find('button[type="submit"]').prop('disabled', false);

				setTimeout(() => {
					form.find('.btn-closed').trigger('click');
				}, 2000)
		    },
			beforeSend: function () {
				showLoading();
			}
		})
		.done(function () {
			hideLoading();
		});

        return false;
    })
})(jQuery);
