<?php
/**
 * Template Name: Sevice Custom Page
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
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php //bcn_display(); ?>				
                    <!-- Breadcrumb NavXT 6.6.0 -->
                    <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage" title="Services" href="<?php echo home_url('/service/') ?>" class="post post-service-archive">
                        <span property="name"><?php _e('Services','cabling') ?></span></a>
                        <meta property="position" content="1">
                    </span>
                    <?php echo " | "; ?>
                    <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage" title="Harnessing" href="<?php echo home_url('/service/data-centre-infrastructure-and-data-network-services/') ?>" class="post post-service">
                            <span property="name"><?php _e('Data Centre Infrastructure and Data Network Services','cabling') ?></span>
                        </a>
                        <meta property="position" content="2">
                    </span>
                    <?php echo " | "; ?>
                    <span property="itemListElement" typeof="ListItem">
                        <span property="name" class="post post-service current-item">
                            <?php echo get_the_title(); ?>
                        </span>
                        <meta property="url" content="<?php echo get_the_permalink(); ?>">
                        <meta property="position" content="3">
                    </span>
                </div>
				<?php cabling_show_back_btn(); ?>
				<div class="row">
					<div class="col-sm-12 col-md-12">
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
