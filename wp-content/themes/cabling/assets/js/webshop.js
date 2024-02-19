(function ($) {
    add_phone_validate('#mobile-phone');
    add_phone_validate('#user_telephone');
    checkMyAccountNavigation();
    product_filter_init();

    $(document).on('change', 'input[name="existing-customer"]', function () {
        const numberField = $('.client-number-field');
        if ($(this).is(':checked')) {
            numberField.show();
        } else {
            numberField.hide();
        }
    })

    $(document).on('change', '.wpcf7-form-control', function () {
        if ($(this).val() === '') {
            $(this).closest('p').removeClass('has-focus');
        } else {
            $(this).closest('p').addClass('has-focus');
        }
    })

    $(document).on('change', '.form-group input', function () {
        if ($(this).val() === '') {
            $(this).closest('.form-group').removeClass('has-focus');
        } else {
            $(this).closest('.form-group').addClass('has-focus');
        }
    })

    $(document).find('.form-group input').each(function () {
        if ($(this).val() === '') {
            $(this).closest('.form-group').removeClass('has-focus');
        } else {
            $(this).closest('.form-group').addClass('has-focus');
        }
    })
    $(document).find('.contact-form-input input').each(function () {
        if ($(this).val() === '') {
            $(this).closest('p').removeClass('has-focus');
        } else {
            $(this).closest('p').addClass('has-focus');
        }
    })

    $(document).on('click', '.continue-step-2', function () {
        $(this).hide();
        $('.quote-step-2').show();
    })

    $(document).on('click', '.contact-form-input label', function () {
        $(this).closest('p').find('input').trigger('focus')
    })

    $(document).on('click', '.child-customer .edit-child', function () {
        const customer = $(this).data('action');

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_get_customer_ajax',
                data: customer,
                nonce: CABLING.nonce,
            },
            success: function (response) {
                if (response.success) {
                    const customerModal = $('#customer_modal');
                    customerModal.find('.modal-content').html(response.data);
                    const mobile_phone = add_phone_validate('#mobile_phone_edit');
                    const user_phone = add_phone_validate('#user_telephone_edit');

                    if (!mobile_phone.isValidNumber() || !user_phone.isValidNumber()) {
                        customerModal.find('.woo-notice').show().html('Please check your phone number.');
                        customerModal.find('button[type="submit"]').prop('disabled', true);
                    }

                    //customerModal.modal();
                    new bootstrap.Modal('#customer_modal').show();
                } else {
                    alert('Something went wrong');
                }
            },
            beforeSend: function () {
                showLoading();
            }
        })
            .done(function () {
                hideLoading();
            })
            .fail(function () {
                console.log("error");
            });
    })

    $(document).on('submit', 'form[name=update-customer-lv1]', function () {

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_update_customer_ajax',
                data: $(this).serialize(),
                nonce: CABLING.nonce,
            },
            success: function (response) {
                if (response.success) {
                } else {
                    alert('Something went wrong');
                }
                $("#customer_modal").modal('hide');
            },
            beforeSend: function () {
                showLoading();
            }
        })
            .done(function () {
                hideLoading();
            })
            .fail(function () {
                console.log("error");
            });
        return false;
    })

    $(document).on('click', '.resend-verify_email', function () {

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_resend_verify_email_ajax',
                data: $(this).attr('data-action'),
                email: $(this).attr('data-email'),
                nonce: CABLING.nonce,
            },
            success: function (response) {
                if (response.success) {
                    $('.woocommerce-notices-wrapper').html(response.data);
                    setTimeout(function () {
                        $('.woocommerce-notices-wrapper').empty();
                    }, 3000)
                } else {
                    alert('Something went wrong');
                }
            },
            beforeSend: function () {
                showLoading();
            }
        })
            .done(function () {
                hideLoading();
            })
            .fail(function () {
                console.log("error");
            });
        return false;
    })

    $(document).on('click', '#load-post-ajax', function () {
        blog_filter_ajax(true);
    })

    $('#blog-filter').on('change', 'select[name="order"],input[type=checkbox]', function () {
        blog_filter_ajax();
    })

    $('#blog-from-date').flatpickr({
        altFormat: "Y/m",
        dateFormat: "Y-m-d",
        altInput: true,
        "plugins": [new rangePlugin({input: "#blog-to-date"})],
        onClose: function (selectedDates, dateStr, instance) {
            if (selectedDates.length > 1) {
                blog_filter_ajax();
            }
        }
    });

    $('.date-picker').flatpickr({
        dateFormat: "m/d/y",
    });

    $(document).on('submit', 'form.woocommerce-EditAccountForm', function () {
        return confirm('Are you sure you want to update your account information?');
    })

    $(document).on('submit', '#infomation-form', function () {
        const password = $('input[name=password]');
        if (!checkPasswordStrength(password.val())) {
            $('.confirm-notice').html(`<ul class="woocommerce-error" role="alert">
                    <li>Your password must have at least: 8 characters long with characters, numbers and symbols</li>
            </ul>`);
            // Use animate to smoothly scroll to the target element
            $('html, body').animate({
                scrollTop: $('#registerStep').offset().top - 200
            }, 'slow');
            return false;
        }

    })

    $(document).on('submit', '#reset-account-password', function () {
        const password = $('#new-password');
        const confirm_password = $('#confirm-password');
        const btn_submit = $(this).find('button[type="submit"]');
        $('.woo-notice').remove();
        $('.form-group.invalid').removeClass('invalid');
        if (!checkPasswordStrength(password.val())) {
            password.closest('div').addClass('invalid');
            return false;
        }
        if (password.val() !== confirm_password.val()) {
            confirm_password.closest('div').addClass('invalid');
            return false;
        }

        btn_submit.prop('disabled', true);

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_reset_password_ajax',
                data: $(this).serialize(),
                nonce: CABLING.nonce,
            },
            success: function (response) {
                hideLoading();
                password.closest('form').prepend(response.data);
                if (response.success) {
                    window.location.reload();
                }

                btn_submit.prop('disabled', false);
            },
            beforeSend: function () {
                showLoading();
            }
        })
            .fail(function () {
                console.log("error");
            });

        return false;
    })

    $(document).on('click', 'a', function () {
        if ($(this).attr('href').includes('my-account') && $(this).attr('href').includes('customer-logout')) {
            return confirm('Are you sure you want to logout?');
        }
        /*if ($(this).closest('table').length) {
            const href = $(this).attr("href");
            if (href && href.toLowerCase().endsWith(".pdf")) {
                const title = $(this).text();
                $('#pdfModal').find('.modal-title').html(title);
                PDFObject.embed(href, "#pdfContent");
                new bootstrap.Modal('#pdfModal').show();

                return false;
            }
        }*/
        return true;
    })

    $(document).on('click', '.search-item', function (e) {
        $(e.delegateTarget).find('.header-search').toggle();
        $(e.delegateTarget).find('.search-ajax').hide();
    })
    $(document).on('click', '.toggle-product-sidebar', function () {
        const product_nav = $(this).closest('.product-services-nav');

        product_nav.find('.back-main-nav').show();
        product_nav.find('.product-cat-nav').show();
        product_nav.find('.product-nav').hide();
    })
    $(document).on('click', '.back-main-nav', function () {
        const product_nav = $(this).closest('.product-services-nav');

        product_nav.find('.back-main-nav').hide();
        product_nav.find('.product-cat-nav').hide();
        product_nav.find('.product-nav').show();
    });

    $('#filter-heading-product').on('click', '.item', function () {
        if ($(this).hasClass('clear-all')) {
            showLoading();
            window.location.href = CABLING.product_page;
        } else {
            const id = $(this).attr("data-action");
            $(document).find(`input[value='${id}']`).prop('checked', false).trigger('change');
        }
    });

    const filter_blog = $('.filter-heading-blog');

    filter_blog.on('click', '.item-cat', function () {
        const id = $(this).attr("data-action");
        $(document).find(`input[value=${id}]`).prop('checked', false).trigger('change');
    });

    filter_blog.on('click', '.clear-item a', function () {
        $('#panelsStayOpen-collapseOne').find('input').val('');
        $('.filter-blog').find(`input[type=checkbox]`).prop('checked', false);
        blog_filter_ajax();
    });

    filter_blog.on('click', '.item-date', function () {
        $('#panelsStayOpen-collapseOne').find('input').val('');
        blog_filter_ajax();
    });

    const filter_product_tag = $('.page-template-product-service');
    filter_product_tag.on('change', 'input[name=product_group], input[type=checkbox], input[type=radio]', function () {

        if ($(this).attr('name') === 'product_type' && 'Custom' === $(this).val()) {
            showLoading();
            $('.woocommerce-product-type-custom').show();
            $('.breadcrumbs-filter span').hide();
            $('.heading .total').hide();
            $('.woocommerce-no-products-found').hide();
            $('#filtered-category-container').empty();
            hideLoading();
        } else {
            $('.woocommerce-product-type-custom').hide();
            product_filter_ajax();
        }
        setActiveCheckbox.call(this);

        const targetElement = $('#filtered-category-container');

        // Use animate to smoothly scroll to the target element
        $('html, body').animate({
            scrollTop: targetElement.offset().top - 200
        }, 'slow');
    });

    filter_product_tag.on('click', '.cat-item', function () {
        const cat_id = $(this).attr('data-category');
        product_filter_ajax(cat_id);
    });

    $('.filter-blog').on('focusout', '.custom-size', function () {
        product_filter_ajax();
    });

    $('.product-variable-filter').on('change', 'input[type=checkbox]', function () {
        showLoading();
        $(this).closest('form').submit();
    });

    $(document).on('change', '#filter-blog-order', function () {
        $(this).closest('form').submit();
    });

    $(document).on('click', '.forums-discover a.nav-link', function (e) {
        e.preventDefault();
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        let filter = $(this).attr('data-action');
        if ($(this).hasClass('forums-category')) {
            filter = $(this).val();
        }
        $('input[name=filter]').val(filter);
        $(this).closest('form').submit();
    });
    $(document).on('change', '.forums-discover select', function () {
        $(this).closest('form').submit();
    });
    $(document).on('click', '.woocommerce-product-gallery__image a', function () {
        return false;
    });
    $(document).on('click', '.menu-item-1033162', function () {
        $(document).find('.cky-btn-revisit').trigger('click');
        return false;
    });
    $(document).on('submit', '#webservice-api-form', function () {
        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_get_api_ajax',
                data: $(this).serialize(),
                nonce: CABLING.nonce,
            },
            success: function (response) {
                hideLoading();
                //if (response.success) {
                $('#api-results').html(response.data.data)
                //}
            },
            beforeSend: function () {
                showLoading();
            }
        })
            .fail(function () {
                console.log("error");
            });
        return false;
    });
})(jQuery);

