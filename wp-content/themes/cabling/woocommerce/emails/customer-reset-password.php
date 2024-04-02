<?php
/**
 * Customer Reset Password email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
$user = get_user_by( 'id', $user_id );
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer username */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $user->first_name . ' ' . $user->last_name ) ); ?></p>
<p><?php esc_html_e( 'We’ve received a request to reset your Datwyler Sealing My Account password.', 'woocommerce' ); ?></p>
<?php /* translators: %s: Customer username */ ?>
<p><?php printf( esc_html__( 'Email: %s', 'woocommerce' ), esc_html( $user->user_email ) ); ?></p>
<p><?php esc_html_e( 'To reset your password, just click on the link below. If you didn\'t make this request, please contact us at suso.ont.sales@datwyler.com .', 'woocommerce' ); ?></p>
<p>
	<a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'id' => $user_id ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>"><?php // phpcs:ignore ?>
		<?php esc_html_e( 'Click here to reset your password', 'woocommerce' ); ?>
	</a>
</p>
<p><?php esc_html_e( 'Best regards,', 'woocommerce' ); ?></p>
<p><?php esc_html_e( 'Datwyler Sealing', 'woocommerce' ); ?></p>

<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	//echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer' );
