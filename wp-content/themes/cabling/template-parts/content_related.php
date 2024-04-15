<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package test
 */

// Query for related posts
$related_posts = cabling_get_post_related(get_the_ID(), get_post_type());
?>

<?php if ($related_posts): ?>
    <div class="blog-related">
        <div class="container">
            <h2 class="heading"><?php echo __('RELATED POSTS', 'cabling') ?></h2>
            <div class="row">
                <?php foreach ($related_posts as $post): $category = getPostCategory($post->ID); ?>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="related-item category-block">
                            <?php if (!empty($category)): ?>
                                <div class="wp-element-caption"><?php echo $category ?></div>
                            <?php endif ?>
                            <a href="<?php echo get_the_permalink($post->ID) ?>">
                                <?php echo get_the_post_thumbnail($post->ID) ?>
                            </a>
                            <div class="news-content">
                                <h4><?php echo $post->post_title ?></h4>
                                <div class="date"><?php echo date('M j, Y', strtotime($post->post_date)) ?></div>
                                <div class="desc"><?php echo wp_trim_words($post->post_content, 15, '...') ?></div>
                                <a href="<?php echo get_the_permalink($post->ID) ?>"
                                   class="block-button btn-red">
                                    <span><?php echo __('Read more', 'cabling') ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
<?php endif ?>