function setActiveCheckbox() {
    const $ = jQuery.noConflict();
    const formCheckbox = $(this).closest('.accordion-body').find('.form-check');
    let checkedTotal = 0;
    //formCheckbox.removeClass('in-active');
    formCheckbox.each(function () {
        if ($(this).find('input').is(':checked')) {
            $(this).removeClass('in-active');
            checkedTotal++;
        } else {
            $(this).addClass('in-active');
        }
    });

    if (checkedTotal === 0) {
        formCheckbox.removeClass('in-active');
    }
}

function showSingleTable(order) {
    const $ = jQuery.noConflict();
    const table = $('tr.single-' + order);
    const tableSingle = $('#table-order-detail');

    tableSingle.find('.table-heading span').html(order);
    tableSingle.find('tbody').empty();
    table.each(function () {
        tableSingle.find('tbody').append(`<tr>${$(this).html()}</tr>`);
    })
    tableSingle.removeClass('hidden');


    $('html, body').animate({
        scrollTop: tableSingle.offset().top - 200
    }, 'slow');
}

function checkMyAccountNavigation() {
    const $ = jQuery.noConflict();
    const myAccount = $('.woocommerce-MyAccount-navigation-link--edit-account');
    const myAccountDashboard = $('.woocommerce-MyAccount-navigation-link--dashboard');
    const myAccountAddress = $('.woocommerce-MyAccount-navigation-link--edit-address');
    const myAccountInfo = $('.woocommerce-MyAccount-navigation-link--setting-account');
    const myAccountManager = $('.woocommerce-MyAccount-navigation-link--users-management');
    const myAccountBacklog = $('.woocommerce-MyAccount-navigation-link--sales-backlog');
    const myAccountInventory = $('.woocommerce-MyAccount-navigation-link--inventory');
    const myAccountShipment = $('.woocommerce-MyAccount-navigation-link--shipment');
    if (myAccountDashboard.length) {
        if (myAccount.hasClass('is-active')
            || myAccountDashboard.hasClass('is-active')
            || myAccountAddress.hasClass('is-active')
            || myAccountInfo.hasClass('is-active')
            || myAccountManager.hasClass('is-active')
            || myAccountBacklog.hasClass('is-active')
            || myAccountInventory.hasClass('is-active')
            || myAccountShipment.hasClass('is-active')
        ) {
            myAccountAddress.fadeIn('fast');
            myAccountInfo.fadeIn('fast');
            myAccountManager.fadeIn('fast');
            myAccountBacklog.fadeIn('fast');
            myAccountInventory.fadeIn('fast');
            myAccountShipment.fadeIn('fast');
            myAccount.fadeIn('fast');
        }
    }
}

