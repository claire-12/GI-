<?php
/**
* Plugin Name: Webshop ACF Blocks
* Description: All custom Gutenberg blocks using ACF.
* Version: 1.0.0
*/


if (!defined('ABSPATH')) {
    die();
}

define('WEBSHOP_ACF_DIR_PATH', plugin_dir_path(__FILE__));
define('WEBSHOP_ACF_DIR_URL', plugin_dir_url(__FILE__));

require WEBSHOP_ACF_DIR_PATH . 'includes/functions.php';

add_action('wp_enqueue_scripts', 'webshop_plugins_script');
function webshop_plugins_script() {
    wp_enqueue_style( 'webshop_plugin_style', WEBSHOP_ACF_DIR_URL . '/assets/css/webshop-style.css');

    wp_enqueue_script( 'webshop_plugin_script', WEBSHOP_ACF_DIR_URL . '/assets/js/webshop-script.js', array(), null, true );
}

function webshop_register_acf_block_types()
{
    $block_icon = '';
	acf_register_block_type([
        'name' => 'webshop_media_text',
        'title' => __('Webshop Media & Text', 'cabling'),
        'description' => __('', 'cabling'),
        'render_template' => WEBSHOP_ACF_DIR_PATH . '/block-templates/media_text/media_text.php',
        'enqueue_assets' => function () {
            wp_enqueue_style(
                'media_text',
                WEBSHOP_ACF_DIR_URL . '/block-templates/media_text/media_text.css',
                '',
                '1.0'
            );
        },
        'category' => 'webshop_blocks',
        'mode' => 'preview',
        'icon' => $block_icon,
        'keywords' => array('media_text', 'cabling'),
        'post_type' => [
            'page',
        ],
        'supports' => [
            'align' => ['full'],
            'anchor' => true,
            'customClassName' => true,
        ]
    ]);
	acf_register_block_type([
        'name' => 'webshop_customer_story_text',
        'title' => __('Webshop Customer Story', 'cabling'),
        'description' => __('Webshop Customer Story Block', 'cabling'),
        'render_template' => WEBSHOP_ACF_DIR_PATH . '/block-templates/webshop_customer_story/webshop_customer_story.php',
        'enqueue_assets' => function () {
            wp_enqueue_style(
                'webshop_customer_story',
                WEBSHOP_ACF_DIR_URL . '/block-templates/webshop_customer_story/webshop_customer_story.css',
                '',
                '1.0'
            );
        },
        'category' => 'webshop_blocks',
        'mode' => 'preview',
        'icon' => $block_icon,
        'keywords' => array('webshop_customer_story', 'cabling'),
        'post_type' => [
            'page',
        ],
        'supports' => [
            'align' => ['full'],
            'anchor' => true,
            'customClassName' => true,
        ]
    ]);
	acf_register_block_type([
        'name' => 'webshop_request_a_quote',
        'title' => __('Webshop Request A Quote', 'cabling'),
        'description' => __('Webshop Request A Quote Block', 'cabling'),
        'render_template' => WEBSHOP_ACF_DIR_PATH . '/block-templates/webshop_request_a_quote/webshop_request_a_quote.php',
        'enqueue_assets' => function () {
            wp_enqueue_style(
                'webshop_request_a_quote',
                WEBSHOP_ACF_DIR_URL . '/block-templates/webshop_request_a_quote/webshop_request_a_quote.css',
                '',
                '1.0'
            );
        },
        'category' => 'webshop_blocks',
        'mode' => 'preview',
        'icon' => $block_icon,
        'keywords' => array('quote', 'cabling'),
        'post_type' => [
            'page',
        ],
        'supports' => [
            'align' => ['full'],
            'anchor' => true,
            'customClassName' => true,
        ]
    ]);
	acf_register_block_type([
        'name' => 'webshop_request_a_quote_button',
        'title' => __('Webshop Request A Quote Button', 'cabling'),
        'description' => __('Webshop Request A Quote Button Block', 'cabling'),
        'render_template' => WEBSHOP_ACF_DIR_PATH . '/block-templates/webshop_request_a_quote_button/webshop_request_a_quote_button.php',
        'category' => 'webshop_blocks',
        'mode' => 'preview',
        'icon' => $block_icon,
        'keywords' => array('quote', 'cabling'),
        'post_type' => [
            'page',
        ],
        'supports' => [
            'align' => ['full'],
            'anchor' => true,
            'customClassName' => true,
        ]
    ]);

	acf_register_block_type([
        'name' => 'webshop_content_section',
        'title' => __('Webshop Content Section', 'cabling'),
        'description' => __('Webshop Content Section', 'cabling'),
        'render_template' => WEBSHOP_ACF_DIR_PATH . '/block-templates/webshop_content_section/webshop_content_section.php',
        'category' => 'webshop_blocks',
        'mode' => 'preview',
        'icon' => $block_icon,
        'keywords' => array('webshop_content_section', 'cabling'),
        'post_type' => [
            'page',
        ],
        'supports' => [
            'align' => ['full'],
            'anchor' => true,
            'customClassName' => true,
        ]
    ]);

}

if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'webshop_register_acf_block_types');
}

/**
 * add custom block category for ACF blocks
 * @link https://developer.wordpress.org/block-editor/developers/filters/block-filters/#managing-block-categories
 */
function webshop_block_category($categories, $post)
{
    return array_merge(
        $categories,
        [
            [
                'slug' => 'webshop_blocks',
                'title' => __('Webshop Blocks', 'cabling'),
            ],
        ]
    );
}

add_filter('block_categories_all', 'webshop_block_category', 10, 2);

add_theme_support( 'align-wide' );
