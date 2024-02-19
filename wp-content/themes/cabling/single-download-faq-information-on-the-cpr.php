<?php
/**
 * Template Name: Single Download
 * Template Post Name: Download
 *
 * The template for displaying all single dowload posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cabling
 */

get_header();

?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="container">
            <div class="cabling-breadcrumb woocommerce-breadcrumb">
                <span class="bc-parent"><?php _e( 'You Are Here: ', 'cabling' ) ?></span>
				<?php bcn_display(); ?>
            </div>
			<?php
			cabling_show_back_btn();
			?>
            <div class="row">
                <div class="col-md-8">
					<?php the_content(); ?>
                    <div id="accordion">
		                <?php
                        echo cabling_get_download_faq();
		                ?>
                    </div>
                </div>
                <div class="mt-3 box-column col-sm-6 col-md-4">
                    <a style="text-decoration: none;" class="box-shadow" href="<?php echo get_site_url(); ?>/training/"
                       title="<?php the_title(); ?>">
                        <div class="img hidden-xs img-seminars-link"
                             style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/seminare.png');"></div>
                        <div class="bottom_link">
                            <span><?php _e( 'Datwyler Training / Seminars', 'cabling' ) ?></span>
                        </div>
                    </a>
                </div>
            </div><!-- .row -->
        </div><!-- .container -->


    </main><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();

