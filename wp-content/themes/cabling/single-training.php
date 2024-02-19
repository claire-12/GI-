<?php
/**
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
				<?php	cabling_show_back_btn(); ?>
				<div class="row">
					<div class="col col-md-12 col-lg-12">
					<?php
					while ( have_posts() ) :
					the_post();
					if( 'Yes' == get_post_meta(get_the_ID(), 'is_training_content', true))
						get_template_part( 'template-parts/content' );
					else
						get_template_part( 'template-parts/content', get_post_type() );
					?>		
					</div><!-- .col -->
				</div><!-- .row -->
				<?php
					
				endwhile; // End of the loop.
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
