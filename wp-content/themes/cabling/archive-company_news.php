<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
$current_posts_per_page = get_option('posts_per_page');
$total_posts = wp_count_posts('company_news')->publish;
$categories = get_categories(['taxonomy' => 'news-category']);
$tags = get_terms(array(
    'taxonomy' => 'news_tag',
    'hide_empty' => false,
));
$block_post = get_post(1035342);
get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php get_template_part('template-parts/page', 'news'); ?>
            <div class="container">
                <div class="blog-wrap">
                    <div class="row">
                        <div class="col-sm-12 col-lg-4">
                            <?php get_template_part('template-parts/filter', 'blog', ['categories' => $categories, 'tags' => $tags]); ?>
                        </div>
                        <div class="col-sm-12 col-lg-8">
                            <?php if (have_posts()) : ?>
                            <div class="blog-breadcrumbs">
                                <?php echo __('Home / News', 'cabling') ?>
                            </div>
                            <?php get_template_part('template-parts/filter_heading', 'blog', ['total' => $total_posts]); ?>
                            <div class="post-wrapper">
                                <?php
                                /* Start the Loop */
                                while (have_posts()) :
                                    the_post();
                                    get_template_part('template-parts/ajax/content', get_post_type());
                                endwhile; ?>
                                <?php
                                else :

                                    get_template_part('template-parts/content', 'none');

                                endif;
                                ?>
                            </div>
                            <div class="ajax-pagination text-center">
                                <div class="number-posts"><?php printf(__('Showing %s of %s Articles', 'cabling'), $current_posts_per_page, $total_posts) ?></div>
                                <button class="block-button"
                                        id="load-post-ajax"><?php echo __('Load more', 'cabling') ?></button>
                            </div>
                        </div>
                        <div class="col-12 mt-5 news-innovation">
                            <?php
                            if ($block_post) {
                                echo apply_filters('the_content', $block_post->post_content);
                            }
                            ?>
                        </div>
                    </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
