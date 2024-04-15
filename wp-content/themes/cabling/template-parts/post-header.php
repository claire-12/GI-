<?php
$title = get_field('custom_title');
if (empty($title)) {
    $title = get_the_title();
}
?>
<header class="page-header <?php echo has_post_thumbnail() ? 'has-background' : '' ?>">
    <h1 class="entry-title hidden"><?php echo $title ?></h1>
    <p class="entry-sub-title"><?php echo get_field('custom_sub_tittle') ?></p>
    <?php if (has_post_thumbnail()) cabling_post_thumbnail(); ?>
</header><!-- .entry-header -->
