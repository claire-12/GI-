<?php
if (has_post_thumbnail($postId)) {
    $thumbnail_id = get_post_thumbnail_id($postId);
} else {
    $thumbnail_id = 1032601;
}
$thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
$initial_date = get_field('initial_date', $postId);
$end_date = get_field('end_date', $postId);
$location = get_field('location', $postId);

$from = DateTime::createFromFormat('d/m/Y', $initial_date);//var_dump($from->format('M d'));
$end = DateTime::createFromFormat('d/m/Y', $end_date);
?>
<div class="post-item-inner" style="position: relative;">
    <a href="<?php echo get_the_permalink($postId) ?>">
        <span class="wp-element-caption"><?php printf('%s - %s', $from->format('M d'), $end->format('M d Y')) ?></span>
        <?php echo $thumbnail ?>
    </a>
    <h4><?php echo get_the_title($postId) ?></h4>
    <div class="desc"><?php echo wp_trim_excerpt('', $postId) ?></div>
    <div class="location"><?php echo $location ?></div>
    <a href="<?php echo get_the_permalink($postId) ?>" class="block-button btn-red">
        <span><?php _e('Register') ?></span>
    </a>
</div>
