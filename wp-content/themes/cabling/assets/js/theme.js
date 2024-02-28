(function ($) {

    const elem = document.querySelector('.main-slider');
    if (elem) {
        new Flickity(elem, {
            // options
            cellAlign: 'left',
            contain: true,
            autoPlay: 5000,
            pageDots: false
        });
    }

    const elemListSlide = document.querySelector('.news-list-slider');
    if (elemListSlide) {
        new Flickity(elemListSlide, {
            // options
            cellAlign: 'left',
            contain: false,
            pageDots: false,
            wrapAround: true,
        });
    }

    let ajaxSearch = null;

    $(document).on('keyup', '.search-field', function () {
        cabling_search_ajax(ajaxSearch);
    })

    $(document).on('click', '.search-filter-ajax', function () {
        cabling_search_ajax(ajaxSearch);
    })

    $(document).on('click', '.filter-pagination', function (e) {
        e.preventDefault();

        const paged = $(this).attr('data-action');
        cabling_search_ajax(ajaxSearch, paged);
    })

    $('.header-search').on('click', '.close', function (e) {
        e.preventDefault();
        $('.search-ajax').hide();
        $('.header-search .close').hide();
    });

    $(document).on('click', '.close-search', function (e) {
        const search_element = $('.search-ajax');
        search_element.empty();
        search_element.hide();

        $('.search-form input[type="text"]').val('');

        $('.header-search').hide()
    });

    $('.product-filter-ajax').on('change', '.custom-select', function (e) {
        e.preventDefault();
        var form = $(this).closest('form');
        $('.loading-ajax').fadeIn('fast');
        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_filter_product_ajax',
                data: form.serialize(),
                filter: $(e.delegateTarget).data('filter'),
            },
            success: function (res) {
                //console.log(res);
                $('.site-main').find('.products').html(res.data);
                if (res.pagination) {
                    $('.site-main').find('.woocommerce-pagination').html(res.pagination);
                } else {
                    $('.site-main').find('.woocommerce-pagination').empty();
                }
                $('.loading-ajax').fadeOut();
            },
        })
            .fail(function () {
                console.log("error");
            });
    });

    $('.woocommerce').on('click', '.filter-pagination .filter-page', function (e) {
        e.preventDefault();

        var form = $(this).closest('.site-main').find('form');
        $('.loading-ajax').fadeIn('fast');

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_filter_product_ajax',
                data: form.serialize(),
                filter: $('.product-filter-ajax').data('filter'),
                num: $(this).data('paged'),
            },
            success: function (res, textStatus, xhr) {
                //console.log(res);
                $('.site-main').find('.products').html(res.data);
                if (res.pagination) {
                    $('.site-main').find('.woocommerce-pagination').html(res.pagination);
                } else {
                    $('.site-main').find('.woocommerce-pagination').empty();
                }
                $('.loading-ajax').fadeOut();
            },
        })
            .fail(function () {
                console.log("error");
            });
    });

    //open login modal
    $('body').on('click', '.my-account.menu-item', function (event) {
        event.preventDefault();

        $("#loginModal").modal("toggle");
    });

    //load more on single product
    $('body').on('click', '.loadmore-meta', function (event) {
        event.preventDefault();

        $(this).hide();
        $(this).closest('.modal').find('.loadless-meta').show();
        $(this).parent().addClass('show_all');
    });
    $('body').on('click', '.loadless-meta', function (event) {
        event.preventDefault();

        $(this).hide();
        $(this).closest('.modal').find('.loadmore-meta').show();
        $(this).parent().removeClass('show_all');
    });

    //sign in ajax function
    $(document).on('submit', 'form[name=cabling_login_form]', function (event) {
        event.preventDefault();

        const form = $(this);
        form.find('.woo-notice').remove();
        form.find('input[type="submit"]').prop('disabled', true);

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_login_ajax',
                data: form.serialize()
            },
            success: function (data) {
                form.find('input[type="submit"]').prop('disabled', false);
                form.find('.login-username').prepend(data.mess);
                grecaptcha.reset();
                if (!data.error) {
                    if ($('input[name=is_reload]').length){
                        window.location.reload();
                    }
                    else {
                        window.location.href = data.redirect;
                    }
                }
            },
        })
            .fail(function () {
                grecaptcha.reset();
                form.find('input[type="submit"]').prop('disabled', false);
                console.log("error");
            });


        return false;
    });

    /*//validate register form
    $(document).on('submit', 'form[name=register-form]', function(event) {

        const form = $(this);
        const response = grecaptcha.getResponse();

        if( response.length == 0 ){
            form.find('.woo-notice').fadeIn();
            grecaptcha.reset();
            return false;
           }

           return true;
    });*/
    $(document).on('submit', 'form.lost_reset_password', function (event) {

        const form = $(this);

        if (form.find('input[name=woocommerce-lost-password-nonce]').length) {
            const response = grecaptcha.getResponse();

            if (response.length == 0) {
                form.prepend('<div class="woocommerce-error woo-notice" role="alert">Please verify the Captcha.</div>');
                grecaptcha.reset();
                form.find('input[type="submit"]').prop('disabled', false);
                return false;
            }
        }

        return true;
    });

    //validate register form
    $('.woocommerce').on('click', '.quick-view > a', function (e) {
        var product = $(this).parent().data('product');
        var load_product_ajax = $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_get_product_single_ajax',
                product: product,
            }
        });

        $.when(load_product_ajax).done(function (result) {
            //console.log(result);

            if (result.status == true) {
                $("#quickViewModal").find('.modal-body').html(result.data);
                $("#quickViewModal").modal('show');
            } else {
                //$(e.delegateTarget).hide();
            }
        });

        return false;
    });

    //insert value to back button
    /*if ($('.woocommerce-breadcrumb').length && $('.backbutton').length) {
        let bread_count = $('.woocommerce-breadcrumb > span').length - 2;
        let name = $('.woocommerce-breadcrumb > span').eq(bread_count).text();
        let link = $('.woocommerce-breadcrumb > span').eq(bread_count).find('a').attr('href');
        if (bread_count) {
            $('.backbutton').find('span').text(name);
            $('.backbutton').attr('href', link);
        } else {
            $('.backbutton').hide();
        }
    }*/

    //validate register form
    $('.woocommerce').on('click', '.wcb2b_login_message', function (e) {
        $("#loginModal").modal("toggle");
        return false;
    });

    //woocommerce check out
    //cabling_move_shipping_btn();
    $('body').on('change', '#ship-to-different-address-checkbox', function (e) {
        //console.log( $(this).is(":checked") );
        if ($(this).is(":checked"))
            $('.woocommerce-billing-fields').addClass('has_shipping_address');
        else
            $('.woocommerce-billing-fields').removeClass('has_shipping_address');
    });

    //trigger product category widget
    $('body').find('.product-categories .cat-parent').append('<span class="show_more"></span>');

    $('body').on('click', '.show_more', function (e) {
        var parent = $(this).closest('.cat-parent');
        parent.toggleClass('active');
        parent.find('> .children').slideToggle();
    });

    $('.top-header').on('click', '.show-language', function (e) {
        $(e.delegateTarget).find('.cabling_language_list').toggle();
    });

    if ($('form[name=pre-training-data]').length) {
        var form = $('form[name=pre-training-data]');

        $('form.wpcf7-form').find('input[name=training-id]').val(form.find('input[name=train-id]').val());
        $('form.wpcf7-form').find('input[name=training-title]').val(form.find('input[name=train-title]').val());
        $('form.wpcf7-form').find('input[name=training-place]').val(form.find('input[name=train-place]').val());
        $('form.wpcf7-form').find('input[name=training-date]').val(form.find('input[name=train-date]').val());

        $('form.wpcf7-form').on('change', '[name="is-same-participant[]"]', function (e) {
            if ($('input[name="is-same-participant[]"]:checked').length > 0) {
                $(e.delegateTarget).find('.differ-participant').slideUp();
            } else {
                $(e.delegateTarget).find('.differ-participant').slideDown();
            }
        });
    }

    $('.more-if a[href^="www"]').each(function () {
        const oldUrl = $(this).attr("href");
        const newUrl = oldUrl.replace('www', 'https://www');
        $(this).attr("href", newUrl);
    });

})(jQuery);

