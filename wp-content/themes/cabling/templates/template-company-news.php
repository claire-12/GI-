<?php
/**
 * Template Name: Company News
 * Template Post Type: Company
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */
get_header();

//$country_name = 'International';
$country = cabling_get_country();
if( isset($country['name']) )
{
	$coutry_term = get_term_by('name', $country['name'], 'filter_country');
	if($coutry_term)
	{
		$country_name = $coutry_term->name;
		if(!empty($coutry_term->parent)){
			$region = get_term_by('term_id', $coutry_term->parent, 'filter_country');
			$region_name = $region->name;
		}
	}
}
//var_dump($region_name);
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				
				<?php 
				cabling_show_back_btn(); ?>
				<div class="row">
					<div class="col col-md-8 col-lg-8">
					<?php the_title( '<h1>', '</h1>', true ); ?>
					<?php
					while ( have_posts() ) :
						the_post();
						
						get_template_part( 'template-parts/content', get_post_type() );
					endwhile; // End of the loop.
					?>
					<?php 
						global $wp_query;
						
						$paged = isset($_GET['num']) ? (int)$_GET['num'] : 1;
						$number = 8;
						
						$args = array(
						    'post_type' => 'company_news',
						    'posts_per_page' => $number,
						    'paged' => $paged,
							'tax_query' => array(
								'relation' => 'OR'
							)
						);
						//get by country
						if( !empty($coutry_term) )
							$args['tax_query'][] = array(
								'taxonomy' => 'filter_country',
								'field' => 'name',
								'terms' => $coutry_term->name,
							);
						
						//get by region
						if( !empty($region_name) )
							$args['tax_query'][] = array(
								'taxonomy' => 'filter_country',
								'field' => 'name',
								'terms' => $region_name,
							);
						if(empty($coutry_term) && empty($region))
							$args['tax_query'][] = array(
								'taxonomy' => 'filter_country',
								'field' => 'name',
								'terms' => 'International',
							);

						$wp_query = new WP_Query( $args );
						if ( $wp_query->have_posts() ): ?>							
							<div class="company-news row flex-column">							
								<?php while ( $wp_query->have_posts() ): the_post(); global $post; ?>
								<?php 
									$terms = get_the_terms( get_the_ID(), 'filter_country' );
									if ($terms) {
										foreach($terms as $term) {
											$country_posts[] = $term->slug;
										} 
									}
								?>
								<div class="col-12 <?php echo isset($country_posts) ? implode(' ', $country_posts) : ''; ?>">
									<div class="news-item">
										<div class="row">
											<div class="col-md-4">
												<?php echo get_the_post_thumbnail( get_the_ID(), '220x150' ); ?>
											</div><!-- .col -->
											<div class="col-md-6">
												<div class="date-meta"><?php echo get_the_date('d/m/Y') ?></div>
												<a class="new-title" href="<?php the_permalink(); ?>">
													<?php the_title(); ?>														
												</a>
												<div class="excerpt">
                                                    <?php echo $post->post_excerpt; ?>
												</div>
											</div><!-- .col -->
											<div class="col-md-2 text-align-right">
												<div class="ml-2 btn-arrow"  class="bottom_link">
												<i style="font-size:25px;font-weight:bold;color:#FFF; " class="fa fa-angle-right"></i>
													<a href="<?php the_permalink(); ?>" style="color:#009fb4; font-size: 20px;">	</a>
												</div>
											</div><!-- .col -->
										</div><!-- .row -->
									</div><!-- .item -->
								</div><!-- .col -->
								<?php endwhile; ?>							
							</div>
							<?php 
								$total_pages = $wp_query->max_num_pages;
								$current_page = max(1, $paged);
							?>
							<?php if ($total_pages > 1): global $wp; ?>
							<div class="wp-pagenavi" role="navigation">
								<?php for ($i=1; $i <= $total_pages; $i++) { 
									$url = home_url(add_query_arg(array('num' => $i), $wp->request));
									
									if ( $current_page != $i)
										echo '<a class="page larger" href="'. esc_url( $url ) .'">'. $i .'</a>';
									else
										echo '<span class="page larger current">'. $i .'</span>';
								
								} ?>
							</div>	
							<?php endif ?>
							<?php 						    
						else :

							get_template_part( 'template-parts/content', 'none' );

						endif;
						wp_reset_postdata();
					?>
					</div><!-- .col -->
				</div><!-- .row -->
				<?php
					
				
				?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
