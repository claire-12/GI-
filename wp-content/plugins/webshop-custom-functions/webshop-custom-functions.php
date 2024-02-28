<?php
/*
Plugin Name: Webshop Customized
Description: Add some custom functionality to the WebShop
Version: 1.0
Author: MinhTuanDang
Text Domain: cabling
*/

define( 'WBC_VERSION', '1.0' );
define( 'WBC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WBC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WBC_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'WBC_PLUGIN_FILE', basename( __FILE__ ) );
define( 'WBC_PLUGIN_FULL_PATH', __FILE__ );

require_once( WBC_PLUGIN_DIR .'vendor/autoload.php' );
require_once( WBC_PLUGIN_DIR .'includes/classes/SearchLog.php' );
require_once( WBC_PLUGIN_DIR .'includes/classes/UserInformed.php' );
require_once( WBC_PLUGIN_DIR .'includes/classes/CablingPageTemplate.php' );
require_once( WBC_PLUGIN_DIR .'includes/classes/RequestProductQuote.php' );
require_once( WBC_PLUGIN_DIR .'includes/classes/CRMController.php' );

function admin_webshop_enqueue_scripts()
{
    wp_enqueue_style('dataTables-webshop', '//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css');

    wp_enqueue_script('dataTables-webshop', '//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js', array(), null, true);
    wp_enqueue_script('admin-webshop', WBC_PLUGIN_URL . '/assets/js/admin-webshop.js', array(), null, true);
}
add_action('admin_enqueue_scripts', 'admin_webshop_enqueue_scripts');

function webshop_enqueue_scripts()
{
    wp_enqueue_script('webshop-cuz', WBC_PLUGIN_URL . '/assets/js/webshop.js', array(), null, true);

    $cabling_nonce = wp_create_nonce('cabling-ajax-nonce');
    wp_localize_script('webshop-cuz', 'CABLING', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'   => $cabling_nonce,
    ));
}
add_action('wp_enqueue_scripts', 'webshop_enqueue_scripts');

add_action('wp_ajax_cabling_save_search_log_ajax', array('SearchLog', 'save_search_logs_ajax'));
add_action('wp_ajax_nopriv_cabling_save_search_log_ajax', array('SearchLog', 'save_search_logs_ajax'));
add_action('admin_menu', array('SearchLog', 'add_admin_menu'));
add_action('admin_init', array('SearchLog', 'delete_all_search_logs'));

add_action('init', array('UserInformed', 'confirm_keep_informed'));
add_action('init', array('UserInformed', 'process_unsubscription'));
add_action('wp_footer', array('UserInformed', 'add_informed_popup'));
add_action('admin_menu', array('UserInformed', 'add_admin_menu'));
add_action('wp_ajax_delete_user_informed_row', array('UserInformed', 'delete_user_informed_row'));
add_action('wp_ajax_cabling_save_keep_informed_data', array('UserInformed', 'save_user_setting_account'));
add_action('wp_ajax_nopriv_cabling_save_keep_informed_data', array('UserInformed', 'save_user_setting_account'));
add_action('wp_ajax_cabling_get_keep_informed_modal', array('UserInformed', 'setting_account_endpoint_content'));
add_action('wp_ajax_nopriv_cabling_get_keep_informed_modal', array('UserInformed', 'setting_account_endpoint_content'));
add_action( 'woocommerce_account_setting-account_endpoint', array('UserInformed', 'setting_account_endpoint_content') );
add_action( 'woocommerce_email_footer', array('UserInformed', 'custom_woocommerce_email_footer') );

add_action('save_post_company_news', array('UserInformed', 'notify_subscribers'), 10, 2);
add_action('save_post_post', array('UserInformed', 'notify_subscribers'), 10, 2);
add_action('save_post_product', array('UserInformed', 'notify_subscribers'), 10, 2);

// Create the table when the plugin is activated
register_activation_hook(__FILE__, array('SearchLog', 'create_table'));
register_activation_hook(__FILE__, array('UserInformed', 'create_table'));
register_activation_hook(__FILE__, array('RequestProductQuote', 'create_table'));
