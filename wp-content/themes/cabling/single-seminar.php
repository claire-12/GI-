<?php get_header(); ?>
    
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
			<div class="row">
				<div class="d-block  mt-3">
					<strong class="ml-3"><?php the_field('location'); ?></strong>
				</div>
			</div>
			<div class="row mt-4">
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

			<div class="row">
				<div class="col-md-8">
					<div id="seminar-list-wrapper">
						<table>
							<thead>
								<tr>
									<th>Title</th>
									<th>Place</th>
									<th>Vacancies</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Copper data cable systems</td>
									<td>	Leipzig / Halle -Germany-</td>
									<td><span class="vacancies-available">&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
									<td>28.04.2020</td>
									<td><span class="glyphicon glyphicon-chevron-right"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div><!-- .col -->

			</div><!-- .row -->
			
			</div><!-- .container  -->
		</main><!-- #main -->
	</div><!-- #primary -->



<?php get_footer(); ?>

