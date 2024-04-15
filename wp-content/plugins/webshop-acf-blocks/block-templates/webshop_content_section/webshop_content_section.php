<?php

/**
 * Media Text Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'webshop-content-section' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values.
$className = 'webshop-content-section webshop-block alignfull';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

$category = null;

// Load values and assign defaults.
$pre_heading = get_field('pre_heading');
$heading = get_field('heading');
$description = get_field('description');
$button = get_field('button');
$background_color = get_field('background_color');
$text_color = get_field('text_color');
$post_type = get_field('post_type');
$taxonomy = get_field('taxonomy');
$button_theme = get_field('button_theme');
$use_slider = get_field('use_slider');
$learn_category = get_field('learn_category');
$production_equipment_category = get_field('production_equipment_category');

if (!empty($learn_category))
    $category = $learn_category;

if (!empty($production_equipment_category))
    $category = $production_equipment_category;

?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>"
     style="background-color: <?php echo $background_color ?? '#fff' ?>; color: <?php echo $text_color ?? '#2B2E34' ?> ">
    <div class="wrap-inner">
        <div class="container">
            <?php if (!empty($pre_heading)): ?>
                <div class="pre-heading"><?php echo $pre_heading ?></div>
            <?php endif ?>
            <h2 class="heading"><?php echo $heading; ?></h2>
            <div class="description"><?php echo $description; ?></div>
            <?php if (!empty($button['title'])): ?>
                <div class="wp-block-buttons">
                    <div class="wp-block-button block-button-<?php echo $button_theme ?>">
                        <a
                                class="wp-element-button"
                                href="<?php echo esc_url($button['url']) ?>"
                                <?php echo empty($button['target']) ? '' : "target='". $button['target'] ."'" ?>
                        >
                            <?php echo $button['title'] ?>
                        </a>
                    </div>
                </div>
            <?php endif ?>
            <?php if (!empty($post_type)): ?>
                <div class="post-type-content blog-related" style="text-align: left">
                    <?php echo webshop_show_posts_shortcode(['post_type' => $post_type, 'taxonomy' => $category, 'use_slider' => $use_slider]); ?>
                </div>
            <?php elseif (!empty($taxonomy)): ?>
                <div class="taxonomy-content" style="text-align: left">
                    <?php echo webshop_show_categories_shortcode(['taxonomy' => $taxonomy, 'use_slider' => $use_slider]); ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
