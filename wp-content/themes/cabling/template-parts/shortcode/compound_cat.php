<div class="taxonomy-slider">
    <?php foreach ($taxonomies as $taxonomy): ?>
        <?php
        if ($atts['taxonomy'] === 'product_cat') {
            $thumbnail_id = get_term_meta($taxonomy->term_id, 'thumbnail_id', true);
        } else {
            $thumbnail_id = get_field('taxonomy_image', $taxonomy);
        }
        $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
        $thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
        ?>
        <div class="carousel-cell wp-block-image size-full" style="position: relative; ">
            <span class="wp-element-caption"><?php echo $taxonomy->name; ?></span>
            <?php echo $thumbnail ?>
        </div>
    <?php endforeach; ?>
</div>
