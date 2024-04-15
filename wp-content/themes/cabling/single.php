<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php get_template_part( 'template-parts/post', 'header' ); ?>
            <div class="container">
                <?php get_template_part( 'template-parts/page', 'breadcrumb' ); ?>
                <div class="single-content">
                    <?php
                        while (have_posts()) :
                            the_post();

                            get_template_part('template-parts/content', get_post_type());

                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                        endwhile; // End of the loop.
                        ?>
                </div><!-- .row -->
            </div>
            <?php get_template_part('template-parts/content_related', get_post_type()); ?>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php get_footer(); ?>
