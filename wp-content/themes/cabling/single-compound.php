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
        <div class="container">
            <div class="woo-breadcrumbs d-flex align-items-center">
                <a href="<?php echo home_url('/products-and-services/') ?>" class="back-button">
                    <i class="fa-light fa-arrow-left"></i>
                    <?php echo __('Back to Results', 'cabling') ?>
                </a>
                <nav class="woocommerce-breadcrumb">
                    <span>
                        <span><?php echo __('Products &amp; Services', 'cabling') ?> / </span>
                    </span>
                    <span>
                        <span><?php echo __('O-rings', 'cabling') ?> / </span>
                    </span>
                    <?php echo __('Compounds', 'cabling') ?>
                </nav>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3">
                    <?php get_template_part('template-parts/filter', 'compound'); ?>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="single-compound-inner">
                        <?php
                        while (have_posts()) :
                            the_post();

                            get_template_part('template-parts/content', 'compound');

                        endwhile; // End of the loop.
                        ?>
                        <?php get_template_part('template-parts/compound', 'table'); ?>
                        <?php the_field('table_description'); ?>
                        <?php cabling_add_quote_button(); ?>
                    </div><!-- .row -->
                </div>
            </div>
        </div>
        <div class="col-12">
            <?php cabling_add_quote_section(); ?>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
