<?php
/**
 *
 * Template Name: Single Event
 * Template Post Type: event
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
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
				<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
				<?php bcn_display(); ?>
				</div>
			<div class="row ml-1">
				<?php cabling_show_back_btn();?>
			</div>
			<div class="row ml-1 mb-2">
				<div class="d-flex" style="font-size: 14px;">
					<?php the_field('initial_date'); ?> - <?php the_field('end_date'); ?>
				</div>
			</div>
			<div class="row ml-1">
				<div class="d-flex">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
			<div class="row">
				<div class="d-block  mt-3">
					<strong class="ml-3"><?php the_field('location'); ?></strong>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8">
				<?php
					while ( have_posts() ) :
						the_post();

						the_content();
						// If comments are open or we have at least one comment, load up the comment template.
						//if ( comments_open() || get_comments_number() ) :
						//	comments_template();
						//endif;

					endwhile; // End of the loop.
			?>	
				</div><!-- .col-md-6 -->
			</div><!-- .row -->
			
			</div><!-- .container  -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
