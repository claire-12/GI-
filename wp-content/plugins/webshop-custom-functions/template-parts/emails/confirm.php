<?php

defined( 'ABSPATH' ) || exit;

$blogname = get_bloginfo('name');

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php echo esc_html__( 'Hi,', 'woocommerce' ) ?></p>
<p><?php printf(esc_html__( 'To ensure you receive our latest updates. Please take a moment to confirm your subscription by clicking the link: %1$s', 'woocommerce' ), make_clickable( esc_url( $link ))) ?></p>
<p><?php echo esc_html__( 'If you did not request this subscription, you can safely ignore this email.', 'woocommerce' ) ?></p>
<p><?php printf( esc_html__( 'Thank you for choosing %1$s! for your subscription. We look forward to keeping you informed!', 'woocommerce' ), esc_html( $blogname )); ?></p>

<?php
do_action( 'woocommerce_email_footer' );
