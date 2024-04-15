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
?>

	<div id="primary" class="content-area">
        <main id="main" class="site-main">
            <?php get_template_part( 'template-parts/page', 'header' ); ?>
			<div class="container">
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
