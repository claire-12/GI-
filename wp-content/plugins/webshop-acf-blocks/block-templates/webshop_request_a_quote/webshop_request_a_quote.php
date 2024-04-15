<?php
$is_user_logged_in = is_user_logged_in();

/**
 * Webshop Request A Quote Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'webshop_request_a_quote_' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values.
$className = 'webshop_request_a_quote webshop-block alignfull';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!$is_user_logged_in) {
    $className .= ' quote-not_logged_in';
}

if ($is_user_logged_in) {
    $user = wp_get_current_user();
    $userId = get_master_account_id($user->ID);

    $email = $user->user_email;
    $name = $user->display_name;
    $company = get_user_meta($userId, 'billing_company', true);
    $title_function = get_user_meta($userId, 'user_title', true);
    $company_street = get_user_meta($userId, 'billing_address_1', true);
    $company_city = get_user_meta($userId, 'billing_city', true);
    $company_postcode = get_user_meta($userId, 'billing_postcode', true);
    $company_country = get_user_meta($userId, 'billing_country', true);
    $billing_phone = get_user_meta($userId, 'billing_phone', true);
    $billing_phone_code = get_user_meta($userId, 'billing_phone_code', true);
    $phone = get_user_phone_number($userId);
}

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="wrap-inner container position-relative">
        <?php if (!$is_user_logged_in): ?>
            <div class="row">
                <div class="col-12 col-lg-6 position-relative"><?php wc_get_template('template-parts/register-block.php', [], '', WBC_PLUGIN_DIR); ?></div>
            </div>
        <?php endif ?>
        <form id="form-request-quote" class="form-request-quote" action="<?php echo home_url(); ?>" method="post"
              enctype="multipart/form-data">
            <?php if (!$is_user_logged_in): ?>
                <div class="login-wrapper-non d-flex justify-content-center mb-5" style="opacity: 0 ">
                    <a style="color: inherit" href="<?php echo wc_get_account_endpoint_url('') ?>">
                        <span class="icon"><img src="<?php echo get_template_directory_uri() . '/images/signin.png' ?>"
                                                width="19" height="31" alt=""></span>
                        <span><?php _e('SIGN IN / REGISTER', 'cabling') ?></span>
                    </a>
                    <div class="form-check d-flex justify-content-center ms-4">
                        <input class="form-check-input" type="checkbox" id="continue-guest" value="yes"
                               checked="checked"
                               required>
                        <label class="form-check-label ms-2" for="continue-guest">CONTINUE AS GUEST</label>
                    </div>
                </div>
            <?php endif ?>
            <div class="row gx-5">
                <div class="col-12 col-lg-6 position-relative">
                    <div class="mb-3">
                        <label for="firstName" class="form-label screen-reader-text">Name</label>
                        <input type="text" class="form-control" name="name" id="firstName" placeholder="Name*"
                               value="<?php echo $name ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="title_function" class="form-label screen-reader-text">Title/Function*</label>
                        <input type="text" class="form-control" name="title-function" id="title_function"
                               placeholder="Title/Function*"
                               value="<?php echo $title_function ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="company-name" class="form-label screen-reader-text">Company name*</label>
                        <input type="text" class="form-control" name="company" id="company-name"
                               placeholder="Company name*"
                               value="<?php echo $company ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="company-sector" class="form-label screen-reader-text">Company Sector*</label>
                        <input type="text" class="form-control" name="company-sector" id="company-sector"
                               placeholder="Company Sector*"
                               value="<?php echo $company_sector ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="company-street" class="form-label">Company Address*</label>
                        <input type="text" class="form-control" name="company-street" id="company-street"
                               placeholder="Number/Street*"
                               value="<?php echo $company_street ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="company-city" class="form-label screen-reader-text">Company City*</label>
                        <input type="text" class="form-control" name="company-city" id="company-city"
                               placeholder="City*"
                               value="<?php echo $company_city ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="company-postcode" class="form-label screen-reader-text">Company Postcode*</label>
                        <input type="text" class="form-control" name="company-postcode" id="company-postcode"
                               placeholder="Postcode*"
                               value="<?php echo $company_postcode ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-5">
                        <label for="company-country" class="form-label screen-reader-text">Company Country*</label>
                        <?php woocommerce_form_field(
                            'billing_country',
                            array(
                                'type' => 'country',
                                'class' => array('mw-100'),
                                'input_class' => array('form-select')
                            ),
                            $company_country ?? ''
                        )
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label screen-reader-text">Professional Email*</label>
                        <input type="email" class="form-control" name="email" id="email"
                               placeholder="Professional Email**"
                               value="<?php echo $email ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label screen-reader-text">Professional Mobile number*</label>
                        <input type="tel" class="form-control" id="mobile-phone"
                               value="<?php echo $phone ?? ''; ?>"
                               placeholder="Professional Mobile number*" required>
                        <span id="mobile-phone-validate" class="hidden input-error"></span>
                        <input type="hidden" class="phone_number" name="billing_phone" value="<?php echo $billing_phone ?? ''; ?>">
                        <input type="hidden" class="phone_code" name="billing_phone_code" value="<?php echo $billing_phone_code ?? ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="product-of-interest" class="form-label screen-reader-text">Product of
                            Interest*</label>
                        <input type="text" class="form-control" name="product-of-interest" id="product-of-interest"
                               placeholder="Product of Interest*"
                               value="<?php echo $product_of_interest ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-5">
                        <label for="when-needed" class="form-label screen-reader-text">When Needed*</label>
                        <input type="text" class="form-control" name="when-needed" id="when-needed"
                               placeholder="When Needed*"
                               value="<?php echo $when_needed ?? '' ?>"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="volume" class="form-label screen-reader-text">Volume</label>
                        <input type="text" class="form-control" name="volume" id="volume" placeholder="Volume"
                               value="<?php echo $volume ?? '' ?>"
                        >
                    </div>
                    <div class="mb-3">
                        <label for="dimension" class="form-label screen-reader-text">Dimension: 0.029 x 0.004 x
                            0.040</label>
                        <input type="text" class="form-control" name="dimension" id="dimension"
                               placeholder="Dimension: 0.029 x 0.004 x 0.040"
                               value="<?php echo $imension ?? '' ?>"
                        >
                    </div>
                    <div class="mb-3">
                        <label for="part-number" class="form-label screen-reader-text">Part number: XXX</label>
                        <input type="text" class="form-control" name="part-number" id="part-number"
                               placeholder="Part number: XXX"
                               value="<?php echo $part_number ?? '' ?>"
                        >
                    </div>
                    <div class="mb-3">
                        <label for="country-of-origin" class="form-label screen-reader-text">Country of Origin</label>
                        <input type="text" class="form-control" name="country-of-origin" id="country-of-origin"
                               placeholder="Country of Origin"
                               value="<?php echo $country_of_origin ?? '' ?>"
                        >
                    </div>
                </div>
                <div class="col-12 col-lg-6 d-flex"
                     style="flex-direction: column;justify-content: space-between; padding-bottom: 16px;">
                    <div>
                        <div class="mb-3 upload-files box p-3">
                            <label for="file" class="form-label">Upload a diagram:</label>
                            <div class="dropzone" id="dropzone">
                                <i class="fa-regular fa-arrow-up-from-bracket"></i>
                                <p class="mb-0">Drag & Drop or <a href="javascript:void(0)">Choose file</a> to upload
                                </p>
                                <p class="help-text">Maximum file size 100MB</p>
                            </div>
                            <ul id="file-list"></ul>
                            <input type="file" class="form-control" id="file" name="file[]" multiple
                                   style="display: none;">
                        </div>
                        <div class="mb-3">
                            <label for="current-suppliers" class="form-label screen-reader-text">Current
                                suppliers</label>
                            <input type="text" class="form-control" name="current-suppliers" id="current-suppliers"
                                   placeholder="Current suppliers"
                                   value="<?php echo $current_suppliers ?? '' ?>"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="potential-order-size" class="form-label screen-reader-text">Potential order
                                size</label>
                            <input type="text" class="form-control" name="potential-order-size"
                                   id="potential-order-size"
                                   placeholder="Potential order size"
                                   value="<?php echo $potential_order_size ?? '' ?>"
                            >
                        </div>
                    </div>
                    <div class="text-area">
                        <label for="additional-information" class="form-label">Any additional information?</label>
                        <textarea name="additional-information" id="additional-information" class="form-control"
                                  cols="30" rows="5" placeholder="Type your message here"></textarea>
                    </div>

                </div>
                <div class="col-12">
                    <div class="form-check d-flex justify-content-center">
                        <input class="form-check-input" type="checkbox" id="share-my-data" value="yes" required>
                        <label class="form-check-label ms-2" for="share-my-data">
                            I AGREE TO <a href="<?php echo esc_url(home_url('/privacy/')) ?>" target="_blank">SHARE MY
                                DATA</a>
                        </label>
                    </div>
                    <div class="form-check mb-3 text-center wp-block-button block-button-white">
                        <?php wp_nonce_field('save_request_quote_cabling', '_wp_quote_nonce') ?>
                        <button type="submit" class="wp-element-button mt-3">
                            <span>REQUEST A QUOTE</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>