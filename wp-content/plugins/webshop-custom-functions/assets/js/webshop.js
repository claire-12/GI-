(function ($) {

    $(document).on('click', '.keep-informed-modal', function () {
        showKeepInformedModal();
    })
    $(document).on('click', 'a', function () {
        if ($(this).attr('href') === '#keep-informed-modal') {
            showKeepInformedModal();
            return false;
        }
        return true;
    })

    $(document).on('click', '.show-product-quote,.woocommerce-MyAccount-navigation-link--request-a-quote', function (e) {
        e.preventDefault();
        let filter_params = []
        if ($(this).closest('body').hasClass('page-template-product-service')) {
            $('#filter-heading-product').find('.item').each(function () {
                if ($(this).hasClass('clear-all')) {
                    return;
                }
                const label = $(this).attr('data-label');
                const value = $(this).text();

                filter_params.push([label, value]);
            })
        }
        const modalElement = document.getElementById('quoteProductModal');
        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_get_product_quote_modal',
                data: $(this).attr('data-action'),
                filter_params: filter_params
            },
            success: function (response) {
                if (response.success) {
                    $('#quoteProductModal').find('.modal-content').html(response.data.content);
                    add_phone_validate('#mobile-phone');
                    if ($('#login-recaptcha').length) {
                        const sitekey = $('#login-recaptcha').attr('data-sitekey');
                        grecaptcha.render('login-recaptcha', {
                            'sitekey': sitekey,
                        });
                    }
                    $('.date-picker').flatpickr({
                        dateFormat: "m/d/Y",
                    });
                    $('#quoteProductModal').find('.form-group input').each(function () {
                        if ($(this).val() === '') {
                            $(this).closest('.form-group').removeClass('has-focus');
                        } else {
                            $(this).closest('.form-group').addClass('has-focus');
                        }
                    });
                    if ($('select[name="product-of-interest"]').val() === 'O-Ring') {
                        $('select[name="product-of-interest"]').trigger('change');
                        $('#dimension-id').val(response.data.arg.inches_id).closest('.form-group').addClass('has-focus');
                        $('#dimension-od').val(response.data.arg.inches_od).closest('.form-group').addClass('has-focus');
                        $('#dimension-width').val(response.data.arg.inches_width).closest('.form-group').addClass('has-focus');
                        $('select[name="dimension_oring[type]"]').val('INH');
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

    $(document).on('change', '#product-of-interest', function (e) {
        if ($('.dimension-not-oring').length && $('.dimension-oring').length) {
            if ($(this).val() === 'O-Ring') {
                $('.dimension-oring').show();
                $('.dimension-not-oring').hide();
            } else {
                $('.dimension-oring').hide();
                $('.dimension-not-oring').show();
            }
        }
    })
    $(document).on('change', '#billing_country', function (e) {
        const stateSelect = $('#billing_state');
        if (stateSelect.length) {
            const country = $(this).val();
            $.ajax({
                url: CABLING.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'cabling_get_state_of_country',
                    data: country,
                },
                success: function (response) {
                    if (response.success) {
                        stateSelect.html(response.data)
                    }
                },
                beforeSend: function () {
                    showLoading();
                }
            })
                .done(function () {
                    hideLoading();
                });
        }
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
            success: function (response) {
                if (response.success) {
                    form.html('<div class="woocommerce-message woo-notice"></div>');
                } else {
                    form.find('.woo-notice').addClass('woocommerce-error');
                }
                form.find('.woo-notice').html(response.data).show();
                form.find('button[type="submit"]').prop('disabled', false);

                setTimeout(() => {
                    window.location.reload();
                }, 5000)
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
    $(document).on('submit', '#form-request-quote', function () {
        const form = $(this);
        const phoneValidate = $('#mobile-phone-validate');

        const formData = new FormData(this);
        formData.append('action', 'cabling_request_quote');
        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                if (response.success) {
                    form.html(response.data);
                    setTimeout(function () {
                        window.location.reload();
                    }, 5000);
                } else {
                    form.prepend(response.data);
                    passwordElement.val('');
                }
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

function showKeepInformedModal() {
    $ = jQuery.noConflict();
    const modalElement = document.getElementById('keepInformedModal');
    $.ajax({
        url: CABLING.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'cabling_get_keep_informed_modal',
        },
        success: function (response) {
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
                'sitekey': sitekey,
            });
            new bootstrap.Modal(modalElement).show();
        });
}
