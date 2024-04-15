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
$message_for_gdpr = get_field('message_for_gdpr', 'options');
$message_for_gdpr = str_replace('!!date!!', date('d/m/Y'), $message_for_gdpr);

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php echo esc_html__( 'Hi,', 'woocommerce' ) ?></p>
<p><?php echo $message_for_gdpr ?></p>
<p><?php echo( esc_html__( 'Thank you for registering with us. To ensure the security of your account and stay updated with our latest features and offerings.', 'woocommerce' )); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<p><?php printf( esc_html__( 'Please click on the following link to verify and set your password: %1$s', 'woocommerce' ), make_clickable( esc_url( $link_verify ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

<?php
do_action( 'woocommerce_email_footer' );
