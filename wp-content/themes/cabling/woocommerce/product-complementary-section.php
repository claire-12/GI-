<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;
$thisTerm = get_queried_object();
$parent = get_field('product_line', $thisTerm);
$args = array(
    'taxonomy' => $thisTerm->taxonomy,
    'hide_empty' => false,
    'number' => 4,
    'exclude' => [$thisTerm->term_id],
    'meta_query' => array(
        array(
            'key' => 'product_line',
            'value' => $parent,
        ),
    ),
);

$terms = get_terms($args);

if (empty($terms)){
    return;
}

?>
<div class="product-complementary-section py-5">
    <div class="wrapper-inner container">
        <h3 class="text-center"><?php echo __('Complementary Solutions', 'cabling'); ?></h3>
        <div class="row">
            <?php foreach ($terms as $term): ?>
                <div class="col-12 col-lg-3">
                    <div class="related-item mb-5">
                        <?php
                        $thumbnail_id = get_field('taxonomy_image', $term);
                        $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
                        $thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
                        $link = get_term_link($term);
                        ?>
                            <a href="<?php echo $link; ?>"
                               title="<?php echo $term->name ?>">
                                <?php echo $thumbnail; ?>
                            </a>
                            <h5>
                                <a href="<?php echo $link; ?>"
                                   title="<?php echo $term->name ?>">
                                    <?php echo $term->name ?>
                                </a>
                            </h5>
                            <div class="cat-desc mb-2">
                                <?php echo get_field('short_description', $terms) ?>
                            </div>
                            <a href="<?php echo $link; ?>" class="block-button btn-red">
                                <span><?php _e('FIND OUT MORE', 'cabling') ?></span>
                            </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
