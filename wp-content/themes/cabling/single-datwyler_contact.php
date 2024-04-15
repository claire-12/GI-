<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();
$class = get_post_type() === 'contact_worldwide' ? 'col-md-12' : 'col-md-8';
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="container">				
				<div class="cabling-breadcrumb woocommerce-breadcrumb">
				   <span class="bc-parent"><?php _e('You are here: ','cabling') ?></span>
				   <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="<?php _e('Contacts','cabling') ?>" href="<?php echo home_url('/contact/'); ?>" class="post post-contact-archive"><span property="name"><?php _e('Contacts','cabling') ?></span></a><meta property="position" content="1"></span>
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
							
                            $contact_id = get_the_ID(); ?>
                            <div class="row pb-3 contact-item contact-<?php echo $contact_id ?>">
                                <div class="col-12 mb-3">
                                <?php if($image_id = get_field('image', $contact_id)): ?>
                                    <?php echo wp_get_attachment_image( $image_id, 'full'); ?>
                                <?php else: ?>
                                    <a href="mailto:<?php echo get_post_meta( $contact_id, 'email', true) ?>">
                                        <?php echo get_the_title(); ?>
                                    </a>
                                <?php endif; ?>
                                </div>
                                <!--<div class="col-12 mb-3">
                                    <?php /*if($image_id): */?>
                                        <strong><?php /*echo get_post_meta( $contact_id, 'first_name', true); */?></strong><br>
                                    <?php /*endif; */?>
                                    <?php /*if($position = get_post_meta( $contact_id, 'position', true)): */?>
                                        <span><?php /*echo $position; */?></span><br>
                                    <?php /*endif; */?>
                                    <?php /*if($address = get_post_meta( $contact_id, 'address', true)): */?>
                                        <span><?php /*echo $address; */?></span><br>
                                    <?php /*endif; */?>
                                    <?php /*if($zipcode = get_post_meta( $contact_id, 'zipcode', true)): */?>
                                        <?php
/*                                        $city = get_post_meta( $contact_id, 'city', true);
                                        $city = !empty($city) ? $city : '';
                                        $region = get_post_meta( $contact_id, 'region', true);
                                        $region = !empty($region) ? $region : '';
                                        */?>
                                        <span><?php /*printf(__('%s %s %s'), $zipcode, $city, $region ) */?></span><br>
                                    <?php /*endif; */?>
                                    <?php /*if($country_ct = get_post_meta( $contact_id, 'country', true)): */?>
                                        <span><?php /*echo $country_ct */?></span><br>
                                    <?php /*endif; */?>
                                </div>-->
                                <div class="col-12 mb-3">
                                    <div class="more-if">
                                        <?php if($phone = get_field('phone')): ?>
                                            <span><?php printf(__('T %s', 'cabling'), $phone) ?></span>
                                        <?php endif; ?>		
                                        <?php if($fax = get_field('fax')): ?>
                                            <span><?php printf(__('F %s', 'cabling'), $fax) ?></span>
                                        <?php endif; ?>		
                                        <?php if($mobile = get_field('mobile')): ?>
                                            <span><?php printf(__('M %s', 'cabling'), $mobile) ?></span>
                                        <?php endif; ?>									
                                        <?php if($image_id): ?>
                                            <span>
                                            <a href="mailto:<?php echo get_post_meta( $contact_id, 'email', true) ?>">
                                                <?php echo get_post_meta( $contact_id, 'email', true) ?>
                                            </a>
                                            </span><br>
                                            <span>
                                            <a href="mailto:<?php echo get_post_meta( $contact_id, 'email_2', true) ?>">
                                                <?php echo get_post_meta( $contact_id, 'email_2', true) ?>
                                            </a>
                                            </span>
                                        <?php endif; ?>									
                                        <?php if($www = get_post_meta( $contact_id, 'www', true)): ?>
                                            <span>
                                            <a href="<?php echo $www ?>">
                                                <?php echo $www ?>
                                            </a>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>	                	
                            </div>
                            <?php
						endwhile; // End of the loop.					
						?>	
					</div><!-- .col -->
				</div><!-- .row -->				
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>