<?php
$blog_page_id = 1047997;
$title = get_field('custom_title', $blog_page_id);
$post_thumbnail = get_the_post_thumbnail($blog_page_id);
if (empty($title)) {
    $title = get_the_title();
}
?>
<header class="page-header <?php echo has_post_thumbnail() ? 'has-background' : '' ?>">
    <h1 class="entry-title"><?php echo $title ?></h1>
    <p class="entry-sub-title"><?php echo get_field('custom_sub_tittle', $blog_page_id) ?></p>
    <div class="post-thumbnail">
        <?php echo $post_thumbnail; ?>
    </div><!-- .post-thumbnail -->
</header><!-- .entry-header -->
