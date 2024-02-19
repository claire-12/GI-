<?php
/**
 * The template for displaying archive event pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main-archive-events" class="site-main">
			<div class="container">
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				<h1 class="archive-title pt-2"><?php _e('Fairs, Roadshows, Conferences','cabling') ?></h1>
				<div class="p-2 mr-0 ml-0 row row-divisor"></div>	
				<?php if ( have_posts() ) : ?>
					<div class="row ml-1">
						<div class="col-md-8">
							<div class="row flex-column boxes-menu">
								<?php
								/* Start the Loop */
								while ( have_posts() ) : the_post(); ?>
								
								<?php
									
									# Get current date
									$todayDate = date('d/m/Y');
									$todayDate = explode("/", $todayDate);
									$day = $todayDate[0];
									$month = $todayDate[1];
									$year = $todayDate[2];
									
									# Get postDate
									if(get_field('end_date')){
										$postDate = get_field('end_date');
									} else {
										$postDate = get_field('initial_date');
									}
									
									$postDate = explode("/", $postDate);
									$pDay = $postDate[0];
									$pMonth = $postDate[1];
									$pYear = $postDate[2];
									
									if($pYear < $year)
										continue;
									
									if($pYear == $year && $pMonth < $month)
										continue;
									
									if($pYear == $year && $pMonth == $month && $pDay < $day)
										continue;
										
									?>
								<div class="col-md-10 mt-2 ">
									<div class="row row-divisor pb-4 mb-3 mb-5">
										<div class="col-md-4 p-0">
											<img class="img-fluid" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'post-thumbnail' ); ?>"> 
										</div><!-- .col -->
										<div class="col-md-5">
											<div class="d-flex" style="font-size: 14px;">
												<?php if( get_field('initial_date')):?>
												<span><?php the_field('initial_date') ; ?><?php echo "&nbsp" ?></span>
												<?php endif; ?>
												<?php if( get_field('end_date')):?> 
												<span>-<?php echo "&nbsp" ?><?php the_field('end_date') ; ?></span>
												<?php endif; ?>
											</div>

											<a href="<?php the_permalink(); ?>" style="color:#009fb4; font-size: 20px;"><?php the_title(); ?></a>
											<div class="d-block  mt-1">
												<strong class=""><?php the_field('location'); ?></strong>
											</div>
										</div><!-- .col -->
										<div class="offset-md-2 col-md-1 text-align-right">
											<a href="<?php the_permalink(); ?>" style="color:#009fb4; font-size: 20px;">
												<div class="ml-2 btn-arrow"  class="bottom_link">												
													<i style="font-size:25px;font-weight:bold;color:#FFF; " class="fa fa-angle-right"></i>	
												</div>
											</a>
										</div><!-- .col -->
									</div><!-- .row -->
								</div><!-- .col -->
									<?php
									
								endwhile; 

								//the_posts_navigation();?>
							</div><!-- .row -->
						</div><!-- .col -->
						
						<div class="mt-3 box-column col-sm-6 col-md-4">
							<a style="text-decoration: none;"class="box-shadow" href="<?php echo home_url('/training/'); ?>" title="<?php the_title(); ?>">
								<div class="img hidden-xs img-seminars-link" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/seminare.png');"></div>
								<div class="bottom_link">
									<span><?php _e('Datwyler Training / Seminars', 'cabling');?></span>
								</div>
							</a>
						</div><!-- .col -->
					</div><!-- .row -->
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