function checkPasswordStrength(password) {
    let strength = 0;

    // Check for minimum length
    if (password.length >= 8) {
        strength += 1;
    }

    /*// Check for at least one uppercase letter
    if (/[A-Z]/.test(password)) {
        strength += 1;
    }*/

    // Check for at least one special character
    if (/[\W_]/.test(password)) {
        strength += 1;
    }

    // Check for at least one number
    if (/\d/.test(password)) {
        strength += 1;
    }

    return strength === 3;
}

function blog_filter_ajax(load_more = false) {
    const $ = jQuery.noConflict();

    const filter_form = $('#blog-filter');
    const data = filter_form.serialize();
    const paged = $('input[name=paged]').val();
    $.ajax({
        url: CABLING.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'cabling_load_blog_ajax',
            data: data,
            paged: paged,
            load_more: load_more,
            nonce: CABLING.nonce,
        },
        success: function (response) {
            if (response.success) {
                $('.filter-params').html(response.data.filter_params);
                if (load_more) {
                    $('.post-wrapper').append(response.data.posts);
                } else {
                    $('.post-wrapper').html(response.data.posts);
                }

                $('.total-posts > span').html(response.data.found_posts);
                filter_form.find('input[name=paged]').val(response.data.paged);

                if (response.data.found_posts === 0) {
                    $('.number-posts').hide();
                } else {
                    $('.number-posts').show().html(response.data.number_posts);
                }

                if (response.data.last_paged) {
                    $('#load-post-ajax').hide();
                } else {
                    $('#load-post-ajax').show();
                }

                hideLoading();
            }
        },
        beforeSend: function () {
            showLoading();
        }
    })
        .fail(function () {
            console.log("error");
        });
}

