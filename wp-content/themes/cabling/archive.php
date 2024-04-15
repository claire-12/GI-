<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */

get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="container">
                <div class="cabling-breadcrumb woocommerce-breadcrumb">
                    <span class="bc-parent"><?php _e('You Are Here: ', 'cabling') ?></span>
                    <?php bcn_display(); ?>
                </div>

                <div class="row">
                    <?php if (have_posts()) : ?>
                        <div class="row boxes-menu">
                            <?php
                            /* Start the Loop */
                            while (have_posts()) :
                                the_post(); ?>
                                <div class="box-column col-sm-6 col-md-3 ">
                                    <a class="box-shadow" href="<?php the_permalink(); ?>"
                                       title="<?php the_title(); ?>">
                                        <div class="img hidden-xs"
                                             style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'post-thumbnail'); ?>');"></div>
                                        <div class="bottom_link">
                                            <span><?php the_title(); ?></span>
                                        </div>
                                    </a>


                                </div>
                            <?php
                            endwhile;

                            //the_posts_navigation();?>
                        </div>
                    <?php
                    else :

                        get_template_part('template-parts/content', 'none');

                    endif;
                    ?>
                </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
