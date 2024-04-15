<?php
/*
    * Template Name: Selected Case Template
    * Template Post Type: application
*/

get_header();
$parent_args = array(
	'post_type'      => get_post_type(),
	'posts_per_page' => -1,
    'post_parent'    => get_the_ID(),
    'orderby'    => 'menu_order',
    'order'    => 'ASC',
);
$parents = get_posts( $parent_args );
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">				
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You are here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				<?php cabling_show_back_btn(); ?>
				<div class="row">
					<div class="col col-md-8">
						<?php
						while ( have_posts() ) :
							the_post();
							
							get_template_part( 'template-parts/content', get_post_type() );
						
						endwhile;//End of the loop.					
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