<?php
if (has_post_thumbnail($postId)) {
    $thumbnail_id = get_post_thumbnail_id($postId);
} else {
    $thumbnail_id = 1032601;
}
$thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
?>
<div class="size-full part-item" style="position: relative; ">
    <a href="<?php echo get_the_permalink($postId) ?>">
        <?php echo $thumbnail ?>
    </a>
    <h4 class="my-3"><?php echo get_the_title($postId) ?></h4>
    <div class="desc"><?php echo get_the_excerpt($postId) ?></div>
</div>
