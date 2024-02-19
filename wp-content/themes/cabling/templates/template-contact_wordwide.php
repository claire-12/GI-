<?php
/**
 * Template Name: Contact Word Wide 
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
$terms = get_terms(array('taxonomy' => 'worldwide', 'hide_empty' => 0));
get_header();
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
					<div class="col-sm-12 col-md-12">
                        <header class="entry-header">
                            <?php the_content(); ?>
                        </header><!-- .entry-header -->
					<?php if ( $terms ) : ?>
                        <div id="accordion">
                            <?php foreach ($terms as $cat): ?>
                            <div class="card mb-3" style="border: none">
                                <div class="card-header" id="<?php echo $cat->slug; ?>">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#<?php echo $cat->slug; ?>-content" aria-expanded="true" aria-controls="<?php echo $cat->slug; ?>-content">
                                            <?php echo $cat->name; ?>
                                            <svg class="icon-up" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"/></svg>
                                            <svg class="icon-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"/></svg>
                                        </button>
                                    </h5>
                                </div>
                                <div id="<?php echo $cat->slug; ?>-content" class="collapse" aria-labelledby="<?php echo $cat->slug; ?>" data-parent="#accordion">
                                    <div class="card-body">
                                        <?php 
                                            $args = array(
                                                'post_type' => 'contact_worldwide',
                                                'posts_per_page' => -1,
                                                'orderby' => 'title',
                                                'order' => 'ASC',
                                                'tax_query' => array(
                                                    array(
                                                        'taxonomy' => 'worldwide',
                                                        'field' => 'slug',
                                                        'terms' => $cat->slug
                                                    )
                                                )
                                            );

                                            $contacts = get_posts($args);
                                            if ( $contacts ) {
                                                echo '<div class="row list-contact">';
                                                foreach ($contacts as $contact) {
                                                    echo '<div class="col-xs-12 col-md-3 mb-3"><a href="'. get_permalink($contact->ID) .'">'. $contact->post_title .'</a></div>';
                                                }
                                                echo '</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    <?php endif;?>					
					</div><!-- .col -->	
				</div><!-- .row -->
			
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();