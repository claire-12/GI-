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
                <div class="blog-wrap">
                    <h1 class="archive-title pt-2"><?php printf(__('Blog - %s', 'cabling'), get_queried_object()->name) ?></h1>
                    <?php if (have_posts()) : ?>
                        <div class="row">
                            <?php
                            /* Start the Loop */
                            while (have_posts()) :
                                the_post(); ?>
                                <div class="col-sm-12 col-md-6 col-lg-4">
                                    <div class="blog-item">
                                        <div class="feature-img">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                <?php if (has_post_thumbnail()) the_post_thumbnail(); ?>
                                            </a>
                                        </div>
                                        <div class="blog-category">
                                            <?php
                                            $post_id = get_the_ID(); // Assuming you are inside the loop

                                            // Get the categories for the current post
                                            $categories = get_the_category($post_id);

                                            if ($categories) {

                                                echo 'Categories: ';
                                                $list = array();
                                                foreach ($categories as $category) {
                                                    $list[] = '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                                                }
                                                echo implode(', ', $list);
                                            }
                                            ?>

                                        </div>
                                        <h4>
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <div class="description">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;

                            the_posts_navigation(); ?>
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
