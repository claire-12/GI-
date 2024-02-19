<?php

/**
 * Webshop Customer Story Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'webshop_customer_story_' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values.
$className = 'webshop_customer_story webshop-block alignfull';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$customer = get_field('customer');
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="wrap-inner container">
        <div class="row gx-5 align-items-center">
            <div class="col-md-12 col-lg-5 col-img">
                <div class="col-left">
                    <?php echo get_the_post_thumbnail($customer, 'full') ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-7 col-content">
                <div class="main-content">
                    <h3 class="heading"><?php echo get_the_title($customer) ?></h3>
                    <div class="description"><?php echo get_the_excerpt($customer) ?></div>
                    <div class="meta-info">
                        <p><?php echo get_field('customer_name', $customer) ?></p>
                        <p><?php echo get_field('company', $customer) ?>, <?php echo get_field('position', $customer) ?></p>
                    </div>
                    <a href="<?php echo esc_url(get_the_permalink($customer)); ?>" class="block-button btn-red">
                        <span><?php echo __('Partner with us', 'cabling'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
