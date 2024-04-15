<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/jm.css" type="text/css" media="screen" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'cabling'); ?></a>

    <header id="masthead" class="site-header" >
        <div class="site-branding_jm_reduced">
            <div class="container">
                <div class="row" style="top:39px;">
                    <div class="col-8 col-sm-3">
                        <?php the_custom_logo(); ?>
                    </div>
                    <div class="col-4 col-sm-9 d-flex align-items-center">
                        <?php echo get_template_part('/template-parts/header', 'right_jm_reduced') ?>
                    </div>
                </div>
            </div>
        </div><!-- .site-branding -->
        <div class="header-search" style="display: none">
            <?php echo get_search_form(); ?>
            <div class="search-ajax">
                <div class="search-result-wrap container">
                    <h3><?php echo __('Search Results', 'cabling') ?></h3>
                    <p class="search-text"><?php printf(__('Search results for: <span></span>', 'cabling')) ?></p>
                    <div class="search-filters">
                        <form action="" id="search-ajax-form">
                            <p class="label"><?php echo __('Advanced Search:', 'cabling') ?></p>
                            <div class="search-filter d-flex align-items-center justify-between">
                                <div class="item me-2 d-flex align-items-center">
                                    <label for="product_cat"
                                           class="label"><?php echo __('Product Category', 'cabling') ?></label>
                                    <?php echo get_product_category_list() ?>
                                </div>
                                <div class="item me-2">
                                    <span class="label">Product (specific)</span>
                                    <label class="switch">
                                        <input type="checkbox" name="search-product" value="yes">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="item me-2">
                                    <span class="label">News</span>
                                    <label class="switch">
                                        <input type="checkbox" name="search-news" value="yes">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="item me-2">
                                    <span class="label">Insight/Blog</span>
                                    <label class="switch">
                                        <input type="checkbox" name="search-blog" value="yes">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="item me-2">
                                    <span class="label">All website</span>
                                    <label class="switch">
                                        <input type="checkbox" name="search-all" value="yes">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="item">
                                    <button class="search-filter-ajax"
                                            type="button"><?php echo __('Search', 'cabling') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="ajax-results" class="ajax-results"></div>
                </div>
            </div>
        </div>

    </header><!-- #masthead -->

    <div id="content" class="site-content">
