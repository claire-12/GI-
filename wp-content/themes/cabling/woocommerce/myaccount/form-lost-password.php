<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
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

do_action('woocommerce_before_lost_password_form');
?>
    <div class="informed-header">
        <h2 class="text-center"><?php echo __('Reset Password', 'cabling') ?></h2>
    </div>

    <form method="post" class="woocommerce-ResetPassword lost_reset_password">

        <p><?php echo apply_filters('woocommerce_lost_password_message', esc_html__('Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'woocommerce')); ?></p><?php // @codingStandardsIgnoreLine ?>
        <?php if (isset($_REQUEST['error']) && $_REQUEST['error'] === 'request_too_much'): ?>
            <div>
                <div class="alert alert-danger woo-notice my-2 d-inline-block" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i><span><?php echo __('Too many requests recently. Please try again later.', 'woocommerce'); ?></span></div>
            </div>
        <?php endif ?>

        <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
            <label for="user_login"><?php esc_html_e('Email', 'woocommerce'); ?></label>
            <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login"
                   id="user_login" autocomplete="username"/>
        </p>

        <div class="clear"></div>

        <?php do_action('woocommerce_lostpassword_form'); ?>

        <p class="woocommerce-form-row form-row">
            <input type="hidden" name="wc_reset_password" value="true"/>
            <button type="submit"
                    class="block-button"
                    value="<?php esc_attr_e('Reset password', 'woocommerce'); ?>"><?php esc_html_e('Reset password', 'woocommerce'); ?></button>
        </p>

        <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

    </form>
<?php
do_action('woocommerce_after_lost_password_form');
