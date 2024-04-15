<?php

class SearchLog
{
    function __construct()
    {
        self::create_table();
    }

    private static function create_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'search_logs';

        // Check if the table already exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id mediumint(9),
                ip_address varchar(45),
                search_query text NOT NULL,
                search_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function log_search_query($search_query)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'search_logs';

        $user_id = is_user_logged_in() ? get_current_user_id() : null;
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'ip_address' => $ip_address,
                'search_query' => $search_query,
            )
        );
    }

    public static function add_admin_menu()
    {
        add_submenu_page(
            'tools.php',            // Parent menu slug
            'Search Logs',          // Page title
            'Search Logs',          // Menu title
            'manage_options',       // Capability required to access
            'search-logs',          // Page slug
            array('SearchLog', 'display_search_logs')   // Callback function to display content
        );
    }

    public static function display_search_logs()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'search_logs';

        $search_logs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY search_date DESC");
var_dump($search_logs);
        echo '<div class="wrap">';
        echo '<h1>Search Logs</h1>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>Date</th><th>User ID</th><th>IP Address</th><th>Search Query</th></tr></thead>';
        echo '<tbody>';

        foreach ($search_logs as $log) {
            echo '<tr>';
            echo '<td>' . $log->search_date . '</td>';
            echo '<td>' . ($log->user_id ?: 'N/A') . '</td>';
            echo '<td>' . $log->ip_address . '</td>';
            echo '<td>' . $log->search_query . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }
}

add_action('admin_menu', array('SearchLog', 'add_admin_menu'));
