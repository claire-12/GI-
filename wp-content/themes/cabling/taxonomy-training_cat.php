<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */

get_header();

$term = get_queried_object();
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
				<div class="col-md-8">					
				<header class="page-header">
					<h1 class="page-title"><?php echo $term->name; ?></h1>
					<div class="row">
						<div class="col-md-8">
							<div class="cat-desc"><?php echo get_field('_cat_description', $term) ?></div>
						</div>
						<div class="col-md-4">
							<div class="f-image">
								<?php $img_id = get_field('_cat_image', $term) ?>
								<?php echo $img_id ? wp_get_attachment_image( $img_id, 'full' ) : ''; ?>
							</div>
						</div>
					</div>
				</header><!-- .page-header -->
				<?php if ( have_posts() ) : ?>				
					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th><?php _e('Title','cabling') ?></th>
								<th><?php _e('Place','cabling') ?></th>
								<th><?php _e('Vacancies','cabling') ?></th>
								<th><?php _e('Date','cabling') ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$count = 0;
							/* Start the Loop */
							while ( have_posts() ) :
								the_post(); ?>
								<?php if( 'Yes' !== get_post_meta(get_the_ID(), 'is_training_content', true)): $count++; ?>
								<tr>
									<td class="train-title">
										<a class="new-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</td>
									<td class="train-place"><?php echo get_field('_training_place') ?></td>
									<td class="train-vacan"><?php echo get_field('_training_vacancies') ?></td>
									<td class="train-date">		
										<div class="text-align-right">
											<span><?php echo get_field('_training_date') ?></span>
											<div class="btn-arrow"  class="bottom_link">
												<i style="font-size:25px;font-weight:bold;color:#FFF; " class="fa fa-angle-right"></i>
												<a href="<?php the_permalink(); ?>" style="color:#009fb4; font-size: 20px;">	</a>
											</div>
										</div><!-- .col -->	
									</td>
								</tr>
								<?php
								endif;
							endwhile; ?>
						</tbody>
					</table>
					<?php if( !$count ) echo '<style>.table{display:none}</style>'; ?>
					<?php
					//the_posts_navigation();

				else :

					//echo __('(Sorry, there are no available entries)','cabling');

				endif;
				?>
				</div>
			</div>			
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
