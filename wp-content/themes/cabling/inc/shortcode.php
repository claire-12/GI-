<?php
/****
 * Create ShortCode Get 3 Post Of Custom Post Type "Company"
 ****/
function shortcode_get_post_company()
{
    $country = cabling_get_current_country();
    if (isset($country['country'])) {
        $country_term = get_term_by('name', $country['country'], 'filter_country');
    }

    $args = array(
        'post_type' => 'company_news',
        'posts_per_page' => 3,
    );

    if (!empty($country_term)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'filter_country',
                'field' => 'term_id',
                'terms' => $country_term->term_id,
            )
        );
    }

    $count = 0;

    $new_query = new WP_Query($args);
    ob_start();
    if ($new_query->have_posts()) {
        while ($new_query->have_posts()) : $new_query->the_post(); ?>
            <div class="news-image grid-img grid-img-<?php echo $count++ ?>">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('full');
                    } else { ?>
                        <img src="http://placehold.it/350x183" alt="placeholder">
                    <?php } ?>
                </a>
            </div>
            <div class="link">
			<span class="uppercase-text">
				<?php printf(__('NEWS | %s', 'cabling'), get_the_date('d/m/Y')) ?>
			</span>
                <div class="bodytext">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="teaser-box teaser-box--blue bottom_link">
            <a href="<?php echo get_the_permalink(1746); ?>"><?php _e('Latest News', 'cabling') ?></a>
        </div>
        <?php
    }
    $list_post = ob_get_contents();
    ob_end_clean();
    wp_reset_postdata();

    return $list_post;
}

add_shortcode('get_post_company', 'shortcode_get_post_company');

/****
 * Create ShortCode Get 3 Post Of Custom Post Type "Event"
 ****/
function shortcode_get_post_event($attrs)
{
    if (empty($attrs['show'])) {
        $attrs['show'] = -1;
    }
    $args = array(
        'post_type' => 'event',
        // 'orderby' => 'initial_date',
        'order' => 'DESC',
        'posts_per_page' => $attrs['show']
    );
    $count = 0;
    $new_query = new WP_Query($args);
    ob_start();
    if ($new_query->have_posts()) {
        while ($new_query->have_posts()) :
            $new_query->the_post();
            ?>
            <div class="news-image grid-img grid-img-<?php echo $count++ ?>">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('full');
                    } else { ?>
                        <img src="http://placehold.it/350x183">
                    <?php } ?>
                </a>
            </div>
            <div class="link">
        	<span class="uppercase-text">
				<?php printf('%s | %s - %s', get_field('city'), get_field('initial_date'), get_field('end_date')) ?>
			</span>
                <div class="bodytext">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="teaser-box teaser-box--blue bottom_link">
            <a href="<?php echo home_url('/event/') ?>"><?php _e('Events', 'cabling') ?></a>
        </div>
    <?php }
    wp_reset_postdata();
    $list_post = ob_get_contents();
    ob_end_clean();
    return $list_post;
}

add_shortcode('get_post_event', 'shortcode_get_post_event');


/****
 * Create ShortCode Get 3 Post Of Custom Post Type "Download"
 ****/
function shortcode_get_post_download($atts)
{
    if (empty($atts['show'])) {
        $atts['show'] = -1;
    }
    $args = array(
        'post_type' => 'download',
        'order' => 'DESC',
        'posts_per_page' => $atts['show']
    );
    $count = 0;
    $new_query = new WP_Query($args);
    ob_start();
    if ($new_query->have_posts()) {
        while ($new_query->have_posts()) :
            $new_query->the_post();
            global $post;
            ?>
            <div class="news-image grid-img grid-img-<?php echo $count++ ?>">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail($post->ID)) { ?>
                        <?php the_post_thumbnail('full') ?>
                    <?php } else { ?>
                        <img src="http://placehold.it/350x183">
                    <?php } ?>
                </a>
            </div>
            <div class="link">
                <div class="bodytext">
                    <span><img src="<?php bloginfo('template_url'); ?>/assets/img/download-solid.svg"></span>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="teaser-box teaser-box--blue bottom_link">
            <a href="<?php echo get_home_url() ?>/download/"><?php _e('Latest Downloads', 'cabling') ?></a>
        </div>
    <?php } else {
        ?>
        <p class="message-no-product"><?php _e('No post', 'cabling') ?></p>
        <?php
    }
    wp_reset_postdata();
    $list_post = ob_get_contents();
    ob_end_clean();
    return $list_post;
}

