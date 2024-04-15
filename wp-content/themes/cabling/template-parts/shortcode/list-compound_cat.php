<div class="taxonomy-row row g-5">
    <?php foreach ($taxonomies as $taxonomy): ?>
        <?php
        $thumbnail_id = get_field('taxonomy_image', $taxonomy);
        $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
        $thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
        $link = get_term_link($taxonomy);
        ?>
        <div class="col-xs-12 col-lg-4">
            <div class="tax-item wp-block-image size-full" style="position: relative; ">
                <a style="color: inherit" href="<?php echo esc_url($link) ?>"><?php echo $thumbnail ?></a>
                <h4 class="wp-caption my-3">
                    <a style="color: inherit" href="<?php echo esc_url($link) ?>"><?php echo $taxonomy->name; ?></a>
                </h4>
                <div class="description"><?php echo $taxonomy->description ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
