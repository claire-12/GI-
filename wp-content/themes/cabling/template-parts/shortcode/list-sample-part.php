<div class="posts-row row g-5">
    <?php foreach ($posts as $postId): ?>
        <?php
        if (has_post_thumbnail($postId)) {
            $thumbnail_id = get_post_thumbnail_id($postId);
        } else {
            $thumbnail_id = 1032601;
        }
        $thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
        ?>
        <div class="col-xs-12 col-lg-4">
            <div class="part-item show-product-quote" data-action="<?php echo $postId ?>" style="position: relative; ">
                <a style="color: inherit" href="<?php echo esc_url(get_the_permalink($postId)) ?>"><?php echo $thumbnail ?></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
