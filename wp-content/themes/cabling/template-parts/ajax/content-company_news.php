<?php
$post_id = get_the_ID();

// Get the categories for the current post
$categories = getPostCategory($post_id, 'news-category');
?>
<div class="post-item blog-item">
    <div class="entry-content row">
        <div class="featured-image col-12 col-lg-4">
            <a href="<?php the_permalink(); ?>">
                <?php if (has_post_thumbnail()): the_post_thumbnail('full'); ?>
                <?php else: echo wp_get_attachment_image(1138416, 'full'); endif; ?>
            </a>
        </div>
        <div class="info col-12 col-lg-8">
            <div class="post-type">
                <h5><?php echo $categories ?></h5>
            </div>
            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <div class="meta"><?php echo get_the_date('M d, Y'); ?></div>
            <div class="desc"><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></div>
            <?php echo do_shortcode('[Sassy_Social_Share url="' . get_permalink() . '"]') ?>
        </div>
    </div>
</div>
