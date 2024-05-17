(function ($) {
    add_phone_validate('#mobile-phone');
    add_phone_validate('#user_telephone');
    add_phone_validate('#contact-phone');
    checkMyAccountNavigation();
    product_filter_init();
    sortList('download-list', 'data-order', 'asc');

    $(document).on('change', 'input[name="existing-customer"]', function () {
        const numberField = $('.client-number-field');
        if ($(this).is(':checked')) {
            numberField.show();
        } else {
            numberField.hide();
        }
    })

    /*$('form.wpcf7-form').on('submit', function() {
        showLoading();
        return true;
    });*/

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

    $(document).on('keyup', '#mobile-phone', function () {
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
        $('input[name=paged]').val(1);
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
    $(document).on('change', 'select[name=product_group]', function () {
        if ($(this).val() !== '') {
            $('input[name=search-product]').prop('checked', true);
        }
    });
    $(document).on('click', '.accordion-item', function () {
        if ($(this).hasClass('filter-inch')) {
            $('#custom-size-width').attr('name', 'attributes[inches_width_custom]');
            $('#custom-size-id').attr('name', 'attributes[inches_od_custom]');
            $('#custom-size-od').attr('name', 'attributes[inches_id_custom]');
        } else if ($(this).hasClass('filter-millimeter')) {
            $('#custom-size-width').attr('name', 'attributes[milimeters_width_custom]');
            $('#custom-size-id').attr('name', 'attributes[milimeters_id_custom]');
            $('#custom-size-od').attr('name', 'attributes[milimeters_od_custom]');
        }
    });
    $(document).on('submit', '#webservice-api-form', function () {
        const sapMaterial = $('#sapMaterial');
        const parcoCompound = $('#parcocompound');
        const parcoMaterial = $('#parcomaterial');
        const show_ponumber = $('input[name="show_ponumber"]').val();

        if ($('input[name=api_page]').val() === 'inventory' && parcoMaterial.val() === '' && sapMaterial.val() === '' && parcoCompound.val() === '') {
            $('.form-error-text').show();
            return false;
        } else {
            $('.form-error-text').hide();
        }
        if ((sapMaterial.val() !== '' && parcoCompound.val() === '') || sapMaterial.val() === '' && parcoCompound.val() !== '') {
            $('.parcocompound-text').show();
            return false;
        } else {
            $('.parcocompound-text').hide();
        }

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
                if (response.success) {
                    $('#api-results').html(response.data.data);
                    if (show_ponumber !== '') {
                        showSingleTable(show_ponumber);
                    }
                } else {
                    $('#api-results').html("Nothing to show");
                }

                $('[name="show_ponumber"]').val('');
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

    $('#api-results').on('click', 'td.PurchaseOrderByCustomer', function () {
        $('#sapMaterial1').val('');
        $('#ponumber1').val('');
        $('#parcomaterial1').val('');
        $('#parcocompound1').val('');
        $('[name="show_ponumber"]').val($(this).attr('data-PurchaseOrderByCustomer'));

        $('#webservice-api-form').submit();
    });

    $(document).on('change', '[name=filter-by]', function () {
        if ($(this).val() === 'name-desc') {
            sortList('download-list', 'data-name', 'asc');
            return false;
        } else if ($(this).val() === 'name-asc') {
            sortList('download-list', 'data-name', 'desc');
            return false;
        } else {
            $(this).closest('form').submit();
        }
    })
    if ($("#infomation-form").length) {
        $("#infomation-form").validate({
            errorElement: "span",
            errorPlacement: function (error, element) {
                error.appendTo(element.parent());
            },
            submitHandler: function (form) {
                $(form).find('.woo-notice').remove();
                $('.confirm-notice').empty();

                const password = $(form).find('input[name=password]');
                if (!checkPasswordStrength(password.val())) {
                    $('.confirm-notice').html(`<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Your password must have at least: 8 characters long with at least 1 uppercase and 1 lowercase character, numbers and symbols
            </div>`);
                    // Use animate to smoothly scroll to the target element
                    $('html, body').animate({
                        scrollTop: $('#registerStep').offset().top - 200
                    }, 'slow');
                    return false;
                }

                let formData = $(form).serialize();

                $.ajax({
                    url: CABLING.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'cabling_register_new_account_ajax',
                        data: formData,
                        nonce: CABLING.nonce,
                    },
                    success: function (response) {
                        gtag('event', 'Account_Creation');
                        hideLoading();
                        if (response.success) {
                            $(form).html(response.data)
                        } else {
                            $(form).prepend(response.data);
                        }
                    },
                    beforeSend: function () {
                        showLoading();
                    }
                })
                    .fail(function () {
                        console.log("error");
                    });

                return false;
            }
        });
    }


    const wpcf7Elm = document.querySelector('.wpcf7');
	if(wpcf7Elm){
		wpcf7Elm.addEventListener('wpcf7mailsent', function (event) {
            gtag('event', 'Lead_Account');
			openModal('modalSuccess');
		}, false);
		wpcf7Elm.addEventListener('wpcf7spam', function (event) {
			openModal('modalError');
		}, false);
		wpcf7Elm.addEventListener('wpcf7invalid', function (event) {
			openModal('modalError');
		}, false);
		wpcf7Elm.addEventListener('wpcf7mailfailed', function (event) {
			openModal('modalError');
		}, false);
		wpcf7Elm.addEventListener('wpcf7submit', function (event) {
			if (event.detail.status === 'wpcf7invalid') {
				openModal('modalError');
			}
            gtag('event', 'Lead_Account');
		}, false);
	}
})(jQuery);

function sortList(element, name, order) {
    const $ = jQuery.noConflict();
    const myList = $(`#${element}`);
    if (myList.length) {
        const listItems = myList.children('div').get();

        listItems.sort(function (a, b) {
            const nameA = $(a).find('.download-item').attr(name).toLowerCase();
            const nameB = $(b).find('.download-item').attr(name).toLowerCase();

            if (order === 'asc') {
                return (nameA < nameB) ? -1 : (nameA > nameB) ? 1 : 0;
            } else if (order === 'desc') {
                return (nameA > nameB) ? -1 : (nameA < nameB) ? 1 : 0;
            }
        });

        $.each(listItems, function (index, item) {
            myList.append(item);
        });
    }
}

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
    const tableContent = $(`tr.single-${order}`);
    const tablePODetails = $('#table-order-detail');

    $(`.backlog-row`).removeClass('table-warning').show();
    $(`.row-${order}`).addClass('table-warning');

    tablePODetails.find('.table-heading span').html(order);
    tablePODetails.find('tbody').empty();
    tableContent.each(function () {
        tablePODetails.find('tbody').append(`<tr>${$(this).html()}</tr>`);
    })
    tablePODetails.removeClass('hidden');


    $('html, body').animate({
        scrollTop: tablePODetails.offset().top - 200
    }, 'fast');
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

        myAccount.addClass('has-tooltip').find('a').attr('data-title', 'Update your details and preferences');
        myAccountInfo.addClass('has-tooltip').find('a').attr('data-title', 'Update your preferences for receiving news and updates about Datwyler Industrial Sealing');
        myAccountBacklog.addClass('has-tooltip').find('a').attr('data-title', 'See details about past and open purchase orders');
        myAccountInventory.addClass('has-tooltip').find('a').attr('data-title', 'See item inventory, pricing and lead times for ordering');
        myAccountManager.addClass('has-tooltip').find('a').attr('data-title', 'Add new user associate to this account');
        myAccountShipment.addClass('has-tooltip').find('a').attr('data-title', 'See the items shipped in the last 12 months');
    }
}

function checkPasswordStrength(password) {
    let strength = 0;

    // Check for minimum length
    if (password.length >= 8) {
        strength += 1;
    }

    // Check for at least one uppercase letter
    if (/[A-Z]/.test(password)) {
        strength += 1;
    }
    // Check for at least one uppercase letter
    if (/[a-z]/.test(password)) {
        strength += 1;
    }

    // Check for at least one special character
    if (/[\W_]/.test(password)) {
        strength += 1;
    }

    // Check for at least one number
    if (/\d/.test(password)) {
        strength += 1;
    }

    return strength === 5;
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
                //$('.filter-blog').addClass('no-results');
                $('.breadcrumbs-filter span').hide();
                $('.heading .total').hide();
                $('.woocommerce-product-type-custom').show();

                if (response.data.isSizeFilter) {
                    $('.text-for-size').show();
                } else {
                    $('.text-for-size').hide();
                }
            } else {
                //$('.filter-blog').removeClass('no-results');
                $('.heading .total').show().find('span').html(response.data.total);
                $('.breadcrumbs-filter span').html(response.data.category);
            }

            const filter_meta = response.data.filter_meta;
            $('.filter-blog').find('.filter-category').each(function () {
                let that = $(this);
                let meta_key = that.attr('data-meta-key');
                if (filter_meta !== null) {
                    if (meta_key) {
                        if (filter_meta.hasOwnProperty(meta_key) && filter_meta[meta_key] && filter_meta[meta_key].includes(that.attr('data-value'))) {
                            that.show();
                        } else {
                            that.hide();
                        }
                    }
                } else {
                    that.show();
                }
            });
            $('.filter-blog').find('.accordion-item').each(function () {
                let that = $(this);
                if (
                    that.hasClass('filter-size')
                    || that.hasClass('filter-custom-hardness')
                    || that.hasClass('filter-inch')
                    || that.hasClass('filter-attribute')
                    || that.hasClass('filter-custom-size')
                    || that.hasClass('filter-millimeter')
                ) {
                    return;
                }
                that.show();
                let isHidden = 0;
                const category = that.find('.filter-category');
                category.each(function () {
                    if (!$(this).is(':visible')) {
                        ++isHidden;
                    }
                });

                if (category.length === isHidden) {
                    that.hide();
                }
            })

            hideLoading();
        },
        beforeSend: function () {
            $('.woocommerce-no-products-found').hide();
            showLoading();
        }
    });

    $('.tax-product_custom_type').on('click', '.page-numbers', function (e) {
        e.preventDefault();

        const form = $('form#form-filter-type');

        form.find('input[name=paged]').val($(this).text());
        form.submit();
        return false
    })
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
        const errorMsg = thisForm.querySelector('.input-error');

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
                    thisForm.querySelector('.phone_number').value = phoneCodeElement.value.trim();
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
                }
            }
            jQuery(jQuery(this)).closest('.form-group').addClass('has-focus');
        });

        phoneCodeElement.addEventListener("countrychange", function () {
            const countryData = iti.getSelectedCountryData();
            if (countryData && countryData.dialCode) {
                thisForm.querySelector('.phone_code').value = countryData.dialCode;
            }
            phoneCodeElement.dispatchEvent(new Event("change"));
        });

        phoneCodeElement.style.paddingLeft = "75px";

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
