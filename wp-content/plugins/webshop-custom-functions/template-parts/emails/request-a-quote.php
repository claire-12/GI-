<?php

defined('ABSPATH') || exit;

$blogname = get_bloginfo('name');

do_action('woocommerce_email_header', $email_heading, $email); ?>

    <p><?php printf(esc_html__('Hi %1$s,', 'woocommerce'), esc_html($data['first_name'] . ' ' . $data['last_name'])); ?></p>
    <div class="quote-content">
        <h4><?php echo esc_html__('The details of quote:', 'woocommerce'); ?></h4>
        <p>
            <span>Product:</span>
            <span><?php echo $data['product-of-interest'] ?></span>
        </p>
        <p>
            <span>Email:</span>
            <span><?php echo $data['email'] ?></span>
        </p>
        <p>
            <span>Name:</span>
            <span><?php echo $data['first_name'] . ' ' . $data['last_name'] ?></span>
        </p>
        <p>
            <span>Company:</span>
            <span><?php echo $data['company'] ?></span>
        </p>
        <p>
            <span>Function:</span>
            <span><?php echo $data['function'] ?></span>
        </p>
        <p>
            <span>Company Address:</span>
            <span><?php echo $data['billing_address_1'] ?></span>
        </p>
        <p>
            <span>Product of Interest:</span>
            <span><?php echo $data['product-of-interest'] ?></span>
        </p>
        <?php if (!empty($data['when-needed'])): ?>
            <p>
                <span>When Needed:</span>
                <span><?php echo $data['when-needed'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['volume'])): ?>
            <p>
                <span>Quantity needed:</span>
                <span><?php echo $data['volume'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['dimension'])): ?>
            <p>
                <span>Dimension:</span>
                <span><?php echo $data['dimension'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['part-number'])): ?>
            <p>
                <span>Part number (if known):</span>
                <span><?php echo $data['part-number'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['o_ring']['desired-application'])): ?>
            <p>
                <span>Desired Application:</span>
                <span><?php echo $data['o_ring']['desired-application'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['o_ring']['material'])): ?>
            <p>
                <span>Material:</span>
                <span><?php echo $data['o_ring']['material'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['o_ring']['hardness'])): ?>
            <p>
                <span>Hardness:</span>
                <span><?php echo $data['o_ring']['hardness'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['o_ring']['temperature'])): ?>
            <p>
                <span>Temperature:</span>
                <span><?php echo $data['o_ring']['temperature'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['o_ring']['compound'])): ?>
            <p>
                <span>Compound:</span>
                <span><?php echo $data['o_ring']['compound'] ?></span>
            </p>
        <?php endif ?>
        <?php if (!empty($data['o_ring']['coating'])): ?>
            <p>
                <span>Coating:</span>
                <span><?php echo $data['o_ring']['coating'] ?></span>
            </p>
        <?php endif ?>
        <p>
            <span>Additional information:</span>
            <span><?php echo $data['additional-information'] ?></span>
        </p>
    </div>

    <p><?php //printf(esc_html__('Thanks for confirming your request for quotation.','woocommerce')); ?></p>
    <p><?php printf(esc_html__('Best regards,','woocommerce')); ?></p>
    <p><?php printf(esc_html__('Datwyler Sealing Solutions','woocommerce')); ?></p><?php
do_action('woocommerce_email_footer');
