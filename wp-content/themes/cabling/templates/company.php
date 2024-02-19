<?php
/**
 * Template Name: Datwyler Page
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
$args = array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => get_the_ID(),
    'order'          => 'ASC',
    'orderby'        => 'menu_order'
 );


$parents = get_posts( $args );

get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-12">
						
						<?php if ( $parents ): ?>
						<div class="row boxes-menu">
							<?php foreach ($parents as $page): ?>
								<div class="box-column col-sm-6 col-md-3 ">
									<a class="box-shadow" href="<?php echo get_the_permalink($page->ID); ?>" title="<?php echo get_the_title($page->ID); ?>">
										<div class="img hidden-xs" style="background-image: url('<?php echo get_the_post_thumbnail_url( $page->ID, 'post-thumbnail' ); ?>');"></div>
										<div class="bottom_link">
											<span><?php echo get_the_title($page->ID); ?></span>
										</div>
									</a>
								</div>
							<?php endforeach ?>
						</div>	
						<?php endif ?>				
						
					<?php
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content', 'page' );

					endwhile; // End of the loop.
					?>					
					</div><!-- .col -->	
				</div><!-- .row -->
			
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();