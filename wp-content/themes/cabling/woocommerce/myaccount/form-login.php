<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

do_action('woocommerce_before_customer_login_form'); ?>
<header class="page-header d-flex">
    <h1 class="entry-title"><?php echo get_the_title() ?></h1>
</header><!-- .entry-header -->

<div class="u-columns col2-set" id="customer_login">
    <div class="u-column1 col-1">
        <form class="woocommerce-form woocommerce-form-login login" method="post" name="cabling_login_form">
            <h2><?php esc_html_e('Existing Users', 'woocommerce'); ?></h2>
            <p class="sub-heading login-username"><?php esc_html_e('Sign In', 'woocommerce'); ?></p>

            <?php do_action('woocommerce_login_form_start'); ?>

            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label class="screen-reader-text"
                       for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span
                            class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="log" id="username"
                       autocomplete="username"
                       required
                       value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                       placeholder="<?php esc_html_e('Email Address*', 'woocommerce'); ?>"/><?php // @codingStandardsIgnoreLine ?>
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme"
                           type="checkbox" id="rememberme" value="forever"/>
                    <span><?php esc_html_e('Remember Email', 'woocommerce'); ?></span>
                </label>
            </p>
            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label class="screen-reader-text" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>
                    &nbsp;<span class="required">*</span></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="pwd"
                       required
                       id="password" autocomplete="current-password"
                       placeholder="<?php esc_html_e('Password*', 'woocommerce'); ?>"/>
                <p class="woocommerce-LostPassword lost_password">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgotten Password', 'woocommerce'); ?></a>
                </p>
            </p>

            <?php do_action('woocommerce_login_form'); ?>

            <p class="form-row">
                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="block-button" name="login"
                        value="<?php esc_attr_e('Sign In', 'woocommerce'); ?>"><?php esc_html_e('Sign In', 'woocommerce'); ?></button>
            </p>

            <?php do_action('woocommerce_login_form_end'); ?>

        </form>

    </div>

    <div class="u-column2 col-2 login col-register">
        <h2><?php esc_html_e('New User', 'woocommerce'); ?></h2>
        <form method="POST" name="register-form" id="register-form">
            <p class="sub-heading">
                <span><?php esc_html_e('Register for an account today to gain access to these Datwyler benefits:', 'woocommerce'); ?></span>
            <ul>
                <!--
                <li>Save and retrieve quotes</li>
                <li>Personalised offers and news</li>
                <li>Test reports</li>-->
                <li>Datwyler Industrial Sealing News</li>
                <li>Shipments</li>
                <li>Sales backlog</li>
                <li>Inventory, Lead time & Pricing</li>
            </ul>
            </p>

            <div class="form-group">
                <label class="screen-reader-text" for="register_email"><?php _e('Email', 'cabling') ?></label>
                <input type="email" class="form-control" placeholder="<?php _e('Email Address*', 'cabling') ?>"
                       name="register_email"
                       value="<?php echo $_POST['register_email'] ?? '' ?>"
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       id="register_email" required>
            </div>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo get_field('gcapcha_sitekey_v2', 'option'); ?>"></div>
            </div>
            <div class="submit-btn">
                <?php wp_nonce_field('cabling-register', 'register-nounce'); ?>
                <button class="block-button" type="submit"
                        class="submit-register"><?php _e('Register Now', 'cabling') ?></button>
            </div>

        </form>
    </div>

</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
