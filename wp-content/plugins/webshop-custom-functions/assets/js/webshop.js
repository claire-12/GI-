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
                    const captcha_element = $(document).find('.quote-recaptcha');

                    if (captcha_element.length) {
                        captcha_element.each(function () {
                            const that = $(this);
                            const sitekey = CABLING.recaptcha_key;
                            grecaptcha.render(that.attr('id'), {
                                'sitekey': sitekey,
                            });
                        })
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
            })
            .error(function () {
                hideLoading();
                alert('Something went wrong');
            });

        return false;
    })

    $(document).on('click', '.continue-step-2', function () {
        $(this).hide();
        $('.quote-step-2').show();
    })

    $(document).on('click', '.continue-as-a-guest', function (e) {

        e.preventDefault();
        const register_block = $('.register-block');
        const button = $(this);
        const capcha_form = $('#quote_form-recapcha');
        const recaptcha = capcha_form.find('[name="g-recaptcha-response"]').val();

        register_block.find('.woo-notice').remove();

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_confirm_recaptcha_ajax',
                recaptcha: recaptcha,
            },
            success: function (response) {
                if (response.success) {
                    const modalElement = register_block.closest('.quote-product-content');

                    modalElement.find('.login-wrapper').hide();
                    modalElement.find('.step2-quote').hide();
                    modalElement.find('.form-request-quote').show();
                    modalElement.find('.login-wrapper-non').css('opacity', 1);
                } else {
                    button.before(response.data);
                }
                hideLoading();
            },
            beforeSend: function () {
                showLoading();
            }
        })
            .error(function () {
                hideLoading();
                alert('Something went wrong');
            });
    });


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
        const stateSelect = $(this).closest('form').find('#billing_state');
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
                        stateSelect.html(response.data);
                        console.log(stateSelect, response.data)
                    }
                },
                beforeSend: function () {
                    showLoading();
                }
            })
                .done(function () {
                    hideLoading();
                })
                .error(function () {
                    hideLoading();
                    alert('Something went wrong');
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
                brandId: CABLING.crm,
            },
            success: function (response) {
                gtag('event', 'Lead_KMI');

                if (response.success) {
                    form.html('<div class="woocommerce-message woo-notice"></div>');
                } else {
                    form.find('.woo-notice').addClass('woocommerce-error');
                }
                form.find('.woo-notice').html(response.data).show();
                form.find('button[type="submit"]').prop('disabled', false);

                setTimeout(() => {
                    window.location.reload();
                }, 10000)
            },
            beforeSend: function () {
                showLoading();
            }
        })
        .done(function () {
            hideLoading();
        })
        .error(function () {
            form.html(`<div class="alert alert-danger d-flex align-items-center" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>
                <div>
                    There was an error while processing the request. Please try again later!
                </div>
            </div>`);
            hideLoading();
            });

        return false;
    })
    $(document).on('submit', '#form-request-quote', function () {
        const form = $(this);
        const phoneValidate = $('#mobile-phone-validate');

        const formData = new FormData(this);
        formData.append('action', 'cabling_request_quote');
        formData.append('brandId', CABLING.crm);
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
                    }, 10000);
                } else {
                    form.prepend(response.data);
                }
                gtag('event', 'Lead_RFQ');
            },
            beforeSend: function () {
                showLoading();
            }
        })
        .done(function () {
            hideLoading();
        })
        .error(function () {
            form.html(`<div class="alert alert-danger d-flex align-items-center" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>
                <div>
                    There was an error while processing the request. Please try again later!
                </div>
            </div>`);
            hideLoading();
        });
        return false;
    })
    $(document).on('submit', '#form-change-address', function () {
        const form = $(this);

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gi_update_shipping_address',
                data: form.serialize(),
            },
            success: function (response) {
                if (response.success) {
                    form.html(response.data);
                    setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                } else {
                    form.prepend(response.data);
                }
            },
            beforeSend: function () {
                showLoading();

                form.find('.alert').remove();
            }
        })
            .done(function () {
                hideLoading();
            })
            .error(function () {
                form.html(`<div class="alert alert-danger d-flex align-items-center" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <div>
                        There was an error while processing the request. Please try again later!
                    </div>
                </div>`);
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
    })
    .error(function () {
        hideLoading();
        alert('Something went wrong');
        })
}

function gi_edit_selected_address(e, address_type, address_key) {
    $ = jQuery.noConflict();

    showLoading();
    const address_item = $(e).closest('.address-item');
    const modalElement = $('#addAddressModal');
    const address = JSON.parse(address_item.attr('data-address'));

    $.ajax({
        url: CABLING.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'gi_get_modal_address_content',
            address_type: address_type,
            address_key: address_key,
            address_fields: address,
        },
        success: function (response) {
            if (response.success) {
                modalElement.find('.form-change-address').html(response.data);
                $("#shipping_country").select2({dropdownParent: $('#addAddressModal')});
                $("#shipping_state").select2({dropdownParent: $('#addAddressModal')});
                new bootstrap.Modal(modalElement).show();
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
