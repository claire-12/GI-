<?php
/**
 * Template Name: Contact single
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
					<?php bcn_display(); ?>
				</div>
				<div class="row">
                    <div class="col-12">
                        <?php if ( !get_field('hide_page_title') ): ?>
                            <header class="entry-header">
                                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                            </header><!-- .entry-header -->
                        <?php endif ?>
                    </div>
					<div class="col-sm-12 col-md-12">
                        <?php
                        $contact = cabling_show_footer_contact();
                        ?>
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <?php if(!empty( $contact[1] )) echo $contact[1]; ?>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <?php if(!empty( $contact[2] )) echo $contact[2]; ?>
                            </div>
                        </div>
                        <div class="contact-link">
                        <?php
                        while ( have_posts() ) :
                            the_post();

                            the_content();

                        endwhile; // End of the loop.
                        ?>
                        </div>
                    </div><!-- .col -->
				</div><!-- .row -->
			
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();