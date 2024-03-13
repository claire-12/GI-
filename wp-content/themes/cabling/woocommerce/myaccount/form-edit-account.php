<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_edit_account_form'); ?>

<form class="woocommerce-EditAccountForm edit-account" action=""
      method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?> >

    <?php do_action('woocommerce_edit_account_form_start'); ?>

    <fieldset>
        <legend><?php esc_html_e('Account Information', 'woocommerce'); ?></legend>

        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <label for="user_level"><?php esc_html_e('Client Level', 'woocommerce'); ?></label>
            <input type="text" class="woocommerce-Input input-text" name="user_level"
                   id="user_level" value="<?php echo esc_attr(get_user_meta($user->ID, 'customer_level', true)); ?>"
                   disabled/>
        </p>
        <?php if (!empty(get_user_meta($user->ID, 'client-number', true))): ?>
            <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="client-number"><?php esc_html_e('Client Number', 'woocommerce'); ?></label>
                <input type="text" class="woocommerce-Input input-text" name="client-number"
                       id="client-number"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'client-number', true)); ?>" disabled/>
            </p>
        <?php endif; ?>
        <div class="clear"></div>
        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name"
                   id="account_first_name" autocomplete="given-name"
                   value="<?php echo esc_attr($user->first_name); ?>"/>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
            <label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name"
                   id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr($user->last_name); ?>"/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?></label>
            <input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email"
                   id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>" disabled/>
        </p>
        <div class="clear"></div>
        <?php echo show_product_field('user_title', array(
            'options' => array('Ms.', 'Mr.'),
            'label' => __('Title', 'woocommerce'),
            'default' => esc_attr(get_user_meta($user->ID, 'user_title', true)) ?? '',
            'class' => ' form-group has-focus mt-4 woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide',
            'required' => true
        )); ?>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="job_title"><?php esc_html_e('Job Title', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="job_title"
                   id="job_title" value="<?php echo esc_attr(get_user_meta($user->ID, 'job_title', true)); ?>"
                   required/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="sap_customer"><?php esc_html_e('SAP Customer', 'woocommerce'); ?></label>
            <input disabled type="text" class="woocommerce-Input woocommerce-Input--email input-text"
                   name="sap_customer"
                   id="sap_customer"
                   value="<?php echo esc_attr(get_user_meta($user->ID, 'sap_customer', true)); ?>" <?php echo current_user_can('administrator') ? '' : 'disabled'; ?>/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="user_department"><?php esc_html_e('Department', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="user_department"
                   id="user_department"
                   value="<?php echo esc_attr(get_user_meta($user->ID, 'user_department', true)); ?>" required/>
        </p>
        <div class="clear"></div>
        <?php if (get_customer_type($user->ID) === MASTER_ACCOUNT): ?>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="billing_company"><?php esc_html_e('Company', 'woocommerce'); ?>&nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="billing_company"
                       id="billing_company"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_company', true)); ?>" required/>
            </p>
            <div class="clear"></div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="company_name_responsible"><?php esc_html_e('Company Responsible Full Name', 'woocommerce'); ?>
                    &nbsp;<span class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--email input-text"
                       name="company_name_responsible"
                       id="company_name_responsible"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'company_name_responsible', true)); ?>"
                       required/>
            </p>
            <div class="clear"></div>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <?php company_name_field() ?>
            </p>
            <div class="clear"></div>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="billing_vat"><?php esc_html_e('Company VAT number', 'woocommerce'); ?>&nbsp;<span
                            class="required">*</span></label>
                <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="billing_vat"
                       id="billing_vat" value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_vat', true)); ?>"
                       required/>
            </p>
            <div class="clear"></div>
        <?php endif ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="billing_address_1"><?php esc_html_e('Address', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="billing_address_1"
                   id="billing_address_1"
                   value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_address_1', true)); ?>" required/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="billing_city"><?php esc_html_e('City', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="billing_city"
                   id="billing_city" value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_city', true)); ?>"
                   required/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="billing_postcode"><?php esc_html_e('Postcode', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--email input-text" name="billing_postcode"
                   id="billing_postcode"
                   value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_postcode', true)); ?>" required/>
        </p>
        <div class="clear"></div>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="billing_country"><?php esc_html_e('Country', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <?php woocommerce_form_field(
                'billing_country',
                array(
                    'type' => 'country',
                    'default' => 'US',
                    'class' => array('mw-100'),
                    'input_class' => array('form-select')
                ),
                esc_attr(get_user_meta($user->ID, 'billing_country', true))
            )
            ?>
        </p>
        <div class="clear"></div>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="mobile-phone"><?php _e('Mobile Number', 'cabling') ?></label>
            <input type="tel" class="form-control" id="mobile-phone"
                   value="<?php echo esc_attr(get_user_phone_number($user->ID)); ?>"
                   placeholder="<?php _e('Mobile Number', 'cabling') ?>" required>
            <span id="mobile-phone-validate" class="hidden input-error"></span>
            <input type="hidden" class="phone_number" name="billing_phone"
                   value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_phone', true)); ?>">
            <input type="hidden" class="phone_code" name="billing_phone_code"
                   value="<?php echo esc_attr(get_user_meta($user->ID, 'billing_phone_code', true)); ?>">
        </p>
    </fieldset>

    <fieldset>
        <legend><?php esc_html_e('Password change', 'woocommerce'); ?></legend>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                   name="password_current" id="password_current" autocomplete="off"/>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1"
                   id="password_1" autocomplete="off"/>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
            <input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2"
                   id="password_2" autocomplete="off"/>
        </p>
    </fieldset>
    <div class="clear"></div>

    <?php do_action('woocommerce_edit_account_form'); ?>

    <p>
        <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
        <button type="submit"
                class="woocommerce-Button btn-submit button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                name="save_account_details"
                value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
        <input type="hidden" name="action" value="save_account_details"/>
    </p>

    <?php do_action('woocommerce_edit_account_form_end'); ?>
</form>

<?php do_action('woocommerce_after_edit_account_form'); ?>
