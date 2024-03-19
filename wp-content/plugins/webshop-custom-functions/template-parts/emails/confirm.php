<?php

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php echo esc_html__( 'Hi,', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Thank you for providing your email address. Before we can proceed further, we need to confirm that this email address is valid and belongs to you.', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'To confirm your email address, please click on the link below: ', 'woocommerce' ) ?></p>
<p><?php echo make_clickable( esc_url( $link )) ?></p>
<p><?php echo esc_html__( 'If you did not request this confirmation, you can simply ignore this email.', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Thank you for your cooperation.', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Best regards,', 'woocommerce' ) ?></p>

<?php
do_action( 'woocommerce_email_footer' );
