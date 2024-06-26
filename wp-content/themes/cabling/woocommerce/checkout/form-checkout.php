<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be Sign in to checkout.', 'woocommerce'));
    return;
}
$user_id = get_current_user_id();
$customer_level = get_customer_level($user_id);
$user_wp9_form = get_user_meta($user_id,'user_wp9_form',true);
$transportation_companies = get_field('transportation_companies','option');
$carriers = [];
foreach( $transportation_companies as $transportation_companie ){
    $carriers[$transportation_companie['transportation']['carrier_id']] = $transportation_companie['transportation']['carrier_name'];
}

// Ref GID-1050 - Handle carrier
$fedex_method = 'wf_fedex_woocommerce_shipping:FEDEX_GROUND';
$free_shipping = 'free_shipping:5';
// Local
// $fedex_method = 'wf_fedex_woocommerce_shipping:FIRST_OVERNIGHT';
// $free_shipping = 'free_shipping:9';

WC()->session->set( 'chosen_shipping_methods', array($fedex_method) );
?>
<script>
    // Ref GID-1050 - Handle carrier
    var fedex_method = '<?= $fedex_method; ?>';
    var free_shipping = '<?= $free_shipping; ?>';
</script>
<style>
    select.form-select {
        border-width: 1px !important;
        border-color: #ccc;
        box-shadow: unset;
        border-radius: 4px;
        width: 100%;
    }
    .ml-2 {
        margin-left:10px;
    }
</style>
<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <?php if ($checkout->get_checkout_fields()) : ?>

        <?php do_action('woocommerce_checkout_before_customer_details'); ?>
        <div class="multisteps-form">
            <!--progress bar-->
            <div class="row">
                <div class="col-12">
                    <div class="multisteps-form__progress">
                        <div id="shipping-step-progress" class="multisteps-form__progress-btn js-active" type="button" title="<?php _e('Shipping Details', 'cabling') ?>">
                            <span><?php _e('Delivery Details', 'cabling') ?></span>
                            <p class="note"><?php _e('Please note: Delivery only available to the USA', 'cabling') ?></p>
                        </div>
                        <div id="carrier-step-progress" class="multisteps-form__progress-btn" type="button" title="<?php _e('Shipping', 'cabling') ?>"><?php _e('Shipping', 'cabling') ?></div>
                        <div id="billing-step-progress" class="multisteps-form__progress-btn" type="button" title="<?php _e('Billing', 'cabling') ?>"><?php _e('Billing', 'cabling') ?></div>
                        <?php if($customer_level == 1 && !$user_wp9_form): ?>
                            <div id="user_wp9_form-step-progress" class="multisteps-form__progress-btn" type="button" title="<?php _e('W9 Form', 'cabling') ?>"><?php _e('W9 Form', 'cabling') ?></div>
                        <?php endif; ?>
                        <div id="order_review-step-progress" class="multisteps-form__progress-btn" type="button" title="<?php _e('Order Summary', 'cabling') ?>"><?php _e('Order Summary', 'cabling') ?></div>
                    </div>
                </div>
            </div>
            <!--form panels-->
            <div class="row">
                <div class="col-12">
                    <div class="multisteps-form__form" id="customer_details">
                        <!--single form panel-->
                        <div id="shipping-step" class="multisteps-form__panel js-active" data-animation="scaleIn">
                            <div class="multisteps-form__content shipping-address-content">
                                <div class="row">
                                    <div class="col-12">
                                        <a class="add-new-address-checkout"
                                           onclick="thmaf_add_new_shipping_address(event, this,'shipping')">Add a new
                                            address</a>
                                        <?php do_action('woocommerce_checkout_shipping'); ?>
                                        <?php wc_get_template_part('checkout/deliver', 'detail'); ?>
                                    </div>
                                    <!--<div class="col-sm-6 col-xs-12">
                                        <?php /*woocommerce_order_review(); */ ?>
                                    </div>-->
                                </div>
                                <div class="button-row wp-block-button block-button-black d-flex mt-4">
                                    <button class="ml-auto js-btn-next submit-carrier-step wp-element-button"
                                            type="button"
                                            title="<?php _e('Continue', 'cabling') ?>"><?php _e('Continue', 'cabling') ?></button>
                                </div>
                            </div>
                        </div>

                        <!--single form panel-->
                        <div id="carrier-step" class="multisteps-form__panel" data-animation="scaleIn">
                            <div class="multisteps-form__content">
                                <div class="woocommerce-carrier-details">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="ml-2 mb-3">
                                                <h4>Transportation company supplier by Datwyler</h4>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="carrier_type" id="carrier_type_fedex" value="<?= $fedex_method; ?>" checked>
                                                    <label class="form-check-label" for="carrier_type_fedex">
                                                        FEDEX
                                                    </label>
                                                </div>
                                            </div>
                                            <?php if($customer_level == 2): ?>
                                            <div class="ml-2 mb-3">
                                                <h4>Transport company provided by the client</h4>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="carrier_type" id="carrier_type_free" value="<?= $free_shipping; ?>">
                                                    <label class="form-check-label" for="carrier_type_free">
                                                        <select class="form-select mt-1" name="carrier_id">
                                                            <?php foreach( $carriers as $carrier_id => $carrier_name ):?>
                                                            <option value="<?= $carrier_id; ?>"><?= $carrier_name; ?></option>
                                                            <?php endforeach;?>
                                                        </select>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-row wp-block-button block-button-black d-flex mt-4">
                                <button class="ml-auto js-btn-next submit-billing-step wp-element-button" type="button" title="Continue">Continue</button>
                            </div>
                        </div>

                        <!--single form panel-->
                        <div id="billing-step" class="multisteps-form__panel" data-animation="scaleIn">
                            <div class="multisteps-form__content">
                                <div class="woocommerce-billing-details">
                                    <?php do_action('woocommerce_checkout_billing'); ?>
                                </div>
                            </div>
                        </div>

                        <?php if($customer_level == 1 && !$user_wp9_form): ?>
                        <!--single form panel-->
                        <div id="user_wp9_form-step" class="multisteps-form__panel" data-animation="scaleIn">
                            <div class="multisteps-form__content">
                                <?php
                                    $gi_wp_form_9 = apply_filters('woocommerce_checkout_gi_add_wp_form_9', null);
                                    echo $gi_wp_form_9;
                                ?>
                            </div>
                            <div class="wp-block-button button-row block-button-black d-flex">
                                <button class="wp-element-button ml-auto continue-to-summary" type="button" title="Continue">Continue</button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!--single form panel-->
                        <div id="order_review-step" class="multisteps-form__panel" data-animation="scaleIn">
                            <div class="multisteps-form__content">
                                <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                                <?php do_action('woocommerce_checkout_before_order_review'); ?>

                                <div id="order_review" class="woocommerce-checkout-review-order">
                                    <?php do_action('woocommerce_checkout_order_review'); ?>
                                </div>

                                <?php do_action('woocommerce_checkout_after_order_review'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

    <?php endif; ?>


</form>
<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
