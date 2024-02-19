<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
get_header();
$terms = get_terms( array( 'taxonomy' => 'training_cat', 'hide_empty' => 0, 'orderby' => 'id' ) );
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				<?php if ( $terms ) : ?>
					<div class="row boxes-menu">
						<?php foreach ($terms as $cat): ?>
						<?php $imge_id = get_field('_cat_image', $cat);  ?>
						<div class="box-column col-sm-6 col-md-3 ">
							<a class="box-shadow" href="<?php echo get_term_link( $cat ); ?>" title="<?php echo $cat->name ?>">
								<div class="img hidden-xs" style="background-image: url('<?php echo $imge_id ? wp_get_attachment_url($imge_id, 'full') : ''; ?>');"></div>
								<div class="bottom_link">
									<span><?php echo $cat->name; ?></span>
								</div>
							</a>
						</div>
						<?php endforeach ?>
					</div>
				<?php
				else :

					//get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
