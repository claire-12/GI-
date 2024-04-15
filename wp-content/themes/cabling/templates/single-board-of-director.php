<?php
/**
 * Template Name: Board of Directors
 * //Template Post Type: Company
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();
$parent_args = array(
    'post_type' => get_post_type(),
    'posts_per_page' => -1,
    'post_parent' => get_the_ID(),
    'orderby' => 'menu_order',
    'order' => 'ASC',
);
$parents = get_posts($parent_args);
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="container">
                <div class="cabling-breadcrumb woocommerce-breadcrumb">
                    <span class="bc-parent"><?php _e('You Are Here: ', 'cabling') ?></span>
                    <?php bcn_display(); ?>
                </div>
                <?php cabling_show_back_btn(); ?>
                <div class="row">
                    <div class="col col-md-12 col-lg-12">
                        <header class="entry-header">
                            <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
                        </header><!-- .entry-header -->
                        <?php if ($parents): ?>
                            <div class="directors">
                                <?php foreach ($parents as $post): ?>
                                    <div class="director-item d-flex">
                                        <div class="image pr-3 max-width-90">
                                            <?php if(has_post_thumbnail($post->ID)) echo get_the_post_thumbnail($post->ID ) ?>
                                        </div>
                                        <div class="director-info w-100 d-flex flex-wrap">
                                            <div class="title">
                                                <b>
                                                <?php if( get_field( 'show_single_page', $post->ID ) ): ?>
                                                    <a href="<?php echo get_the_permalink($post->ID) ?>" title="<?php echo get_the_title($post->ID) ?>">
                                                        <?php echo get_the_title($post->ID) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo get_the_title($post->ID) ?>
                                                <?php endif; ?>
                                                </b>
                                                <div class="degree"><?php echo get_field( 'company-degree', $post->ID ); ?></div>
                                            </div>
                                            <div class="position"><?php echo get_field( 'company-position', $post->ID ); ?></div>
                                            <div class="company"><?php echo get_field( 'company-info', $post->ID ); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>
                    </div><!-- .col -->
                </div><!-- .row -->
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();