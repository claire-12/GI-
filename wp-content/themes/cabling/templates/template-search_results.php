<?php
/**
 * Template Name: SearchWP Results
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
global $post;

// Retrieve applicable query parameters.
$search_query = isset( $_GET['searchwp'] ) ? sanitize_text_field( $_GET['searchwp'] ) : null;
$search_page  = isset( $_GET['swppg'] ) ? absint( $_GET['swppg'] ) : 1;

// Perform the search.
$search_results    = [];
$search_pagination = '';
if ( ! empty( $search_query ) && class_exists( '\\SearchWP\\Query' ) ) {
	$searchwp_query = new \SearchWP\Query( $search_query, [
		'engine' => 'default', // The Engine name.
		'fields' => 'all',          // Load proper native objects of each result.
		'page'   => $search_page,
	] );

	$search_results = $searchwp_query->get_results();

	if ( ! empty( $search_query ) && ! empty( $search_results ) ) {
		foreach ( $search_results as $key => $search_result ) {
			switch ( get_class( $search_result ) ) {
				case 'WP_Post':
					$post = $search_result;
					//if(wpml_get_language_information($search_result->ID)['language_code'] != ICL_LANGUAGE_CODE) unset($search_results[$key]);
					wp_reset_postdata();
					break;
			}
		}
	}

	$search_pagination = paginate_links( array(
		'format'  => '?swppg=%#%',
		'current' => $search_page,
		'total'   => count($search_results) ?? 1,
	) );
}

get_header();
?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <div class="container">
                <div class="cabling-breadcrumb woocommerce-breadcrumb">
                    <span class="bc-parent"><?php _e( 'You Are Here: ', 'cabling' ) ?></span>
					<?php bcn_display(); ?>
                </div>

				<?php cabling_show_back_btn(); ?>

                <header class="page-header">
                    <h1 class="page-title">
						<?php if ( ! empty( $search_query ) ) : ?>
							<?php printf( __( 'Search Results for: %s' ), esc_html( $search_query ) ); ?>
						<?php endif; ?>
                    </h1>
                </header>

				<?php if ( ! empty( $search_query ) && ! empty( $search_results ) ) : ?>
					<?php foreach ( $search_results as $search_result ) : ?>
                        <article class="page hentry search-result">
							<?php
							switch ( get_class( $search_result ) ) {
								case 'WP_Post':
									$post = $search_result;
									?>
                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <?php the_post_thumbnail('full'); ?>
                                    </header>
                                    <div class="entry-summary"><?php the_excerpt(); ?></div>
									<?php
									wp_reset_postdata();
									break;
								case 'WP_User':
									?>
                                    <header class="entry-header"><h2 class="entry-title">
                                            <a href="<?php echo get_author_posts_url( $search_result->data->ID ); ?>">
												<?php echo esc_html( $search_result->data->display_name ); ?>
                                            </a>
                                        </h2></header>
                                    <div class="entry-summary">
										<?php echo wp_kses_post( get_the_author_meta( 'description',
											$search_result->data->ID ) ); ?>
                                    </div>
									<?php
									break;
							}
							?>
                        </article>
					<?php endforeach; ?>

					<?php if ( $searchwp_query->max_num_pages > 1 ) : ?>
                        <div class="navigation pagination" role="navigation">
                            <h2 class="screen-reader-text">Results navigation</h2>
                            <div class="nav-links"><?php echo wp_kses_post( $search_pagination ); ?></div>
                        </div>
					<?php endif; ?>
				<?php elseif ( ! empty( $search_query ) ) : ?>
                    <p><?php echo esc_attr_x( 'No results found, please search again.', 'submit button' ) ?></p>
				<?php endif; ?>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