function product_filter_init() {
    const $ = jQuery.noConflict();
    const form = $('.woo-sidebar').find('form');

    const preFilterInput = form.find('.pre_filter');
    if (preFilterInput.length) {
        preFilterInput.each(function () {
            const filterName = $(this).data('action');
            const filterItem = $(`.filter-${filterName}`);
            const filterValue = $(this).val();
            if (filterItem.length) {
                filterItem.find('.form-check-input').each(function () {
                    if ($(this).val() === filterValue) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).closest('.form-check').remove();
                    }
                })
            }
        });
        product_filter_ajax();
    }

}

function product_filter_ajax(cat_id) {
    const $ = jQuery.noConflict();
    const form = $('.woo-sidebar').find('form');

    add_filter_heading(form);

    const formData = form.serialize();
    $.ajax({
        url: CABLING.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'cabling_get_products_ajax',
            data: formData,
            category: cat_id,
            nonce: CABLING.nonce
        },
        success: function (response) {
            if (response.data.redirect) {
                window.location.href = response.data.redirect;
                return;
            }
            $('#filtered-category-container').html(response.data.results);
            if (response.data.total === 0) {
                $('.filter-blog').addClass('no-results');
                $('.breadcrumbs-filter span').hide();
                $('.heading .total').hide();
                $('.woocommerce-product-type-custom').show();

                if (response.data.isSizeFilter) {
                    $('.text-for-size').show();
                } else {
                    $('.text-for-size').hide();
                }
            } else {
                $('.filter-blog').removeClass('no-results');
                $('.heading .total').show().find('span').html(response.data.total);
                $('.breadcrumbs-filter span').html(response.data.category);
            }

            const filter_meta = response.data.filter_meta;
            $('.filter-blog').find('.filter-category').each(function () {
                let that = $(this);
                let meta_key = that.attr('data-meta-key');
                if (filter_meta !== null) {
                    if (meta_key) {
                        if (filter_meta.hasOwnProperty(meta_key) && filter_meta[meta_key].includes(that.attr('data-value'))) {
                            that.show();
                        } else {
                            that.hide();
                        }
                    }
                } else {
                    that.show();
                }
            });
            /*$('.filter-blog').find('.accordion-item').each(function () {
                let that = $(this);
                if (that.find('.filter-category').is(':visible')) {
                    that.show();
                } else {
                    that.hide();
                }
            })*/

            hideLoading();
        },
        beforeSend: function () {
            $('.woocommerce-no-products-found').hide();
            showLoading();
        }
    });
}

