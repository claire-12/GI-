<?php
/**
 * Template Name: Homepage
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
get_header();

$page_id = get_the_ID(); ?>
    <div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php if (empty(get_field('hide_section_hero'))): ?>
            <section class="section-hero">
                <div class="main-slider">
                    <?php if (have_rows('image_repeater_hero')): ?>
                        <?php while (have_rows('image_repeater_hero')): the_row();
                            ?>
                            <div class="slider-item carousel-cell w-100" style="position: relative; ">
                                <div class="slide-inner">
                                    <?php $link_banner = get_sub_field('button_hero'); ?>
                                    <img src="<?php the_sub_field('image'); ?>" alt="">
                                </div>
                                <div class="hero-inner-container">
                                    <div class="hero-content">
                                        <div class="hero-content-main">
                                            <h2><?php the_sub_field('title_hero'); ?></h2>
                                            <div class="pre-heading"><?php the_sub_field('description_hero'); ?></div>
                                            <?php
                                            if ($link_banner):
                                                ?>
                                                <a class="main-button"
                                                   href="<?php echo esc_url($link_banner); ?>">
                                                    <?php _e('FIND OUT MORE', 'cabling'); ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <?php
                    while (have_posts()) :
                        the_post();

                        get_template_part('template-parts/content', 'page');

                    endwhile; // End of the loop.
                    ?>
                </div><!-- .col -->
            </div><!-- .row -->

        </div>
    </main><!-- #main -->
<?php
get_footer();
