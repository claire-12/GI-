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
$termchildren = get_term_children( $term->term_id, $term->taxonomy );
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">
		<div class="container">
            <div class="cabling-breadcrumb woocommerce-breadcrumb">
                <span class="bc-parent"><?php _e('You Are Here: ','cabling') ?></span>					
                <?php bcn_display(); ?>
            </div>
			<a href="<?php echo home_url('/service/'); ?>" class="backbutton box-shadow"><?php _e('Back to: Services','cabling'); ?></a>
			<div class="row">
				<div class="col-md-12">					
                    <header class="page-header">
                        <!-- <?php if( empty($termchildren)):  ?>
                        <h1 class="page-title"><?php echo $term->name; ?></h1>
                        <?php endif; ?> -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="cat-desc"><?php echo get_field('_cat_description', $term) ?></div>
                            </div>
                            <div class="col-md-4">
                                <?php if( !empty($termchildren)):  ?>
                                <?php  
                                    $cat = get_term_by( 'id', $termchildren[0], $term->taxonomy );
                                    $imge_id = get_field('_cat_image', $cat); 
                                ?>
                                <div class="row boxes-menu">
                                    <div class="box-column col-12">
                                        <a class="box-shadow" href="<?php echo get_term_link( $cat ); ?>" title="<?php echo $cat->name ?>">
                                            <div class="img hidden-xs" style="background-image: url('<?php echo $imge_id ? wp_get_attachment_image_url( $imge_id, 'full' ) : ''; ?>');"></div>
                                            <div class="bottom_link">
                                                <span><?php echo $cat->name ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </header><!-- .page-header -->
                    <?php if( empty($termchildren)):  ?>
                    <article id="project-list">
                        <div class="row ">
                            <div class="col-l col-md-8">
                                <div class="row flex-column boxes-menu">
                                    <?php
                                    /* Start the Loop */
                                    while ( have_posts() ) : the_post(); ?>
                                        <?php $country_list = get_the_terms( get_the_ID(), 'reference_country' ); ?>
                                        <div class="col-md-12 mt-2">
                                            <div class="row row-divisor pb-4 mb-3 mb-5">
                                                <div class="col-md-4 p-0">
                                                    <?php if(has_post_thumbnail()): ?>
                                                    <img class="img-fluid" src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>"> 
                                                    <?php endif; ?>
                                                </div><!-- .col -->
                                                <div class="col-md-5">
                                                    <a href="<?php the_permalink(); ?>" style="color:#009fb4; font-size: 20px;"><?php the_title(); ?></a>
                                                    <div class="d-block  mt-1">
                                                        <p class="tag-reference">
                                                        <?php echo $country_list ? join(', ', wp_list_pluck($country_list, 'name')) : ''; ?>
                                                        </p>
                                                    </div>
                                                </div><!-- .col -->
                                                <div class="offset-md-2 col-md-1 text-align-right">
                                                    <div class="ml-2 btn-arrow"  class="bottom_link">
                                                    <i style="font-size:25px;font-weight:bold;color:#FFF; " class="fa fa-angle-right"></i>
                                                        <a href="<?php the_permalink(); ?>" style="color:#009fb4; font-size: 20px;">	</a>
                                                    </div>
                                                </div><!-- .col -->
                                            </div><!-- .row -->
                                        </div><!-- .col -->
                                    <?php
                                    endwhile; 

                                    the_posts_navigation();?>
                                </div><!-- .row -->
                            </div><!-- .col -->
                        </div><!-- .row -->
                    </article>
                    <?php endif; ?>
				</div>
			</div>			
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