add_shortcode('get_post_download', 'shortcode_get_post_download');


function cabling_show_country_contact_callback()
{
    $contact = cabling_show_footer_contact();
    ob_start(); ?>
    <div class="row">
        <?php if (!empty($contact[1])): ?>
            <div class="col-md-6 col-xs-12">
                <?php echo $contact[1] ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($contact[2])): ?>
            <div class="col-md-6 col-xs-12">
                <?php echo $contact[2] ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($contact[3])): ?>
            <div class="col-md-6 col-xs-12">
                <?php echo $contact[3] ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($contact[4])): ?>
            <div class="col-md-6 col-xs-12">
                <?php echo $contact[4] ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($contact['contact_link'])): ?>
            <div class="col-12">
                <?php echo $contact['contact_link'] ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
    $content = ob_get_clean();
    return $content;
}

add_shortcode('cabling_show_country_contact', 'cabling_show_country_contact_callback');

function cabling_cookie_message_callback()
{
    $cookie_message = get_field('cookie_message', 'options');
    ob_start();
    echo $cookie_message;
    $content = ob_get_clean();
    return $content;
}

add_shortcode('cookie_message', 'cabling_cookie_message_callback');

/**
 *  Get solutions post type
 */
function get_solutions_shortcode($atts)
{
    extract(shortcode_atts(array(
        'page_id' => '',
    ), $atts));

    $args = array(
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => 3
    );

    $new_query = new WP_Query($args);
    ob_start();
    if ($new_query->have_posts()): ?>

        <div class="spotligth-box">

            <div class="spotligth-list row">

                <?php while ($new_query->have_posts()) : $new_query->the_post(); ?>
                    <div class="col-xs-12 col-md-6 col-lg-4">
                        <div class="spotligth-item-inner">
                            <div class="spotligth-img">
                                <?php
                                $featured_img_url = get_the_post_thumbnail_url();
                                $related_product = get_field('related_product');
                                if (empty($related_product)){
                                    $link = get_the_permalink();
                                } else {
                                    $link = get_the_permalink($related_product);
                                }
                                ?>
                                <a href="<?php echo $link; ?>" style="display: contents;">
                                    <img src="<?php echo esc_url($featured_img_url); ?>" alt="thumbnail image">
                                </a>
                            </div>
                            <div class="spotligth-head">
                                <h5>
                                    <?php echo get_the_title(); ?>
                                </h5>
                            </div>
                            <div class="spotligth-text">
                                <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                            </div>
                            <div class="action-bottom">
                                <a class="main-button" href="<?php echo $link; ?>">
                                    <?php _e('Learn more', 'healthcare') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>

        </div>

    <?php endif;

    wp_reset_postdata();
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

add_shortcode('list_solutions', 'get_solutions_shortcode');

/**
 *  Get posts
 */
function get_posts_shortcode($attrs)
{
    extract(shortcode_atts(array(
        'page_id' => '',
    ), $attrs));

    $args = array(
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => 5
    );

    $new_query = new WP_Query($args);
    ob_start();
    if ($new_query->have_posts()): ?>

        <div class="news-box">

            <div class="news-list news-list-slider">

                <?php while ($new_query->have_posts()) : $new_query->the_post(); ?>
                    <?php
                    $category_detail = get_the_category();
                    ?>
                    <div class="news-item w-50 mr-30" data-title="<?php echo $category_detail[0]->name ?? ''; ?>">
                        <div class="news-item-content">
                            <?php if (!empty(get_field('top_titlle'))): ?>
                                <div class="top-title">
                                    <p><?php echo get_field('top_titlle'); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="top-title">
                                    <p><?php echo get_the_title(); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (has_post_thumbnail()): ?>
                                <div class="news-picture">
                                    <?php
                                    $featured_img_url = get_the_post_thumbnail_url();
                                    ?>
                                    <img src="<?php echo esc_url($featured_img_url); ?>" alt="News image">
                                </div>
                            <?php endif; ?>

                            <div class="news-head">
                                <p>
                                    <?php echo get_the_title(); ?>
                                </p>
                            </div>

                            <?php if (!empty(get_field('sub_title'))): ?>
                                <div class="news-subtitle">
                                    <p>
                                        <?php echo wp_trim_words(get_field('sub_title'), 15, '...'); ?>
                                    </p>
                                </div>
                            <?php endif; ?>

                            <div class="news-description">
                                <p>
                                    <?php echo wp_trim_words(get_the_excerpt(), 50, '...'); ?>
                                </p>
                            </div>

                            <a href="<?php echo get_field('link_post'); ?>" class="main-button" target="_blank">
                                <?php _e('Read more', 'healthcare') ?>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>

        </div>
    <?php endif;

    wp_reset_postdata();
    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}

add_shortcode('list_posts', 'get_posts_shortcode');
/**
 *  Get posts title
 */
function get_posts_title_shortcode($atts)
{
    extract(shortcode_atts(array(
        'heading' => __('Areas', 'cabling')
    ), $atts));

    $title = empty($atts['heading']) ? __('Title', 'cabling') : __('Areas', 'cabling');

    $args = array(
        'post_type' => get_post_type(get_the_ID()),
        'post_status' => 'publish',
        'posts_per_page' => -1
    );

    $posts = get_posts($args);
    ob_start();
    if ($posts): ?>
        <div class="blogs-title blog-list sidebar-widget">
            <h2 class="wp-block-heading"><?php echo $title ?></h2>
            <ul>
                <?php foreach ($posts as $post): ?>

                    <li class="item">
                        <a href="<?php echo get_the_permalink($post->ID) ?>"><?php echo get_the_title($post->ID) ?></a>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif;
    return ob_get_clean();
}

add_shortcode('list_posts_title', 'get_posts_title_shortcode');

/**
 * Show the list of taxonomies
 * @param $atts
 * @return false|string
 */
function show_taxonomy_list_shortcode($atts)
{
    extract(shortcode_atts(array(), $atts));

    $taxonomy = 'category';
    if (get_post_type(get_the_ID()) === 'company_news') {
        $taxonomy = 'news-category';
    }

    $term_list = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true
    ));
    ob_start();
    if (!empty($term_list)): ?>
        <div class="taxonomy-list sidebar-widget">
            <h2 class="wp-block-heading"><?php _e('Category', 'cabling'); ?></h2>
            <ul>
                <?php foreach ($term_list as $term): ?>

                    <li class="item">
                        <a href="<?php echo get_term_link($term) ?>"><?php echo $term->name ?></a>
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif;
    return ob_get_clean();
}

