<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();
$args = array(
	'post_type'      => get_post_type(),
	'posts_per_page' => -1,
	'post_parent'    => get_the_ID(),
);
$parents = get_posts( $args );
$class = get_post_type() === 'contact_worldwide' ? 'col-md-12' : 'col-md-8';
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">				
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
				   <span class="bc-parent"><?php _e('You are here: ','cabling') ?></span>
				   <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="<?php _e('Company','cabling') ?>" href="<?php echo home_url('/company/'); ?>" class="post post-company-archive"><span property="name"><?php _e('Company','cabling') ?></span></a><meta property="position" content="1"></span>
				   |
				   <span property="itemListElement" typeof="ListItem">
				      <a property="item" typeof="WebPage" title="<?php _e('Go to Press.','cabling') ?>" href="<?php echo get_permalink(apply_filters( 'wpml_object_id', 1754, 'page', false, ICL_LANGUAGE_CODE )); ?>" class="post post-company_news-archive">
				      	<span property="name"><?php _e('Press','cabling') ?></span>
				      </a>
				      <meta property="position" content="1">
				   </span>
				   | 
				   <span property="itemListElement" typeof="ListItem">
				      <span property="name" class="post post-company_news current-item"><?php echo get_the_title($post->ID); ?></span>
				      <meta property="url" content="<?php echo get_the_permalink($post->ID); ?>">
				      <meta property="position" content="2">
				   </span>
				</div>

				<?php cabling_show_back_btn(); ?>
				<div class="row">
					<div class="col <?php echo $class ?>">
						<?php
						while ( have_posts() ) :
							the_post();
							
							get_template_part( 'template-parts/content', get_post_type() );
						
						endwhile; // End of the loop.					
						?>	
					</div><!-- .col -->
					<?php if ( $parents ): ?>
					<div class="col col-md-4 boxes-menu">
						<?php foreach ($parents as $post): ?>
							<div class="box-column">
								<a class="box-shadow" href="<?php echo get_the_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
									<div class="img hidden-xs" style="background-image: url('<?php echo get_the_post_thumbnail_url( $post->ID, 'post-thumbnail' ); ?>');"></div>
									<div class="bottom_link">
										<span><?php echo get_the_title($post->ID); ?></span>
									</div>
								</a>
							</div>
						<?php endforeach ?>
					</div>	
					<?php endif ?>
				</div><!-- .row -->				
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>