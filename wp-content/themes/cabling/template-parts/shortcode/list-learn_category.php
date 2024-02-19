<div class="taxonomy-row row g-5">
    <?php foreach ($taxonomies as $taxonomy): ?>
        <?php
        $thumbnail_id = get_field('taxonomy_image', $taxonomy);
        $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
        $thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
        $link = get_field('custom_link', $taxonomy);
        ?>
        <div class="col-12 col-lg-4">
            <div class="tax-item wp-block-image size-full">
                <a style="color: inherit" href="<?php echo esc_url($link['url']) ?>"><?php echo $thumbnail ?></a>
                <h4 class="wp-caption my-3">
                    <a style="color: inherit" href="<?php echo esc_url($link['url']) ?>"><?php echo $taxonomy->name; ?></a>
                </h4>
                <div class="description"><?php echo $taxonomy->description ?></div>
                <a href="<?php echo esc_url($link['url']) ?>" class="block-button mt-0"><?php echo __('Find out more', 'cabling');?></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
