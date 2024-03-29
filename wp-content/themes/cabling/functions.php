<?php
/**
 * cabling functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cabling
 */

define('MASTER_ACCOUNT', 'master_account');
define('CHILD_ACCOUNT', 'child_account');
define('LOG_DB_NAME', 'customer_change_logs');


if (!function_exists('cabling_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function cabling_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on cabling, use a find and replace
         * to change 'cabling' to the name of your theme in all the template files.
         */
        load_theme_textdomain('cabling', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'menu-1' => esc_html__('Primary', 'cabling'),
            'top-header' => esc_html__('Top Header', 'cabling'),
            'footer-copyright' => esc_html__('Footer Copyright', 'cabling'),
            'footer-link' => esc_html__('Footer Links', 'cabling'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('cabling_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support('custom-logo', array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }
endif;
add_action('after_setup_theme', 'cabling_setup');

add_action('init', 'start_session', 1);
function start_session()
{
    if (!session_id()) {
        session_start();
    }
}

add_action('wp_logout', 'end_session');
add_action('wp_login', 'end_session');
function end_session()
{
    session_destroy();
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cabling_content_width()
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('cabling_content_width', 640);
}

add_action('after_setup_theme', 'cabling_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cabling_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'cabling'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'cabling'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Brands', 'cabling'),
        'id' => 'footer-brand',
        'description' => esc_html__('Add widgets here.', 'cabling'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Links', 'cabling'),
        'id' => 'footer-2',
        'description' => esc_html__('Add widgets here.', 'cabling'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer Copyright', 'cabling'),
        'id' => 'footer-copyright',
        'description' => esc_html__('Add widgets here.', 'cabling'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Blog Sidebar', 'cabling'),
        'id' => 'blog-sidebar',
        'description' => esc_html__('Add widgets here.', 'cabling'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Forum Sidebar', 'cabling'),
        'id' => 'forum-sidebar',
        'description' => esc_html__('Add widgets here.', 'cabling'),
        'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
        'after_widget' => '</div>',
    ));
}

add_action('widgets_init', 'cabling_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function cabling_scripts()
{
    wp_enqueue_style('cabling-style', get_stylesheet_uri());
    wp_enqueue_style('flickity', get_template_directory_uri() . '/assets/js/flickity/flickity.min.css');
    wp_enqueue_style('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('flatpickr', get_template_directory_uri() . '/assets/js/flatpickr/flatpickr.min.css');
    wp_enqueue_style('cabling-font-awesome', get_template_directory_uri() . '/assets/css/Font-Awesome-6.4.0/css/all.css');
    wp_enqueue_style('cabling-theme', get_template_directory_uri() . '/assets/css/theme.css');
    wp_enqueue_style('cabling-responsive', get_template_directory_uri() . '/assets/css/responsive.css');

    wp_enqueue_style('intlTelInput', '//cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.min.css');
    wp_enqueue_script('intlTelInput', '//cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js', array(), '18.2.1', true);

    if (is_checkout()) {
        wp_enqueue_script('cabling-checkout', get_template_directory_uri() . '/assets/js/checkout-step.js', array(), null, true);
    }
    if (is_page_template('templates/register.php')) {
        wp_enqueue_script('jquery-validation', '//cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js', array('jquery'), null, true);
    }

    wp_enqueue_script('recaptcha', '//www.google.com/recaptcha/api.js', array(), null, true);
    wp_enqueue_script('bootstrap', '//cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), null, true);
    //wp_enqueue_script('pdfobject', '//cdn.jsdelivr.net/npm/pdfobject@2.2.12/pdfobject.min.js',array('jquery'), null, true);
    wp_enqueue_script('flatpickr', get_template_directory_uri() . '/assets/js/flatpickr/flatpickr.min.js', array(), null, true);
    wp_enqueue_script('flatpickr-rangePlugin', get_template_directory_uri() . '/assets/js/flatpickr/plugins/rangePlugin.js', array(), null, true);
    wp_enqueue_script('flickity', get_template_directory_uri() . '/assets/js/flickity/flickity.pkgd.min.js', array(), null, true);
    wp_enqueue_script('cabling-theme', get_template_directory_uri() . '/assets/js/theme.js', array(), null, true);
    wp_enqueue_script('cabling-webshop', get_template_directory_uri() . '/assets/js/webshop.js', array(), null, true);

    $cabling_nonce = wp_create_nonce('cabling-ajax-nonce');
    wp_localize_script('cabling-theme', 'CABLING', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'   => $cabling_nonce,
    ));
    wp_localize_script('cabling-webshop', 'CABLING', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'   => $cabling_nonce,
            'product_page'   => is_tax('product_custom_type') ? get_term_link(get_queried_object()) : home_url('/products-and-services'),
    ));

    wp_enqueue_script('cabling-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20151215', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    wp_dequeue_script('wc-lost-password');

}

add_action('wp_enqueue_scripts', 'cabling_scripts');


/**
 * Enqueue a script in the WordPress admin, excluding edit.php.
 *
 * @param int $hook Hook suffix for the current admin page.
 */
function cabling_enqueue_admin_script($hook)
{
    /* if ( 'edit.php' != $hook ) {
         return;
     }*/
    wp_enqueue_script('cabling-script', get_template_directory_uri() . '/assets/js/admin.js', array(), '1.0');
}

add_action('admin_enqueue_scripts', 'cabling_enqueue_admin_script');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}


/**
 * Load Icode compatibility file.
 */

require get_template_directory() . '/inc/icode-functions.php';
require get_template_directory() . '/inc/ajax.php';
require get_template_directory() . '/inc/icode-custom_postype.php';
require get_template_directory() . '/inc/shortcode.php';
require get_template_directory() . '/inc/GIWebServices.php';

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
    require get_template_directory() . '/inc/woocommerce.php';
    require get_template_directory() . '/inc/variation-meta.php';
}

global $wpdb;

$table_name = $wpdb->prefix . LOG_DB_NAME;

// Check if the table already exists
if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        change_by_id mediumint(9) NOT NULL,
        user_id mediumint(9) NOT NULL,
        data text NOT NULL,
        change_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function log_customer_change($user_by, $user_id, $data) {
    global $wpdb;
    $table_name = $wpdb->prefix . LOG_DB_NAME;

    $wpdb->insert(
        $table_name,
        array(
            'change_by_id' => $user_by,
            'user_id' => $user_id,
            'data' => $data,
            'change_date' => current_time('mysql'),
        )
    );
}

function get_customer_log($user_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . LOG_DB_NAME;

    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d ORDER BY change_date DESC",
            $user_id
        )
    );
}

function cabling_show_footer_contact()
{
    $country = cabling_get_country();
    //$contact = get_field('field_5fc8ed86b7b65','options_language');
    $contact = get_field('footer_contact', 'options');
    $contact = empty($contact) ? get_field('field_5fc8ed86b7b65', 'options_language') : $contact;
    $content = [];
    if ($contact) {
        foreach ($contact as $key => $c) {
            $content['contact_link'] = $c['contact_link'];

            if ($key === 0) {
                $content[1] = $c['content'] ?: '';
                $content[2] = $c['content_2'] ?: '';
                $content[3] = $c['content_3'] ?: '';
                $content[4] = $c['content_4'] ?: '';
            }

            if ($country['code'] === $c['country_code']) {
                $content[1] = $c['content'] ?: '';
                $content[2] = $c['content_2'] ?: '';
                $content[3] = $c['content_3'] ?: '';
                $content[4] = $c['content_4'] ?: '';
                break;
            }
        }
    }
    return $content;
}

function password_change_email_admin($email, $user, $blogname)
{
    $sr_search = array("!!user_name!!");
    $sr_replace = array($user->display_name);
    $newcontent = get_field('message_changepw', 'option');

    $subject = get_field('subject_email_changepw', 'option');
    $sr_searchsubject = array("!!site_name!!");
    $sr_replacesubject = array("Datwyler");

    $email['subject'] = str_replace($sr_searchsubject, $sr_replacesubject, $subject);
    $email['message'] = str_replace($sr_search, $sr_replace, $newcontent);
    return $email;
}

add_filter('wp_password_change_notification_email', 'password_change_email_admin', 10, 3);


function my_new_user_notification_email_admin($wp_new_user_notification_email_admin, $user, $blogname)
{
    $sr_search = array("!!user_name!!", "!!email!!");
    $sr_replace = array($user->display_name, $user->user_email);
    $newcontent = get_field('message_newuser', 'option');

    $subject = get_field('subject_email_newuser', 'option');
    $sr_searchsubject = array("!!site_name!!");
    $sr_replacesubject = array("Datwyler");

    $wp_new_user_notification_email_admin['subject'] = str_replace($sr_searchsubject, $sr_replacesubject, $subject);
    $wp_new_user_notification_email_admin['message'] = str_replace($sr_search, $sr_replace, $newcontent);

    return $wp_new_user_notification_email_admin;
}

add_filter('wp_new_user_notification_email_admin', 'my_new_user_notification_email_admin', 10, 3);

add_filter('allow_empty_comment', '__return_true');

add_action('template_redirect', 'verify_register_code');
function verify_register_code(): void
{
    if (is_page_template('templates/register.php') && isset($_GET['code'])) {
        $data = json_decode(base64_decode($_GET['code']));
        $email = urldecode($data->email);

        if (empty($email) || empty(get_transient($email)) || ($data->code != get_transient($email))) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit();
        }
    }

}
// Force enable SearchWP's alternate indexer.
add_filter( 'searchwp\indexer\alternate', '__return_true' );

add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point( $paths ) {
  // Remove the default path (optional)
  unset($paths[0]);

  // Add your custom path
  $paths[] = get_stylesheet_directory() . '/acf-json';

  return $paths;
}

function my_acf_json_save_point( $path ) {
    return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'my_acf_json_save_point' );
