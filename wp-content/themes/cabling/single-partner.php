<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();
$partner_type = get_field('partner_contact');
$solution_contact = get_field('solution_contact');
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">	
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
					<span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
					<?php bcn_display(); ?>
				</div>
				<?php 
				cabling_show_back_btn(); 
				?>
				         <div style="width: 700px; height: 120px;"> <?php 
 
	$my_page_title = get_post_meta( get_the_ID(), 'page_header', true);
    $my_intro_text = get_post_meta( get_the_ID(), 'intro_text', true);
    
	
 
	if( ! empty( $my_page_title ) ) {
		echo '<h1>' . $my_page_title . '</h1>';
        echo '<p>' . $my_intro_text . '</p>'; 
       
		                        	}
 
						?>
				</div>              
				<div> <?php echo "</br></br>"?> </div>
				<div class="row">
					<div class="col col-sm-6 col-md-8 col-lg-8">
					<?php
					if ( empty($partner_type) ) {
						while ( have_posts() ) :
							the_post();
							
							get_template_part( 'template-parts/content', get_post_type() );
						endwhile; // End of the loop.
					}else{
						$args = array(
							'post_type' => 'contact_worldwide',
							'posts_per_page' => -1,
							'orderby' => 'title',
							'order' => 'ASC'
						);
						$contries = new WP_Query($args);

						// partner filter
						if($contries->have_posts())
						{
							echo '<div class="product-filter" style="overflow: hidden;">';
								echo '<div class="parner-filter filter-item">';
									echo '<select name="partner-filter" class="browser-default custom-select">';
									echo '<option value="">' . __('All Countries','cabling') . '</option>';
									while ($contries->have_posts()) {
										$contries->the_post();
										echo '<option value="' . get_post_field( 'post_name', get_the_ID() ) . '">' . get_the_title() . '</option>';
									}
									echo '</select>';
								echo '</div>';
							echo '</div>';						
						}
						wp_reset_postdata(  );

						$contact_args = array(
							'post_type' => 'datwyler_contact',
							'posts_per_page' => -1,
							//'fields' => 'ids',
							//'orderby'=>'menu_order',
							'orderby'=>'title',
                            'order' => 'ASC',
							'tax_query' => array(
								array(
									'taxonomy' => 'contact_type',
									'field' => 'term_id',
									'terms' => array($partner_type, $solution_contact)
								)
							),
						);
						$contacts = new WP_Query($contact_args);

						// all partner items
						if($contacts->have_posts())
						{
							echo '<div class="parner-section woocommerce">';
							while ($contacts->have_posts()) {
								$contacts->the_post();
								$contact_id = get_the_ID();

								$image_id = get_field('image');
								$country_ids = get_field('contact_country');

								$country_class = '';
								foreach ($country_ids as $c) {
									$country_class .= get_post_field( 'post_name', $c ) . ' ';
								}
								
								ob_start(); ?>
                                <?php //if($image_id): ?>
                                    <strong><?php echo get_post_meta( $contact_id, 'first_name', true); ?></strong><br>
                                <?php //endif; ?>
                                <?php if($org = get_post_meta( $contact_id, 'job_title', true)): ?>
									<strong><?php echo $org; ?></strong><br>
								<?php endif; ?>
								<?php if($position = get_post_meta( $contact_id, 'position', true)): ?>
									<span><?php echo $position; ?></span><br>
								<?php endif; ?>
								<?php if($address = get_post_meta( $contact_id, 'address', true)): ?>
									<span><?php echo $address; ?></span><br>
								<?php endif; ?>

								<?php
                                if( $zipcode = get_post_meta( $contact_id, 'zipcode', true) ) {
                                    $city = get_post_meta($contact_id, 'city', true);
                                    $country = get_post_meta($contact_id, 'country', true);
                                    printf(__('<span>%s %s</span><br>'), $zipcode, $city);
                                    printf(__('<span>%s</span><br>'), $country);
                                }
                                if($description = get_post_meta( $contact_id, 'description', true)): ?>
                                    <span><?php echo $description; ?></span><br>
                                <?php endif;
								$organization = ob_get_clean();
								
								ob_start(); ?>
								<?php if($phone = get_post_meta( $contact_id, 'phone', true)): ?>
									<span><?php printf(__('T %s', 'cabling'), $phone) ?></span>
								<?php endif; ?>		
								<?php if($fax = get_post_meta( $contact_id, 'fax', true)): ?>
									<span><?php printf(__('F %s', 'cabling'), $fax) ?></span>
								<?php endif; ?>		
								<?php if($mobile = get_post_meta( $contact_id, 'mobile', true)): ?>
									<span><?php printf(__('M %s', 'cabling'), $mobile) ?></span>
								<?php endif; ?>									
								<span>
								<a href="mailto:<?php echo get_post_meta( $contact_id, 'email', true) ?>">
									<?php echo get_post_meta( $contact_id, 'email', true) ?>
								</a>
                                <a href="mailto:<?php echo get_post_meta( $contact_id, 'email_2', true) ?>">
									<?php echo get_post_meta( $contact_id, 'email_2', true) ?>
								</a>
								</span>								
								<?php if($www = get_post_meta( $contact_id, 'www', true)): ?>
									<span>
									<a target="_blank" href="<?php echo esc_url($www) ?>">
										<?php echo $www ?>
									</a>
									</span>
								<?php endif; 
								$contact_info = ob_get_clean();
								
								echo '<div class="row pb-3 mb-3 border-bottom border-dark product-item contact-item '. $country_class .'">';
								echo '<div class="company-logo col-xs-12 col-md-3">'. wp_get_attachment_image( $image_id, 'full') .'</div>';
								echo '<div class="company-name col-xs-12 col-md-6">' . $organization . '</div>';
								echo '<div class="company-info col-xs-12 col-md-3">' . $contact_info . '</div>';
								echo '</div>';
							}
							echo '</div>';
						}

						wp_reset_postdata(  );

					}					
					?>		
					</div><!-- .col -->
				</div><!-- .row -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