add_shortcode('show_taxonomy_list', 'show_taxonomy_list_shortcode');
/**
 * Show the list of post by date
 * @param $atts
 * @return false|string
 */
function show_post_by_date_shortcode($atts)
{
    extract(shortcode_atts(array(), $atts));

    $archives = wp_get_archives(array(
        'echo' => 0,
        'show_post_count' => false,
        'type' => 'daily',
        'post_type' => get_post_type(get_the_ID())
    ));

    ob_start();
    if (!empty($archives)): ?>
        <div class="post-date-list sidebar-widget">
            <h2 class="wp-block-heading"><?php _e('Date', 'cabling'); ?></h2>
            <ul>
                <?php echo $archives ?>
            </ul>
        </div>
    <?php endif;
    return ob_get_clean();
}

add_shortcode('show_post_by_date', 'show_post_by_date_shortcode');

/**
 * Show the list of post by date
 * @param $atts
 * @return false|string
 */
function webshop_show_categories_shortcode($atts)
{
    extract(shortcode_atts(array(
        'taxonomy' => '',
        'custom_template' => 'no',
        'meta_filter' => ''
    ), $atts));

    $args = array(
        'taxonomy' => $atts['taxonomy'],
        'hide_empty' => false,
        'parent' => 0,
        'exclude' => [7889],
        'orderby' => 'term_order',
    );
    if (!empty($atts['meta_filter'])) {
        $args['meta_query'] = array(
            array(
                'key' => 'product_type',
                'value' => $atts['meta_filter'],
                'compare' => '=',
            ),
        );
    }

    $taxonomies = get_terms($args);

    ob_start();
    if (!empty($taxonomies)): ?>
        <div class="taxonomies-list mt-4 category-block <?php echo 'list-' . $atts['taxonomy']; ?>">
            <div class="container">
                <?php if (isset($atts['custom_template']) && $atts['custom_template'] === 'yes'): ?>
                    <?php include get_template_directory() . '/template-parts/shortcode/list-' . $atts['taxonomy'] . '.php' ?>
                <?php else: ?>
                    <?php if ($atts['use_slider']): ?>
                        <div class="taxonomy-slider"
                             data-flickity='{ "cellAlign": "left", "contain": true, "prevNextButtons": false, "pageDots": false }'>
                            <?php foreach ($taxonomies as $taxonomy): ?>
                                <?php
                                if ($atts['taxonomy'] === 'product_cat') {
                                    $thumbnail_id = get_term_meta($taxonomy->term_id, 'thumbnail_id', true);
                                } else {
                                    $thumbnail_id = get_field('taxonomy_image', $taxonomy);
                                }
                                $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
                                $thumbnail = wp_get_attachment_image($thumbnail_id, 'full');
                                ?>
                                <div class="carousel-cell wp-block-image size-full" style="position: relative; ">
                                    <span class="wp-element-caption"><?php echo $taxonomy->name; ?></span>
                                    <?php echo $thumbnail ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <?php include get_template_directory() . '/template-parts/shortcode/list-' . $atts['taxonomy'] . '.php' ?>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
    <?php endif;
    return ob_get_clean();
}

