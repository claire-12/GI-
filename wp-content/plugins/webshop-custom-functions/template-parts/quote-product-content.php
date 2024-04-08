<div id="quote-product-content" class="quote-product-content">
    <button type="button" class="button-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-x"></i>
    </button>
    <div class="wrap-inner container">
        <h4 class="text-center"><?php echo __('Request a quote', 'cabling') ?></h4>
        <h5 class="text-center step2-quote"><?php echo __('Thanks for offering us the opportunity to quote.', 'cabling'); ?></h5>
        <?php if (!$is_user_logged_in): ?>
            <div class="step2-quote">
                <h5><?php echo __('If it’s your first time here, you can either continue as a guest, or sign up with My Account, Datwyler’s online account management portal.', 'cabling'); ?></h5>
                <h5><?php echo __('If you’re an existing My Account user, just log-in to complete your request.', 'cabling'); ?></h5>
            </div>
            <?php wc_get_template('template-parts/register-block.php', [], '', WBC_PLUGIN_DIR); ?>
        <?php endif ?>
        <form id="form-request-quote" class="form-request-quote <?php echo $is_user_logged_in ? '' : 'hidden' ?>"
              action="<?php echo home_url(); ?>" method="post"
              enctype="multipart/form-data">
            <?php if (!$is_user_logged_in): ?>
                <div class="login-wrapper-non d-flex justify-content-center mb-5" style="opacity: 0">
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
            <?php else: ?>
                <?php
                $current_user = wp_get_current_user();
                $full_name = "$current_user->first_name $current_user->last_name";
                $client_number = get_user_meta($current_user->ID, 'client-number', true);
                $billing_company = get_user_meta($current_user->ID, 'billing_company', true);
                $my_account_content = get_field('my_account_content', 'options');
                $avatar_url = get_avatar_url($current_user->ID, array('size' => 150));
                $lost_password_url = esc_url(wp_lostpassword_url()); ?>
                <div class="login-wrapper-non mb-5 text-center">
                    <?php if (!empty($product)): ?>
                        <p class="text-bold mb-3">
                            <strong><?php echo __('I would like to be informed about this product:', 'cabling') ?></strong>
                        </p>
                    <?php endif; ?>
                    <div class="account-heading">
                        <p class="welcome-text">
                            <?php
                            printf(
                            /* translators: 1: user display name 2: logout url */
                                wp_kses(__('Welcome %1$s', 'woocommerce'), $allowed_html),
                                esc_html($full_name ?: $current_user->display_name),
                            );
                            ?>
                        </p>
                        <ul class="account-meta d-flex align-items-center justify-content-center">
                            <li>
                                <?php
                                printf(
                                /* translators: 1: user display name 2: logout url */
                                    wp_kses(__('Account: %1$s', 'woocommerce'), $allowed_html),
                                    $current_user->ID,
                                );
                                ?>
                            </li>
                            <?php if (!empty($client_number)): ?>
                                <li>
                                    <?php
                                    printf(
                                    /* translators: 1: user display name 2: logout url */
                                        wp_kses(__('SAP Account Number: %1$s', 'woocommerce'), $allowed_html),
                                        $client_number,
                                    );
                                    ?>
                                </li>
                            <?php endif ?>
                            <li>
                                <?php
                                printf(
                                /* translators: 1: user display name 2: logout url */
                                    wp_kses(__('Company: %1$s', 'woocommerce'), $allowed_html),
                                    $billing_company,
                                );
                                ?>
                            </li>
                            <li>
                                <?php
                                printf(
                                /* translators: 1: user display name 2: logout url */
                                    wp_kses(__('Employee', 'woocommerce'), $allowed_html),
                                    esc_html(''),
                                );
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif ?>
            <?php if (!empty($filter_params)): ?>
                <?php $filters = []; ?>
                <div class="filter-params-quote">
                    <ul style="columns: 2">
                        <?php foreach ($filter_params as $param): ?>
                            <li>
                                <strong><?php echo $param[0] ?? '-' ?>:</strong>
                                <?php echo $param[1] ?? '-' ?>
                            </li>
                            <?php $filters[] = implode(': ', $param); ?>
                        <?php endforeach ?>
                    </ul>
                    <input type="hidden" name="filter-params"
                           value="<?php echo base64_encode(json_encode($filters)) ?>">
                </div>
            <?php endif ?>
            <div class="row gx-5">
                <?php if (isset($product)): ?>
                    <div class="col-12 quote-product-overview">
                        <?php
                        ob_start();
                        cabling_woocommerce_pdf_document($product);
                        echo ob_get_clean();
                        ?>

                    </div>
                <?php endif ?>
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="first_name" id="firstName"
                                       value="<?php echo $first_name ?? '' ?>" required>
                                <label for="firstName" class="form-label">First Name<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="last_name" id="last_name"
                                       value="<?php echo $last_name ?? '' ?>" required>
                                <label for="last_name" class="form-label">Last Name<span
                                            class="required">*</span></label>
                            </div>
                            <?php echo show_product_field('function', array(
                                'options' => CRMConstant::FUNCTION_CONTACT,
                                'label' => __('Function', 'woocommerce'),
                                'class' => 'form-group has-focus mb-3',
                                'required' => true,
                                'default' => $function ?? '',
                            )); ?>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="job_title" id="job_title"
                                       value="<?php echo $job_title ?? '' ?>">
                                <label for="job_title"
                                       class="form-label">Job Title</label>
                            </div>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="company" id="company-name"
                                       value="<?php echo $company ?? '' ?>"
                                       required>
                                <label for="company-name" class="form-label">Company name<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group">
                                <input type="email" class="form-control" name="email" id="email"
                                       value="<?php echo $email ?? '' ?>"
                                       required>
                                <label for="email" class="form-label">Professional Email<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group form-phone">
                                <input type="tel" class="form-control" id="mobile-phone"
                                       value="<?php echo $phone_number ?? ''; ?>" required>
                                <span id="mobile-phone-validate" class="hidden input-error"></span>
                                <input type="hidden" class="phone_number" name="billing_phone"
                                       value="<?php echo $billing_phone ?? ''; ?>">
                                <input type="hidden" class="phone_code" name="billing_phone_code"
                                       value="<?php echo $billing_phone_code ?? ''; ?>">
                                <label for="phone" class="form-label">Professional Mobile number<span
                                            class="required">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <p><strong>Company<span class="required">*</span></strong></p>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="billing_address_1" id="company-street"
                                       value="<?php echo $billing_address_1 ?? '' ?>"
                                       required>
                                <label for="company-street" class="form-label">Street Address<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="billing_address_2"
                                       id="company-street-number"
                                       value="<?php echo $billing_address_2 ?? '' ?>"
                                       required>
                                <label for="company-street-number" class="form-label">Street Number<span
                                            class="required">*</span></label>
                            </div>

                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="billing_city" id="company-city"
                                       value="<?php echo $billing_city ?? '' ?>"
                                       required>
                                <label for="company-city" class="form-label">City<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="billing_postcode"
                                       id="company-postcode"
                                       value="<?php echo $billing_postcode ?? '' ?>"
                                       required>
                                <label for="company-postcode" class="form-label">Postcode<span
                                            class="required">*</span></label>
                            </div>
                            <?php echo show_product_field('billing_country', array(
                                'options' => CRMCountry::getCountries(),
                                'label' => __('Country', 'woocommerce'),
                                'class' => 'form-group has-focus mb-3',
                                'required' => true,
                                'key' => true,
                                'default' => $billing_country ?? '',
                            )); ?>
                            <?php
                            echo show_product_field('billing_state', array(
                                'options' => CRMCountry::getStatesByCountryCode($billing_country ?? ''),
                                'label' => __('State', 'woocommerce'),
                                'class' => 'form-group has-focus mb-4 mt-3',
                                'required' => true,
                                'key' => true,
                                'default' => $billing_state ?? '',
                            ));
                            ?>
                            <div class="wp-block-button block-button-black continue-step-2"
                                 style="text-align: right">
                                <a class="wp-block-button__link has-text-align-center wp-element-button"
                                   href="javascript:void(0)">Continue</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="quote-step-2 <?php echo $is_user_logged_in ? '' : 'hidden' ?>">
                <hr class="mb-5">
                <div class="row gx-5">
                    <div class="col-md-12 col-lg-6">
                        <div class="mb-3 form-group1">
                            <?php product_of_interest_field($product_of_interest ?? '') ?>
                        </div>
                        <div class="mb-3 form-group">
                            <input type="date" class="form-control date-picker" name="when-needed" id="when-needed"
                                   value="<?php echo $when_needed ?? '' ?>">
                            <label for="when-needed" class="form-label">By when do you need the product delivered to
                                your business?</label>
                        </div>
                        <div class="mb-3 form-group">
                            <input type="number" class="form-control" name="volume" id="volume" min="0"
                                   value="<?php echo $volume ?? '' ?>"
                            >
                            <label for="volume" class="form-label">Quantity needed</label>
                        </div>
                        <div class="mb-3 form-group dimension-not-oring">
                            <input type="text" class="form-control" name="dimension" id="dimension"
                                   value="<?php echo $dimension ?? '' ?>"
                            >
                            <label for="dimension" class="form-label">Dimensions needed<span
                                        class="help"></span></label>
                        </div>
                        <div class="mb-3 dimension-oring hidden">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <input type="number" class="form-control" name="dimension_oring[id]"
                                               id="dimension-id" step="0.001" min="0"
                                               value=""
                                        >
                                        <label for="dimension-id" class="form-label">ID</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <input type="number" class="form-control" name="dimension_oring[od]"
                                               id="dimension-od" step="0.001" min="0"
                                               value=""
                                        >
                                        <label for="dimension-od" class="form-label">OD</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <input type="number" class="form-control" name="dimension_oring[width]"
                                               step="0.001" min="0"
                                               id="dimension-width" value=""
                                        >
                                        <label for="dimension-width" class="form-label">CS</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <?php echo show_product_field('dimension_oring[type]', array(
                                        'options' => array(
                                            'MMT' => __('Milimeters', 'cabling'),
                                            'INH' => __('Inches', 'cabling'),
                                        ),
                                        'label' => __('Unit', 'woocommerce'),
                                        'key' => true
                                    )); ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 form-group">
                            <input type="text" class="form-control" name="part-number" id="part-number"
                                   value="<?php echo $part_number ?? '' ?>"
                            >
                            <label for="part-number" class="form-label">Your Internal Reference Number (if
                                applicable)<span
                                        class="help"></span></label>
                        </div>
                        <div class="mb-3 upload-files box p-3">
                            <label for="file" class="form-label">Upload Diagram(s)</label>
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
                    </div>
                    <div class="col-md-12 col-lg-6 d-flex"
                         style="flex-direction: column;justify-content: space-between; padding-bottom: 16px;">
                        <div class="o-ring-block position-relative">
                            <h5>O-RINGS / BACKUP RINGS ONLY</h5>
                            <?php product_desired_application_field($desired ?? '') ?>
                            <?php if (empty($material)): ?>
                                <?php product_material_field() ?>
                            <?php else: ?>
                                <div class="mb-3 form-group">
                                    <input type="text" class="form-control" name="o_ring[material]" id="material"
                                           value="<?php echo $material ?>" readonly>
                                    <label for="material" class="form-label">Material: <span
                                                class="help">Buna-N</span></label>
                                </div>
                            <?php endif ?>
                            <?php product_harness_field($hardness ?? ''); ?>
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="o_ring[temperature]" id="temperature"
                                       value="<?php echo $temperature ?? '' ?>"
                                >
                                <label for="temperature" class="form-label">Temperature Range Needed<span
                                            class="help"></span></label>
                            </div>
                            <!--
                            <div class="mb-3 form-group">
                                <input type="text" class="form-control" name="o_ring[compound]" id="compound"
                                       value="<?php echo $compound ?? '' ?>"
                                >
                                <label for="compound" class="form-label">Compound: <span
                                            class="help">Nitrile</span></label>
                            </div>
                        -->
                            <div class="form-group">
                                <input type="text" class="form-control" name="o_ring[coating]" id="coating"
                                       value="<?php echo $coating ?? '' ?>"
                                >
                                <label for="coating" class="form-label">Coating Needed<span class="help"></span></label>
                            </div>
                        </div>
                        <div class="text-area">
                            <label for="additional-information" class="form-label">Any additional information?</label>
                            <textarea name="additional-information" id="additional-information" class="form-control"
                                      cols="30" rows="5" placeholder="Type your message here"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="submit-block text-center mt-3 quote-step-2 <?php echo $is_user_logged_in ? '' : 'hidden' ?>">
                <div class="form-check d-inline-block">
                    <input class="form-check-input" type="checkbox" id="share-my-data" value="yes" required>
                    <label class="form-check-label ms-2" for="share-my-data">
                         Please tick this box to confirm that you consent to Datwyler processing your personal data in order to respond to your quote request and to acknowledge that Datwyler shall process your personal data in accordance with its privacy notice, which can be found <a target="_blank"
                                                                    href="<?php echo home_url('/privacy') ?>">here</a>.
                    </label>
                    <!--                    <label class="form-check-label ms-2" for="share-my-data">
                        I would like
                    to receive sales and marketing information from Datwyler Switzerland Inc. and its affiliates,
                    based on my personal interests.
                    I give my consent to the processing of my data
                    as described in the terms and conditions <a target="_blank" href="<?php echo home_url('/privacy') ?>">here</a>.
-->

                    </label>
                </div>
                <div class="mb-3 form-group">
                    <input type="hidden" name="object_id" value="<?php echo $object['object_id'] ?? '' ?>">
                    <input type="hidden" name="object_type" value="<?php echo $object['object_type'] ?? '' ?>">
                    <?php wp_nonce_field('save_request_quote_cabling', '_wp_quote_nonce') ?>
                    <button type="submit" class="btn btn-primary btn-submit"><i class="fa-light fa-messages me-2"></i>Request
                        a quote
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