function cabling_move_shipping_btn() {
    var j = jQuery.noConflict();

    if (j('#ship-to-different-address').length) {
        var button = j('#ship-to-different-address').clone();

        j('#ship-to-different-address').remove();
        j('#billing_address_1_field').after(button);
    }
}

function cabling_search_ajax(ajaxSearch, paged = 1) {
    const $ = jQuery.noConflict();
    const form = $('#search-ajax-form');
    setTimeout(function () {
        const key_search = $('.search-field').val();
        if (key_search.length < 3) {
            return false;
        } else if (key_search.length > 100) {
            alert('Maximum characters is 100!');
            return;
        }

        const search_element = $('.search-ajax');
        const search_result = $('#ajax-results');

        search_element.find('.search-text span').text(key_search);

        ajaxSearch = $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'search_ajax',
                key_search: key_search,
                paged: paged,
                data: form.serialize(),
            },
            beforeSend: function () {
                if (ajaxSearch != null) {
                    ajaxSearch.abort();
                }
                search_element.removeClass('no');
                search_result.html('Searching...');
                search_element.show();
            },
            success: function (response) {
                search_element.removeClass('no');
                search_result.html(response.data);
                search_result.append(response.pagination);
                search_element.show();
                $('.header-search .close').show();
                if (response.search_query == '') {
                    search_element.hide();
                }

                save_search_log(key_search);
            },
            error: function (err) {
                search_element.addClass('no');
                search_result.html('No results found, please search again.');
                search_element.show();
                //$('.header-search .close').show();
            }
        });
    }, 500);
}


