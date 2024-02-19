<?php
/**
 * Template Name: Single Download
 * Template Post Name: Download
 *
 * The template for displaying all single download
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
                    <span class="bc-parent"><?php _e('You Are Here: ', 'cabling') ?></span>
                    <?php bcn_display(); ?>
                </div>
                <?php cabling_show_back_btn(); ?>
                <div class="row">
                    <div class="col-md-12">
                        <?php the_content(); ?>
                    </div>
                </div><!-- .row -->
                <div class="row">
                    <div class="col-md-10">
                        <div id="accordion">
                            <?php
                            $download_types = get_terms(['taxonomy' => 'download_type','hide_empty' => false]);
                            if ($download_types)
                            {
                                $country = cabling_get_country();
                                //var_dump($country['name']);
                                foreach ($download_types as $type)
                                {
                                    cabling_get_download_file($type, get_the_ID(), $country['name']);
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div><!-- .container -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php
get_footer();