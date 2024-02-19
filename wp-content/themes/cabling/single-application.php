<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();
$cats = get_the_terms(get_the_ID(), 'service_cat');

$parent_args = array(
	'post_type'      => get_post_type(),
	'posts_per_page' => -1,
    'post_parent'    => get_the_ID(),
    'orderby'=>'menu_order',
    'order' => 'ASC',
);

$study_args = $parent_args;

if( $cats ){
    $parent_args['tax_query'] = array(
        array( 
            'taxonomy' => $cats[0]->taxonomy,
            'field' => 'term_id',
            'terms' => $cats[0]->term_id,
            'operator' => 'NOT IN'
        )
    );
    $study_args['tax_query'] = array(
        array( 
            'taxonomy' => $cats[0]->taxonomy,
            'field' => 'term_id',
            'terms' => $cats[0]->term_id,
        )
    );
    $case_studies = get_posts($study_args);
}

$parents = get_posts( $parent_args );
$references = get_field('service_references');
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
					<div class="col col-md-8">
						<?php
						while ( have_posts() ) :
							the_post();
							
							get_template_part( 'template-parts/content', get_post_type() );
						
						endwhile;//End of the loop.					
                        ?>
                        <?php if( !empty($references) ): ?>
                        <div class="application-items">
                            <?php foreach( $references as $reference): ?>
                                <?php $countries = get_the_terms( $reference, 'reference_country' ); ?>
                                <div class="news-item">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php if(get_the_post_thumbnail_url( $reference, 'full' )): ?>
                                            <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url( $reference, 'full' ); ?>"> 
                                            <?php endif; ?>
                                        </div><!-- .col -->
                                        <div class="col-md-5">
                                            <a href="<?php echo get_the_permalink($reference); ?>" style="color:#009fb4; font-size: 20px;"><?php echo get_the_title($reference); ?></a>
                                            <div class="d-block  mt-1">
                                                <p class="tag-reference">
                                                <?php echo $countries ? join(', ', wp_list_pluck($countries, 'name')) : ''; ?>
                                                </p>
                                            </div>
                                        </div><!-- .col -->
                                        <div class="col-md-3 text-align-right">
                                            <div class="ml-2 btn-arrow"  class="bottom_link">
                                            <i style="font-size:25px;font-weight:bold;color:#FFF; " class="fa fa-angle-right"></i>
                                                <a href="<?php echo get_the_permalink($reference); ?>" style="color:#009fb4; font-size: 20px;">	</a>
                                            </div>
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .item -->
                            <?php endforeach; ?>
                        </div><!-- .row -->
                        <?php endif; ?>
                        <?php if( !empty($case_studies)): ?>
						<div class="case_studies-items">
                            <?php foreach( $case_studies as $case): ?>
                                <?php $country_list = get_the_terms( $case->ID, 'reference_country' ); ?>
                                <div class="news-item">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php if(get_the_post_thumbnail_url( $case->ID, 'full' )): ?>
                                            <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url( $case->ID, 'full' ); ?>"> 
                                            <?php endif; ?>
                                        </div><!-- .col -->
                                        <div class="col-md-5">
                                            <a href="<?php echo get_the_permalink($case->ID); ?>" style="color:#009fb4; font-size: 20px;"><?php echo get_the_title($case->ID); ?></a>
                                            <div class="d-block  mt-1">
                                                <p class="tag-reference">
                                                <?php echo $country_list ? join(', ', wp_list_pluck($country_list, 'name')) : ''; ?>
                                                </p>
                                            </div>
                                        </div><!-- .col -->
                                        <div class="col-md-3 text-align-right">
                                            <div class="ml-2 btn-arrow"  class="bottom_link">
                                            <i style="font-size:25px;font-weight:bold;color:#FFF; " class="fa fa-angle-right"></i>
                                                <a href="<?php echo get_the_permalink($case->ID); ?>" style="color:#009fb4; font-size: 20px;">	</a>
                                            </div>
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .item -->
                            <?php endforeach; ?>
                        </div><!-- .row -->  
                        <?php endif; ?>                    
                    </div><!-- .col -->		
                    <div class="col col-md-12">
                    <?php if ( $parents ): ?>
                        <div class="row boxes-menu">
                            <?php foreach ($parents as $post): ?>
                                <div class="box-column col-sm-6 col-md-3 ">
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
                    </div>			
				</div><!-- .row -->				
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>