// Default Billing Disable Fields
(function ($) {

    $(".woocommerce-billing-fields").each(function () {
        $(this).find(':input').prop("readonly", true);
        $(this).find(':input').css('background-color', '#f1f1f1');
        $(this).find('span').css('background-color', '#f1f1f1');
        //console.log('teste');
        //<-- Should return all input elements in that specific form.
    });
    // $('#billing_country').prop('disabled', true);

    $('.woocommerce-checkout').on('submit', function () {
        $('#billing_country').prop('disabled', false);
    });

    /*$(document).on('input', '#registerStep input[type=password]', function () {
        const btnSubmit = $(this).closest('form').find('button[type="submit"]');
        const password = $(this).val();
        let strength = 0;
        btnSubmit.prop('disabled', true);

        $(this).css('border-color', '#dc3545');
        // Check the length of the password
        if (password.length >= 8) {
            strength += 1;
        }

        // Check for both uppercase and lowercase characters
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            strength += 1;
        }

        // Check for at least one digit
        if (/\d/.test(password)) {
            strength += 1;
        }

        // Check for at least one special character
        if (/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password)) {
            strength += 1;
        }

        // Display the password strength
        let strengthText = "";
        switch (strength) {
            case 0:
            case 1:
                strengthText = "Weak";
                break;
            case 2:
                strengthText = "Moderate";
                break;
            case 3:
                strengthText = "Strong";
                break;
            case 4:
                strengthText = "Very Strong";
                btnSubmit.prop('disabled', false);
                $(this).css('border-color', '#28a745');
                break;
        }

        if ($(this).is('input[name="re-password"]')) {
            if ($(this).val() === $('input[name="password"]').val()) {
                $(this).css('border-color', '#28a745');
            } else {
                $(this).css('border-color', '#dc3545');
            }
        }
    })*/

    // JM 20230914
    $(document).on('input', 'input[name=password_2]', function () {
        const btnSubmit1 = $(this).closest('form').find('button[type="submit"]');
        const password = $(this).val();
        let strength = 0;
        btnSubmit1.prop('disabled', true);

        $(this).css('border-color', '#dc3545');
        // Check the length of the password
        if (password.length >= 8) {
            strength += 1;
        }

        // Check for both uppercase and lowercase characters
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            strength += 1;
        }

        // Check for at least one digit
        if (/\d/.test(password)) {
            strength += 1;
        }

        // Check for at least one special character
        if (/[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/.test(password)) {
            strength += 1;
        }

        // Display the password strength
        let strengthText = "";
        switch (strength) {
            case 0:
            case 1:
                strengthText = "Weak";
                break;
            case 2:
                strengthText = "Moderate";
                break;
            case 3:
                strengthText = "Strong";
                break;
            case 4:
                strengthText = "Very Strong";
                btnSubmit1.prop('disabled', false);
                $(this).css('border-color', '#28a745');
                break;
        }

        if ($(this).is('input[name="password_2"]')) {
            if ($(this).val() === $('input[name="password_1"]').val()) {
                $(this).css('border-color', '#28a745');
                btnSubmit1.prop('disabled', false);
            } else {
                $(this).css('border-color', '#dc3545');
                btnSubmit1.prop('disabled', true);
            }
        }
    })

    //submit create the child user
    $(document).on('submit', 'form[name=create-user-form]', function (event) {
        event.preventDefault();

        const form = $(this);
        form.find('.woo-notice').empty();
        form.find('button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_create_webshop_user_ajax',
                data: form.serialize()
            },
            success: function (response) {
                form.find('.woo-notice').html(response.message).show();
                if (response.error === true) {
                    form.find('button[type="submit"]').prop('disabled', false);
                } else {
                    window.location.reload();
                }
            },
        })
            .fail(function () {
                console.log("error");
            });


        return false;
    });
    $(document).on('submit', '#share-email-form', function (event) {
        event.preventDefault();

        //const response = grecaptcha.getResponse();

        const form = $(this);
        form.find('.woo-notice').remove();
        form.find('button[type="submit"]').prop('disabled', true);


        $.ajax({
            url: CABLING.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'cabling_share_page_email_ajax',
                data: form.serialize(),
                nonce: CABLING.nonce,
            },
            success: function (response) {
                if (response.success) {
                    form.find('button[type="submit"]').prop('disabled', false);
                    form.prepend(response.data.data);

                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                } else {
                    form.prepend(`<div class="woocommerce-error woo-notice" role="alert">${response.data}</div>`);
                    grecaptcha.reset();
                    form.find('input[type="submit"]').prop('disabled', false);
                    return false;
                }

            },
        })
            .fail(function () {
                console.log("error");
            });


        return false;
    });

    $(document).on('click', '.child-customer .delete-child', function (event) {
        event.preventDefault();

        if (confirm("This action cannot be undone, do you confirm the user deletion?")) {
            const id = $(this).attr('data-action');
            $.ajax({
                url: CABLING.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'cabling_delete_webshop_user_ajax',
                    data: id
                },
                success: function (response) {
                    if (response.success === true) {
                        window.location.reload();
                    }
                },
            })
                .fail(function () {
                    console.log("error");
                });
        }
    });

    $(document).on('click', '.show-email-share .email-share', function () {
        $('#emailShareModal').modal('show');
        return false;
    })

})(jQuery);

//navigation
(function ($) {
    add_mobile_class_to_navigation();

    $(window).resize(function () {
        add_mobile_class_to_navigation();
    });

})(jQuery);

function add_mobile_class_to_navigation() {
    if (jQuery(window).width() < 992) {
        jQuery("body").find('.main-navigation').addClass('collapse');
    }
}

function save_search_log(text) {
    jQuery.ajax({
        url: CABLING.ajax_url,
        type: 'POST',
        dataType: 'json',
        data: {
            action: 'cabling_save_search_log_ajax',
            data: text
        }
    });
}

function generateCaptchaElement(id) {
    const recaptcha_element = jQuery(`#${id}`);
    const sitekey = recaptcha_element.attr('data-sitekey');
    grecaptcha.render(id, {
        'sitekey': sitekey,
    });
}
