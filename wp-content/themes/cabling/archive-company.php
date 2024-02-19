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
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>

<h1 class="archive-title pt-2"><?php _e('Datwyler IT Infra','cabling');?></h1>
				<p><?php _e('Datwyler IT Infra is an international company with headquarters in Switzerland and affiliates in Europe, the Middle East and Asia. Datwyler enables organisations around the world to run their IT/OT infrastructure seamlessly and scale their business with ease.<br><br> 
The well-established company operates on the market as a provider of innovative system solutions, products and services for data centres, fibre networks and intelligent buildings, as well as acting as a subcontractor or general contractor covering the entire value-added chain. This is founded on Datwyler’s substantial expertise in the development and manufacture of the requisite products and solutions, the company’s project experience, global presence and internationally established partner network.<br><br>  
Datwyler IT Infra was founded in 1915 and employs a workforce of around 1000 around the world.


','cabling');?></p>

				<?php if ( have_posts() ) : ?>
					<div class="row boxes-menu">
						<?php
						/* Start the Loop */
						while ( have_posts() ) :
							the_post(); ?>
							<div class="box-column col-sm-6 col-md-3 ">
								<a class="box-shadow" href="<?php echo !empty( get_field('job_link') ) ? get_field('job_link') : get_the_permalink(); ?>" title="<?php the_title(); ?>">
									<div class="img hidden-xs" style="background-image: url('<?php echo get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ); ?>');"></div>
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

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
