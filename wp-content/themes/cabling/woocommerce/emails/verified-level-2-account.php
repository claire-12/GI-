<?php
/**
 * Customer pre register
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$blogname = get_bloginfo('name');
$my_account_link = wc_get_account_endpoint_url('dashboard');

$content_raw = get_field('message_verified', 'options');
$content_raw = str_replace('!!site_name!!', $blogname, $content_raw);
$content_raw = str_replace('!!login_link!!', make_clickable( esc_url( $link_verify ) ), $content_raw);

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<p>
    <?php echo $content_raw; ?>
</p>
<?php

do_action( 'woocommerce_email_footer', $email );