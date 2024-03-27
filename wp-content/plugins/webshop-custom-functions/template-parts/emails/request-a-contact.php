<?php

defined('ABSPATH') || exit;

$blogname = get_bloginfo('name');

do_action('woocommerce_email_header', $email_heading, $email); ?>

    <p><?php printf(esc_html__('Hi %1$s,', 'woocommerce'), esc_html($data['your-company-sector'])); ?></p>
    <div class="quote-content">
        <h4><?php echo esc_html__('The details of contact us :', 'woocommerce'); ?></h4>
        <p>
            <span>Email:</span>
            <span><?php echo $data['your-email'] ?></span>
        </p>
        <p>
            <span>Name:</span>
            <span><?php echo $data['first-name'] . ' ' . $data['last-name'] ?></span>
        </p>
        <p>
            <span>Phone:</span>
            <span><?php echo $data['mobile'] ?></span>
        </p>
        <p>
            <span>Job title:</span>
            <span><?php echo $data['job-title'] ?></span>
        </p>
        <p>
            <span>Company:</span>
            <span><?php echo $data['your-company-sector'] ?></span>
        </p>
        <p>
            <span>Function:</span>
            <span><?php echo $data['function'][0] ?? '' ?></span>
        </p>
        <p>
            <span>Product of Interest:</span>
            <span><?php echo $data['your-product'][0] ?? '' ?></span>
        </p>
        <p>
            <span>Message:</span>
            <span><?php echo $data['your-message'] ?></span>
        </p>
    </div>

    <p><?php printf(esc_html__('Thank you for choosing %1$s! for your subscription. We look forward to keeping you informed!', 'woocommerce'), esc_html($blogname)); ?></p>
<?php
do_action('woocommerce_email_footer');
