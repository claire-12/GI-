<?php

class UserInformed
{
    public static function create_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'user_informed';

        // Check if the table already exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id mediumint(9),
                ip_address varchar(45),
                channel varchar(10),
                channel_value varchar(255),
                category text NOT NULL,
                date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function add_informed_popup() {
        wc_get_template('template-parts/keep-informed-popup.php', [], '', WBC_PLUGIN_DIR);
    }

    public static function delete_all_user_informed() {
        if (isset($_GET['action']) && $_GET['action'] === 'delete_all_user_informed') {
            global $wpdb;
            $table_name = $wpdb->prefix . 'user_informed';

            // Delete all logs
            $wpdb->query("TRUNCATE TABLE $table_name");

            // Redirect back to the admin menu page
            wp_redirect(admin_url('tools.php?page=user-informed'));
            exit();
        }
    }

    private static function update_informed_channel($channel, $value, $category): void
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'user_informed';

        $data = array(
            'user_id' => 0,
            'ip_address' => '',
            'channel' => sanitize_text_field($channel),
            'channel_value' => sanitize_text_field($value),
            'category' => serialize($category)
        );

        $exist_row = $wpdb->get_col($wpdb->prepare("
            SELECT * FROM $table_name
            WHERE channel = %s AND channel_value = %s
        ", $data['channel'], $data['channel_value']));

        if (is_user_logged_in()) {
            $data['user_id'] = get_current_user_id();
        } else {
            $data['ip_address'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        }
        if (empty($exist_row[0])){
            $wpdb->insert($table_name, $data);
        } else {
            $wpdb->update(
                $table_name,
                $data,
                array('id' => (int)$exist_row[0]),
                array('%d','%s','%s','%s','%s'),
                array('%d')
            );
        }
    }

    private static function remove_informed_channel($channel): void
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'user_informed';

        if (is_user_logged_in()) {
            $wpdb->query($wpdb->prepare("
                DELETE FROM $table_name
                WHERE user_id = %d
                AND channel = %s
            ", get_current_user_id(), $channel));
        } else {
            $ip_address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
            $wpdb->query($wpdb->prepare("
                DELETE FROM $table_name
                WHERE ip_address = %s
                AND channel = %s
            ", $ip_address, $channel));

        }
    }

    public static function save_user_setting_account(): void
    {
        parse_str($_REQUEST['data'], $data);
        if (isset($data['_wpnonce']) && wp_verify_nonce($data['_wpnonce'], 'setting-account-action')){
            $verified_recapcha = cabling_verify_recaptcha($data['g-recaptcha-response']);
            if (empty($verified_recapcha)){
                //wp_send_json_error(__('reCAPTCHA verification failed. Please try again!', 'cabling'));
            }
            $success = __('Subscription successfully!', 'cabling');
            $informedData = [];
            if (is_user_logged_in()){
                $user = wp_get_current_user();

                $email = $user->user_email;
                $sms = get_user_phone_number($user->ID);
                $whatsapp = $sms;
            } else {
                $email = sanitize_email($data['channel-email']);
                if (!empty($data['sms_number'])){
                    if (strstr($data['sms_number'], $data['sms_number_code'])) {
                        $sms = $data['sms_number'];
                    } else {
                        $sms = sprintf('+%s%s', $data['sms_number_code'], $data['sms_number']);
                    }
                }
                if (!empty($data['whatsapp_number'])){
                    if (strstr($data['whatsapp_number'], $data['whatsapp_number_code'])) {
                        $whatsapp = $data['whatsapp_number'];
                    } else {
                        $whatsapp = sprintf('+%s%s', $data['whatsapp_number_code'], $data['whatsapp_number']);
                    }
                }
            }

            $informedData['email'] = $email;
            $informedData['category'] = $data['category'];

            if (!empty($data['informed_channel']['email']) && filter_var($email, FILTER_VALIDATE_EMAIL)){
                self::update_informed_channel('email', $email, $data['category']);

                if (!is_user_logged_in()) {
                    self::send_confirm_notification($email);
                    $is_sent_confirmed = true;
                }

                $success = __('Subscription Confirmation: Please check you email to confirm the subscription.', 'cabling');
            } else {
                self::remove_informed_channel('email');
            }
            if (!empty($data['informed_channel']['sms']) && !empty($sms)){
                $informedData['sms'] = $sms;
                self::update_informed_channel('sms', $sms, $data['category']);
            } else {
                self::remove_informed_channel('sms');
            }
            if (!empty($data['informed_channel']['whatsapp']) && !empty($whatsapp)){
                $informedData['whatsapp'] = $whatsapp;
                self::update_informed_channel('whatsapp', $whatsapp, $data['category']);
            } else {
               self::remove_informed_channel('whatsapp');
            }

            if (empty($is_sent_confirmed)) {
                do_action('saved_user_keep_informed', $informedData);
            }

            wp_send_json_success($success);
        }

        wp_send_json_error(['error' => __('Something went wrong.', 'cabling')]);
    }

    private static function find_category($category): array|object|null
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_informed';

        // Prepare and execute the SQL query
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE category LIKE %s", '%"' . $wpdb->esc_like($category) . '";%');
        return $wpdb->get_results($query);
    }

    private static function find_category_by_user(): array|object|null
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_informed';

        if (is_user_logged_in()) {
            $query = $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d", get_current_user_id());
        } else {
            $ip_address = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
            $query = $wpdb->prepare("SELECT * FROM $table_name WHERE ip_address = %s", $ip_address);

        }
        return $wpdb->get_results($query, ARRAY_A);
    }

    public static function notify_subscribers($ID, $post): void
    {
        if ($post->post_status !== 'publish')
            return;

        $post_type = get_post_type($post);

        if (!in_array($post_type, ['product', 'company_news', 'post']))
            return;

        $taxonomy_name = match ($post_type){
            'product' => 'product_cat',
            'company_news' => 'news-category',
            default => 'category',
        };

        $terms = get_the_terms($post, $taxonomy_name);
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $informed_categories = self::find_category($term->term_id);
                if ($informed_categories){
                    foreach ($informed_categories as $category){
                        if ($category->channel === 'email'){
                            $email = $category->channel_value;

                            $key = sanitize_key('verify_informed_'.$email);
                            $is_verified = get_option($key, 'no');

                            if (empty($category->user_id) && $is_verified != 'yes')
                                continue;

                            $post_url = get_the_permalink($ID);
                            $subject = 'New Post Published in ' . $term->name;
                            $message = "A new post in ". $term->name ." has been published: '$post->post_title'.\n\nRead it here: $post_url";

                            self::send_email($email, $subject, $message);
                        }
                    }
                }
            }
        }
    }

    public static function add_admin_menu(): void
    {
        add_submenu_page(
            'tools.php',            // Parent menu slug
            'User Informed',          // Page title
            'User Informed',          // Menu title
            'manage_options',       // Capability required to access
            'user-informed',          // Page slug
            array('UserInformed', 'display_user_informed')   // Callback function to display content
        );
    }

    private static function send_email($to, $subject, $content): void
    {
        $mailer = WC()->mailer();
        $mailer->recipient = $to;
        $type = 'template-parts/keep-informed-mail.php';
        $content = self::get_email_html('', $subject, $mailer, $type, $content);
        $headers = "Content-Type: text/html\r\n";

        $mailer->send($to, $subject, $content, $headers);
    }

    private static function get_email_html($link, $heading, $mailer, $type, $content = ''): string
    {
        return wc_get_template_html($type, array(
            'link_verify' => $link,
            'email_heading' => $heading,
            'sent_to_admin' => false,
            'plain_text' => false,
            'email' => $mailer,
            'content' => $content,
        ),
        '', WBC_PLUGIN_DIR);

    }

    public static function setting_account_endpoint_content(): void
    {
        $args = self::getInformedData();

        if (isset($_REQUEST['action'])){
            ob_start();
            wc_get_template('template-parts/keep-informed.php', $args, '', WBC_PLUGIN_DIR);
            $data = ob_get_clean();
            wp_send_json_success($data);
        }
        wc_get_template('template-parts/keep-informed.php', $args, '', WBC_PLUGIN_DIR);
    }

    public static function display_user_informed()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_informed';

        $per_page = 20; // Number of entries per page
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $per_page;

        $user_informed = $wpdb->get_results(
            "SELECT * FROM $table_name ORDER BY channel DESC LIMIT $per_page OFFSET $offset"
        );

        echo '<div class="wrap informed-log">';
        echo '<h1>User Informed</h1>';
        // Add a button to delete all logs
        echo '<div class="delete-logs-button">';
        echo '<button class="button button-primary" id="delete-all-logs">Delete All</button>';
        echo '</div>';
        echo '<table class="widefat">';
        echo '<thead><tr><th>User ID</th><th>IP Address</th><th>Channel</th><th>Value</th><th>Category</th><th>Date</th><th></th></tr></thead>';
        echo '<tbody>';

        foreach ($user_informed as $log) {
            $cats = '';
            if (is_array(unserialize($log->category))){
                $cats = implode(',', unserialize($log->category));
            }
            echo '<tr>';
            echo '<td>' . $log->user_id . '</td>';
            echo '<td>' . $log->ip_address . '</td>';
            echo '<td>' . $log->channel . '</td>';
            echo '<td>' . ($log->channel_value ?: 'N/A') . '</td>';
            echo '<td style="max-width: 300px">' . $cats  . '</td>';
            echo '<td>' . $log->date . '</td>';
             echo '<td><button class="delete-row" data-log-id="' . $log->id . '">Delete</button></td>';
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
                        if (confirm('Are you sure you want to delete all items?')) {
                            // Redirect to the PHP function that handles log deletion
                            window.location.href = "<?php echo admin_url('admin-ajax.php?action=delete_all_user_informed'); ?>";
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

    public static function delete_user_informed_row() {
        // Get the log_id from the AJAX request
        $log_id = intval($_POST['log_id']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'user_informed';

        // Perform the deletion
        $result = $wpdb->delete($table_name, array('id' => $log_id), array('%d'));

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    private static function send_notification(string $email)
    {
        $name = get_bloginfo('name');
        $subject = "[COMPANY] Congratulations! Your subscription has been confirmed. 🎉";
        $message = "Thank you for confirming your subscription! You're now officially part of our community, and you'll start receiving our newsletters, updates, and special offers right in your inbox. We're excited to have you on board, and we look forward to sharing valuable information with you. If you ever have any questions or feedback, don't hesitate to reach out.";
        $subject = str_replace('COMPANY', $name, $subject);

        self::send_email($email, $subject, $message);
    }

    /**
     * @return array
     */
    private static function getInformedData(): array
    {
        $product_category = get_product_category();
        $news_category = get_terms([
            'taxonomy' => 'news-category',
            'hide_empty' => false
        ]);
        $category = get_terms([
            'taxonomy' => 'category',
            'hide_empty' => false
        ]);

        $user_informed = self::find_category_by_user();
        $category_informed = [];
        $channel = [];
        if (!empty($user_informed)) {
            foreach ($user_informed as $informed) {
                $category_informed = unserialize($informed['category']);
                $channel[$informed['channel']] = $informed['channel_value'];
            }
        }

        return array(
            'product_category' => $product_category,
            'news_category' => $news_category,
            'blog_category' => $category,
            'category_informed' => $category_informed,
            'channel' => $channel,
        );
    }

    private static function send_confirm_notification(string $email): void
    {
        $verify_link = add_query_arg([
                'verify_nonce' => wp_create_nonce('verify_informed'),
                'verify_informed' => base64_encode($email),
        ], home_url('/'));
        $name = get_bloginfo('name');
        $subject = "[COMPANY] Subscription Confirmation: Please Confirm Your Email";
        $message = "Hi,
        Thank you for your interest in [COMPANY]!
        To ensure you receive our latest updates. Please take a moment to confirm your subscription by clicking the link below:
        [CONFIRMATION_LINK]
        If you did not request this subscription, you can safely ignore this email.
        If you have any questions or need further assistance, please don't hesitate to reach out to our customer support.
        Thank you for choosing [COMPANY] for your subscription. We look forward to keeping you informed!
        Best regards,
            ";
        $subject = str_replace('COMPANY', $name, $subject);
        $message = str_replace('[COMPANY]', $name, $message);
        $message = str_replace('[CONFIRMATION_LINK]', $verify_link, $message);

        self::send_email($email, $subject, $message);
    }

    public static function confirm_keep_informed(): void
    {
        if (!empty($_GET['verify_informed']) && isset($_GET['verify_nonce'])){
            $email = base64_decode($_GET['verify_informed']);
            $key = sanitize_key('verify_informed_'.$email);
            update_option($key, 'yes');
            self::send_notification($email);

            do_action('saved_user_confirm_keep_informed', ['email' => $email]);

            wp_redirect(home_url('/your-subscription-has-been-confirmed/'));
            exit();
        }
    }

    public static function custom_woocommerce_email_footer($email)
    {
        if (empty($email->recipient)){
            return;
        }
        $unsubscription_link = add_query_arg(
            array(
                'unsubscription' => base64_encode('unsubscription'),
                'data' => urlencode(base64_encode($email->recipient)),
            ),
            home_url('/newsletter-unsubscription')
        );
         ?>
            <p style="font-size: smaller;color: #999;text-align: center; text-decoration: none"><?php printf( '%s', '<a href="' . $unsubscription_link . '">Unsubscription</a>' ); ?></p>

        <?php
    }
    public static function process_unsubscription()
    {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'confirm-unsubscription-template')){
            $email = $_POST['confirm-unsubscriptio-email'];

            global $wpdb;
            $table_name = $wpdb->prefix . 'user_informed';

            $wpdb->query($wpdb->prepare("
                DELETE FROM $table_name
                WHERE channel_value = %s
            ", $email));

            wp_redirect(home_url('/unsubscription'));
            exit();
        }
    }
}
