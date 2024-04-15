<?php
if (function_exists('is_bbpress') && is_bbpress() && bbp_is_forum_archive()): ?>
    <header class="page-header has-background">
        <h1 class="entry-title"><?php echo __('Forum', 'cabling') ?></h1>
        <p class="entry-sub-title"><?php echo __('Connect and get inspired', 'cabling') ?></p>
        <?php echo wp_get_attachment_image(1072825, 'full', false, ['class' => 'wp-post-image'])?>
    </header><!-- .entry-header -->
<?php else: ?>
    <?php $title = get_field('custom_title');
    if (empty($title)) {
        $title = get_the_title();
    } ?>
    <header class="page-header <?php echo has_post_thumbnail() ? 'has-background' : '' ?>">
        <h1 class="entry-title"><?php echo $title ?></h1>
        <p class="entry-sub-title"><?php echo get_field('custom_sub_tittle') ?></p>
        <?php if (has_post_thumbnail()) cabling_post_thumbnail(); ?>
    </header><!-- .entry-header -->
<?php endif; ?>