add_shortcode('webshop_show_categories', 'webshop_show_categories_shortcode');
/**
 * Show the list of post by date
 * @param $atts
 * @return false|string
 */
function webshop_show_posts_shortcode($atts)
{
    $args = array(
        'post_type' => $atts['post_type'] ?? 'post',
        'posts_per_page' => 10,
        'fields' => 'ids',
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );

    if (!empty($atts['taxonomy'])) {
        $term = get_term($atts['taxonomy']);
        if ($term) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'term_id',
                    'terms' => $term->term_id,

                )
            );
        }
    }

    $posts = get_posts($args);

    ob_start();
    if (!empty($posts)): ?>
        <div class="posts-list category-block <?php echo 'list-' . $atts['post_type'] ?>">
            <div class="container">
                <?php if (isset($atts['custom_template']) && $atts['custom_template'] === 'yes'): ?>
                    <?php include get_template_directory() . '/template-parts/shortcode/list-' . $atts['post_type'] . '.php' ?>
                <?php else: ?>
                    <?php if ($atts['use_slider']): ?>
                        <div class="taxonomy-slider"
                             data-flickity='{ "cellAlign": "left", "contain": true, "prevNextButtons": false, "pageDots": false }'>
                            <?php foreach ($posts as $postId): ?>
                                <div class="slide-item">
                                    <?php include get_template_directory() . '/template-parts/shortcode/list-' . $atts['post_type'] . '.php' ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <?php include get_template_directory() . '/template-parts/shortcode/list-' . $atts['post_type'] . '.php' ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif;
    return ob_get_clean();
}

add_shortcode('webshop_show_posts', 'webshop_show_posts_shortcode');
