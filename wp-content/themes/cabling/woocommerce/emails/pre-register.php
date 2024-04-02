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

<p><?php echo esc_html__( 'Hello – and thanks again for requesting access to Datwyler My Account. Still open', 'woocommerce' ) ?></p>
<p><?php printf( esc_html__( 'Please take a moment to confirm your request by clicking on the following link: %1$s', 'woocommerce' ), make_clickable( esc_url( $link_verify ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
<p><?php echo esc_html__( 'If you didn’t make this request, there’s no action needed -- please ignore this email.', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Best regards,', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Datwyler Sealing', 'woocommerce' ) ?></p>

<?php
do_action( 'woocommerce_email_footer' );
