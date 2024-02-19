<?php
/**
 * Template Name: Register
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
		<div class="container">
			<?php
			while ( have_posts() ) :
				the_post(); ?>

				<div class="entry-content">
					<?php the_content(); ?>
					<?php get_template_part( 'template-parts/content', 'register' ); ?>
				</div><!-- .entry-content -->
				<?php
			endwhile; // End of the loop.
			?>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
