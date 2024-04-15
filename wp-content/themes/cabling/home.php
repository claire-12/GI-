<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
$current_posts_per_page = 6;
$total_posts = wp_count_posts()->publish;
get_header();
?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php get_template_part('template-parts/page', 'blog'); ?>
            <div class="container">
                <div class="blog-wrap">
                    <?php if (have_posts()) : ?>
                    <div class="blog-breadcrumbs mb-5 d-flex justify-content-between align-items-center">
                        <div class="breadcr"><?php echo __('Home / Blog', 'cabling') ?></div>
                        <div class="blog-filter-order">
                            <form action="" method="get">
                                <select name="order" id="filter-blog-order">
                                    <option value="newest" <?php selected('newest', $_GET['order']) ?>><?php echo __('SORT BY: NEWEST - OLDEST', 'cabling') ?></option>
                                    <option value="oldest" <?php selected('oldest', $_GET['order']) ?>><?php echo __('SORT BY: OLDEST - NEWEST', 'cabling') ?></option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="description text-center mb-5 ">
                        Nonsequos aut exceritiam, corruptas plam experaecabo. Ut essente mi, temporepudit am <br>
                        ullesto te veligen digenia et alis ipsaecestium a que pe est modit everibus, occae quatinumquis
                        <br>
                        eic teceraero enisit od exerspe lluptassimus et faccumqui
                    </div>
                    <div class="post-wrapper-inner">
                        <div id="blog-wrapper" class="row post-wrapper">
                            <?php
                            /* Start the Loop */
                            while (have_posts()) :
                                the_post();
                                get_template_part('template-parts/ajax/content', get_post_type());
                            endwhile; ?>
                        </div>
                        <?php
                        else :

                            get_template_part('template-parts/content', 'none');

                        endif;
                        ?>
                    </div>
                    <div class="ajax-pagination text-center mb-5">
                        <div class="number-posts"><?php printf(__('Showing %s of %s Blog Posts', 'cabling'), $current_posts_per_page, $total_posts) ?></div>
                        <button class="block-button"
                                id="load-post-ajax"><?php echo __('Load more', 'cabling') ?></button>
                        <form action="" id="blog-filter">
                            <input type="hidden" name="paged" value="1">
                            <input type="hidden" name="post_type" value="post">
                            <input type="hidden" name="posts_per_page" value="6">
                            <input type="hidden" name="order" value="<?php echo $_GET['order'] ?? 'newest' ?>">
                        </form>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
