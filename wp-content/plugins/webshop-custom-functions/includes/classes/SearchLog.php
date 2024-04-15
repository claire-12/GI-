<?php

class SearchLog
{
    public static function create_table()
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

    public static function delete_all_search_logs() {
        if (isset($_GET['action']) && $_GET['action'] === 'delete_all_search_logs') {
            global $wpdb;
            $table_name = $wpdb->prefix . 'search_logs';

            // Delete all logs
            $wpdb->query("TRUNCATE TABLE $table_name");

            // Redirect back to the admin menu page
            wp_redirect(admin_url('tools.php?page=search-logs'));
            exit();
        }
    }

    public static function log_search_query($search_query)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'search_logs';
        $user_id = is_user_logged_in() ? get_current_user_id() : null;
        $ip_address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        // Sanitize the search query
        $search_query = sanitize_text_field($search_query);

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

        $per_page = 20; // Number of entries per page
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $per_page;

        $search_logs = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY search_date DESC LIMIT $per_page OFFSET $offset"
        );

        echo '<div class="wrap">';
        echo '<h1>Search Logs</h1>';
        // Add a button to delete all logs
        echo '<div class="delete-logs-button">';
        echo '<button class="button button-primary" id="delete-all-logs">Delete All Logs</button>';
        echo '</div>';
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

        // Pagination links
        $total_entries = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $total_pages = ceil($total_entries / $per_page);
        $page_links = paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'prev_text' => '&laquo; Previous',
            'next_text' => 'Next &raquo;',
            'total' => $total_pages,
            'current' => $current_page,
        ));

        if ($page_links) {
            echo '<div class="tablenav">';
            echo '<div class="tablenav-pages">' . $page_links . '</div>';
            echo '</div>';
        }

        echo '</div>';
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteAllLogsButton = document.getElementById('delete-all-logs');
                if (deleteAllLogsButton) {
                    deleteAllLogsButton.addEventListener('click', function() {
                        if (confirm('Are you sure you want to delete all logs?')) {
                            // Redirect to the PHP function that handles log deletion
                            window.location.href = "<?php echo admin_url('admin-ajax.php?action=delete_all_search_logs'); ?>";
                        }
                    });
                }
            });
        </script>
        <?php
        echo '<style>
            .delete-logs-button {
                text-align: right;
                margin-bottom: 15px;
            }
            .tablenav-pages {
                margin: 1em 0;
            }
        
            .tablenav-pages a,
            .tablenav-pages span {
                display: inline-block;
                margin-right: 6px;
                padding: 6px 10px;
                border: 1px solid #ccc;
                background-color: #f9f9f9;
                color: #555;
                text-decoration: none;
                border-radius: 3px;
            }
        
            .tablenav-pages a:hover {
                background-color: #eee;
            }
        
            .tablenav-pages .current,
            .tablenav-pages .current:hover {
                background-color: #0073e6;
                color: #fff;
                border-color: #0073e6;
            }
        
            .tablenav-pages .next,
            .tablenav-pages .prev {
                font-weight: bold;
            }
        </style>';

    }

    public static function save_search_logs_ajax()
    {
        $text = $_REQUEST['data'];

        self::log_search_query($text);
        exit();
    }
}
