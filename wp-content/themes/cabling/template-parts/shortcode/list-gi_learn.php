<div class="taxonomy-row row g-5">
    <?php foreach ($posts as $postId): ?>
        <?php
        if (has_post_thumbnail($postId)) {
            $thumbnail_id = get_post_thumbnail_id($postId);
        } else {
            $thumbnail_id = 1032601;
        }
        $link = get_the_permalink($postId);
        $target = '';
        $custom_link = get_field('custom_link', $postId);
        if (!empty($custom_link['url'])){
            $link = $custom_link['url'];
            $target = $custom_link['target'];
        }
        ?>
        <div class="col-12 col-lg-4 position-relative pb-5">
            <div class="tax-item wp-block-image size-full">
                <a style="color: inherit" href="<?php echo esc_url($link) ?>">
                    <?php echo wp_get_attachment_image($thumbnail_id) ?>
                </a>
                <h3 class="wp-caption my-3">
                    <a style="color: inherit" href="<?php echo esc_url($link) ?>">
                        <?php echo get_the_title($postId) ?>
                    </a>
                </h3>
                <div class="description"><?php echo get_the_excerpt($postId) ?></div>
                <a href="<?php echo esc_url($link) ?>"
                    <?php echo empty($target) ? '' : 'target="'. $target .'"' ?>
                   class="block-button mt-0"><?php echo __('Find out more', 'cabling'); ?></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
