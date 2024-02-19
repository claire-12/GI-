<?php

/**
 * Media Text Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'media-text' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values.
$className = 'media_text webshop-block alignfull';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$wmt_image = get_field('wmt_image');
$wmt_pre_heading = get_field('wmt_pre_heading');
$wmt_heading = get_field('wmt_heading');
$wmt_description = get_field('wmt_description');
$wmt_button = get_field('wmt_button');
$wmt_image_position = get_field('wmt_image_position');
$wmt_background = get_field('wmt_background');
//var_dump($block);
$className .= ' ' . $wmt_image_position;
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" style="background-color: <?php echo $wmt_background ?? '#fff' ?>">
    <div class="wrap-inner">
        <div class="row gx-5">
            <div class="col-md-12 col-lg-6 col-img">
                <div class="col-left">
                    <?php echo wp_get_attachment_image($wmt_image, 'full') ?>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-content">
                <div class="main-content">
                    <?php if (!empty($wmt_pre_heading)): ?>
                        <p class="pre-heading"><?php echo $wmt_pre_heading ?></p>
                    <?php endif ?>
                    <h3 class="heading"><?php echo $wmt_heading ?></h3>
                    <div class="description"><?php echo $wmt_description ?></div>
                    <?php if (isset($wmt_button['url'])): ?>
                        <a href="<?php echo esc_url($wmt_button['url']); ?>" class="block-button <?php echo ($wmt_button['url'] === '/request-a-quote/') ? 'show-product-quote' : ''; ?>">
                            <span><?php echo $wmt_button['title']; ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
