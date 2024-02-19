<?php
if (has_post_thumbnail($postId)) {
    $thumbnail_id = get_post_thumbnail_id($postId);
} else {
    $thumbnail_id = 1032601;
}
$thumbnail = wp_get_attachment_image($thumbnail_id, 'post-thumbnail');
$cat = get_the_term_list($postId, 'news-category');
?>
<div class="related-item category-block">
    <div class="wp-element-caption"><?php echo $cat ?></div>
    <a href="<?php echo get_the_permalink($postId) ?>">
        <?php echo $thumbnail ?>
    </a>
    <div class="news-content">
        <h4><?php echo get_the_title($postId) ?></h4>
        <div class="date"><?php echo get_the_date('M d, Y', $postId) ?></div>
        <div class="desc"><?php echo wp_trim_excerpt('', $postId) ?></div>
        <a href="<?php echo get_the_permalink($postId) ?>"
           class="block-button btn-red">
            <span><?php echo __('Read more', 'cabling') ?></span>
        </a>
    </div>
</div>
