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
<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
<p><?php printf( esc_html__( 'Thanks for creating an account on %1$s. Please click on the following link to continue: %2$s', 'woocommerce' ), esc_html( $blogname ), make_clickable( esc_url( $link_verify ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

<?php
do_action( 'woocommerce_email_footer' );
