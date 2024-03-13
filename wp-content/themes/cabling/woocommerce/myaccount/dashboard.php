<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$allowed_html = array(
    'a' => array(
        'href' => array(),
    ),
);
$full_name = "$current_user->first_name $current_user->last_name";
$client_number = get_user_meta($current_user->ID, 'client-number', true);
$billing_company = get_user_meta($current_user->ID, 'billing_company', true);
$my_account_content = get_field('my_account_content', 'options');
$avatar_url = get_avatar_url($current_user->ID, array('size' => 150));
$lost_password_url = esc_url(wp_lostpassword_url());

//echo '<pre>';
//var_dump($current_user);
?>
    <div class="account-heading text-center">
        <h2 class="page-heading"><?php echo __('Datwyler My Account', 'cabling') ?></h2>
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
    <div class="account-overview text-center">
        <div class="description">
            <?php echo __("Thank you for choosing to work with Datwyler. <br> Get the information you need 24 hours a day", 'cabling') ?>
        </div>
        <div class="overview-content">
            <h3><?php echo __('My Profile', 'cabling'); ?></h3>
            <div class="change-password-account">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="account-avatar">
                            <img class="rounded-circle" src="<?php echo $avatar_url; ?>" alt="">
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <form action="" id="change-account-password" class="change-account-password">
                            <div class="form-group1 mb-3 position-relative">
                                <label for="user_name"><?php echo __('Name:', 'cabling') ?></label>
                                <input type="text" id="user_name"
                                       value="<?php echo $full_name ?: $current_user->display_name ?>" readonly>
                            </div>
                            <div class="form-group1 mb-3 position-relative">
                                <label for="user_email"><?php echo __('Email:', 'cabling') ?></label>
                                <input type="email" id="user_email"
                                       value="<?php echo $current_user->data->user_email ?>" readonly>
                            </div>
                            <div class="form-group1 mb-3 position-relative">
                                <label for="user_password"><?php echo __('Password:', 'cabling') ?></label>
                                <input type="email" id="user_password" value="********" readonly>
                            </div>
                            <div class="btn-submit wp-block-button block-button-black">
                                <a href="<?php echo $lost_password_url ?>" class=" wp-element-button"
                                   data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                    <span>SET NEW PASSWORD</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <h3><?php echo __('CONTENT PREFERENCES', 'cabling'); ?></h3>
            <div class="keep-informed-account-wrapper">
                <?php UserInformed::setting_account_endpoint_content() ?>
            </div>
        </div>
    </div>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');

/**
 * Deprecated woocommerce_before_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_before_my_account');

/**
 * Deprecated woocommerce_after_my_account action.
 *
 * @deprecated 2.6.0
 */
do_action('woocommerce_after_my_account');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
