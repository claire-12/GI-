<?php
/**
 * Template Name: Training Registration
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
$training_id = isset($_GET['data']) ? base64_decode($_GET['data']) : 0;
get_header();
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				<?php cabling_show_back_btn(); ?>
				
				<div class="row">
					<div class="col-sm-12 col-md-8">			
					<?php if ( $training_id ): ?>
						<h2 class="page-title h2"><?php _e('Seminar reservation','cabling') ?></h2>
						<div class="h4"><?php _e('Registration for the seminar:','cabling') ?></div>
						<div class="h2"><?php echo get_the_title( $training_id ); ?></div>
						<div class="h4">
							<?php echo get_post_meta( $training_id, '_training_place', true ); ?>
							<span> - </span>
							<?php echo get_post_meta( $training_id, '_training_date', true ); ?>
						</div>
						<form name="pre-training-data">
							<input type="hidden" name="train-id" value="<?php echo $training_id; ?>" >
							<input type="hidden" name="train-title" value="<?php echo get_the_title( $training_id ); ?>" >
							<input type="hidden" name="train-place" value="<?php echo get_post_meta( $training_id, '_training_place', true ); ?>" >
							<input type="hidden" name="train-date" value="<?php echo get_post_meta( $training_id, '_training_date', true ); ?>" >
						</form>
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