function add_filter_heading(form) {
    const $ = jQuery.noConflict();
    const filter = $('#filter-heading-product');
    filter.find('.item:not(.clear-all)').remove();
    form.find('input:checked').each(function () {
        const id = $(this).val();
        const name = $(this).attr('title');
        const label = $(this).closest('.accordion-item').find('.accordion-button').text();
        let type = $(this).attr('name');
        type = type.replace('[]', '');

        filter.append(`<div class="item item-${type} me-2" data-label="${label.trim()}" data-action="${id}">${name}<span class="clear ms-1"><i class="fa-thin fa-circle-xmark"></i></span></div>`);
    })

    if (filter.find('.item').length > 1) {
        filter.addClass('is-multiple');
    } else {
        filter.removeClass('is-multiple');
    }
}

function add_phone_validate(phone_element) {
    const phoneCodeElement = document.querySelector(phone_element);
    if (phoneCodeElement !== null) {
        const parentElement = phoneCodeElement.parentNode;
        const thisForm = phoneCodeElement.closest('form');
        const errorMsg = parentElement.querySelector('.input-error');

        let buttonElement;
        if (thisForm) {
            buttonElement = thisForm.querySelector('.btn-submit');
        }
        const resetPhoneError = () => {
            phoneCodeElement.classList.remove("error");
            errorMsg.innerHTML = "";
            errorMsg.classList.add("hidden");
            if (buttonElement) {
                buttonElement.disabled = false;
            }
        };
        const iti = window.intlTelInput(phoneCodeElement, {
            initialCountry: "us",
            separateDialCode: true,
            preferredCountries: ['us', 'ca'],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
                return "";
            },
        });

        phoneCodeElement.addEventListener("change", function () {
            resetPhoneError();
            if (phoneCodeElement.value.trim()) {
                if (iti.isValidNumber()) {
                    parentElement.querySelector('.phone_number').value = phoneCodeElement.value.trim();
                    if (buttonElement) {
                        buttonElement.disabled = false;
                    }
                } else {
                    //parentElement.querySelector('.phone_number').value = '';
                    phoneCodeElement.classList.add("error");
                    errorMsg.innerHTML = 'Invalid number';
                    errorMsg.classList.remove("hidden");
                    if (buttonElement) {
                        buttonElement.disabled = true;
                    }
                    console.log('Invalid number');
                }
            }
            jQuery(jQuery(this)).closest('.form-group').addClass('has-focus');
        });

        phoneCodeElement.addEventListener("countrychange", function () {
            const countryData = iti.getSelectedCountryData();
            if (countryData && countryData.dialCode) {
                parentElement.querySelector('.phone_code').value = countryData.dialCode;
            }
            phoneCodeElement.dispatchEvent(new Event("change"));
        });

        return iti;
    }
}

function hideLoading() {
    jQuery('.loading-wrap').fadeOut('fast');
    jQuery('body').removeClass('has-loading');
}

function showLoading() {
    jQuery('.loading-wrap').fadeIn();
    jQuery('body').addClass('has-loading');
}

function openModal(modalId) {
    const modalElement = document.getElementById(modalId);
    new bootstrap.Modal(modalElement).show();
}
