<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package test
 */
$post_id = get_the_ID();
$categories = getPostCategory($post_id);
$objects = get_queried_object();


$post_name = get_post_type_name($objects->post_type);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
    <div class="blog-breadcrumbs mb-5">
        <?php printf(__('Home / %s / %s', 'cabling'), $post_name, get_the_title())  ?>
    </div>
    <header class="entry-header">
        <div class="category post-item d-flex">
            <h5><?php echo $categories ?></h5>
            <?php echo do_shortcode('[Sassy_Social_Share url="' . get_permalink() . '"]') ?>
        </div>
        <div class="title"><?php the_title('<h2>', '</h2>') ?></div>
        <div class="entry-meta">
            <?php the_date('F d, Y'); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'test'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );
        ?>
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
