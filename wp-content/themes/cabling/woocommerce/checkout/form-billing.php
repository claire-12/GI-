<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;

$fields = $checkout->get_checkout_fields('billing');
?>
<div class="woocommerce-billing-fields">
    <?php if (wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()) : ?>

        <h3><?php esc_html_e('Billing &amp; Shipping', 'woocommerce'); ?></h3>

    <?php else : ?>

        <h3><?php esc_html_e('Billing address', 'woocommerce'); ?></h3>

    <?php endif; ?>

    <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

    <div class="woocommerce-billing-fields__field-wrapper">
        <div class="same-shipping-address d-flex align-items-center justify-content-between">
            <label class="billing-label" for="same-shipping-address">
                <input type="checkbox" id="same-shipping-address" name="same-as-shipping" value="1" checked>
                <span>SAME AS DELIVERY ADDRESS</span>
            </label>
            <div class="wp-block-button button-row block-button-black d-flex">
                <button class="wp-element-button ml-auto continue-to-order" type="button"
                        title="<?php _e('Continue', 'cabling') ?>"><?php _e('Continue', 'cabling') ?></button>
            </div>
        </div>
        <hr>
        <div class="accordion accordion-flush" id="accordionAddress">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <div class="accordion-button p-0 collapsed" type="button" data-bs-toggle="collapse"
                         data-bs-target="#collapseAddNew" aria-expanded="false" aria-controls="collapseAddNew">
                        <label class="billing-label" for="diff-shipping-address">
                            <input type="checkbox" id="diff-shipping-address" name="same-as-shipping" value="1">
                            <span>ADD NEW ADDRESS</span>
                        </label>
                    </div>
                </h2>
                <div id="collapseAddNew" class="accordion-collapse collapse" aria-labelledby="headingOne"
                     data-bs-parent="#accordionAddress">
                    <div class="accordion-body px-0">
                        <?php

                        foreach ($fields as $key => $field) {
                            woocommerce_form_field($key, $field, $checkout->get_value($key));
                        }
                        ?>
                        <div class="wp-block-button button-row block-button-black d-flex">
                            <button class="wp-element-button ml-auto continue-to-order new-address" type="button"
                                    title="<?php _e('Continue', 'cabling') ?>"><?php _e('Continue', 'cabling') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        foreach ($fields as $key => $field) {
            //woocommerce_form_field($key, $field, $checkout->get_value($key));
        }
        ?>
    </div>

    <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
</div>

<?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
    <div class="woocommerce-account-fields">
        <?php if (!$checkout->is_registration_required()) : ?>

            <p class="form-row form-row-wide create-account">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox"
                           id="createaccount" <?php checked((true === $checkout->get_value('createaccount') || (true === apply_filters('woocommerce_create_account_default_checked', false))), true); ?>
                           type="checkbox" name="createaccount" value="1"/>
                    <span><?php esc_html_e('Create an account?', 'woocommerce'); ?></span>
                </label>
            </p>

        <?php endif; ?>

        <?php do_action('woocommerce_before_checkout_registration_form', $checkout); ?>

        <?php if ($checkout->get_checkout_fields('account')) : ?>

            <div class="create-account">
                <?php foreach ($checkout->get_checkout_fields('account') as $key => $field) : ?>
                    <?php woocommerce_form_field($key, $field, $checkout->get_value($key)); ?>
                <?php endforeach; ?>
                <div class="clear"></div>
            </div>

        <?php endif; ?>

        <?php do_action('woocommerce_after_checkout_registration_form', $checkout); ?>
    </div>
<?php endif; ?>
