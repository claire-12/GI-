<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/jm.css" type="text/css" media="screen" />
	<link rel="icon" href="favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon" />

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'cabling'); ?></a>

    <header id="masthead" class="site-header" style="height:118px;">
        <div class="site-branding_jm">
            <div class="container">
                <div class="row align-items-center" style="top:39px;height:45px;">
                    <div class="col-sm-3">
                        <?php the_custom_logo(); ?>
                    </div>
                    <div class="col-sm-9">
                        <?php echo get_template_part('/template-parts/header', 'right_jm') ?>
                    </div>
                </div>
            </div>
        </div><!-- .site-branding -->
        <div class="header-search" style="display: none">
            <?php echo get_search_form(); ?>
            <div class="search-ajax"></div>
        </div>

    </header><!-- #masthead -->

    <div id="content" class="site-content">
