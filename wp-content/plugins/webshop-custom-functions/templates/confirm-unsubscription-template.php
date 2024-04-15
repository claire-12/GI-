<?php
/**
 * The template for displaying all pages
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
$title = get_field('custom_title');
if (empty($title)){
    $title = get_the_title();
}
if (isset($_GET['unsubscription']) && isset($_GET['data'])) {
    $email = base64_decode(urldecode($_GET['data']));
}
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <?php if ( !get_field('hide_page_title') ): ?>
                <header class="page-header">
                    <h1 class="entry-title"><?php echo $title ?></h1>
                    <p class="entry-sub-title"><?php echo get_field('custom_sub_tittle') ?></p>
                    <?php if (has_post_thumbnail()) cabling_post_thumbnail(); ?>
                </header><!-- .entry-header -->
            <?php endif ?>
			<div class="container">
                <?php if ( !empty(get_field('hide_breadcrumb')) || is_bbpress()): ?>
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>
                    <?php if ( get_query_var( 'bbp_search' ) ): $forums_link = bbp_get_root_slug(); ?>
                    <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage"
                           title="Go to Areas."
                           href="<?php echo home_url($forums_link) ?>"
                            class="archive post-forum-archive">
                            <span property="name">Areas</span>
                        </a><meta property="position" content="1">
                    </span>
                    |
                        <span property="itemListElement" typeof="ListItem">
                            <span property="name" class="post post-forum current-item">Search</span>
                        </span>
                    <?php endif ?>
					<?php bcn_display(); ?>
				</div>
				<?php cabling_show_back_btn(); ?>
                <?php endif ?>

				<div class="row">
					<div class="col-sm-12 col-md-12">
						<?php
						while ( have_posts() ) :
							the_post(); ?>
                            <form method="post">
                                <h5><?php _e('Newsletter Unsubscription', 'cabling'); ?></h5>
                                <div class="mb-3 channel-email">
                                    <input type="email" class="form-control" id="confirm-unsubscriptio-email" name="confirm-unsubscriptio-email"
                                           value="<?php echo $email ?? '' ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <?php wp_nonce_field('confirm-unsubscription-template') ?>
                                    <button type="submit" class="btn btn-primary"><?php _e('Unsubscription', 'cabling'); ?></button>
                                </div>
                            </form>
                        <?php
						endwhile; // End of the loop.
						?>
					</div><!-- .col -->
				</div><!-- .row -->

			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
