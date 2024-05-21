<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon" />
	

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'cabling'); ?></a>

    <header id="masthead" class="site-header">
        <div class="site-branding">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-9 col-lg-3">
                        <?php the_custom_logo(); ?>
                    </div>
                    <div class="col-3 col-lg-9">
                        <?php echo get_template_part('/template-parts/header', 'right') ?>
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
