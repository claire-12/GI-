<?php

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php echo esc_html__( 'Hello – and thanks again for asking us to contact you.', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Please take a moment to confirm your request by clicking on the following link: ', 'woocommerce' ) ?></p>
<p><?php echo make_clickable( esc_url( $link )) ?></p>
<p><?php echo esc_html__( 'If you received this message in error, there’s no action needed -- please ignore this email.', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Best regards,', 'woocommerce' ) ?></p>
<p><?php echo esc_html__( 'Datwyler Sealing', 'woocommerce' ) ?></p>

<?php
do_action( 'woocommerce_email_footer' );
