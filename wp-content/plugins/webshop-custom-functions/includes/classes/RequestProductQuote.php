<?php

class RequestProductQuote
{
    private static $encrytKey = 'WebshopEncryptionKey2023!@#';

    public function __construct()
    {
    }

    public static function create_table(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'request_a_quote';


        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            email VARCHAR(255) NOT NULL,
            name VARCHAR(255),
            company VARCHAR(255),
            company_sector VARCHAR(255),
            company_address VARCHAR(255),
            additional_information TEXT,
            object_id INT,
            object_type VARCHAR(50),
            files TEXT,
            status VARCHAR(20),
            product_of_interest VARCHAR(255),
            when_needed VARCHAR(255),
            volume VARCHAR(255),
            dimension VARCHAR(255),
            part_number VARCHAR(255),
            country_of_origin VARCHAR(255),
            current_suppliers VARCHAR(255),
            potential_order_size VARCHAR(255),
            quote_number VARCHAR(255),
            quote_price VARCHAR(255),
            date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            data_company TEXT,
            data_o_ring TEXT,
            PRIMARY KEY (id)
        );";

        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;

        if (!$table_exists) {
            // Create the table if it doesn't exist
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public static function add_new_quote_filter_column()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'request_a_quote';

        // Check if the column already exists
        $column_exists = $wpdb->get_var("SHOW COLUMNS FROM $table_name LIKE 'quote_filter'");

        if (!$column_exists) {            // Column doesn't exist, add it
            //$alter_sql = " ALTER TABLE $table_name ADD quote_number VARCHAR(255) ";
            $alter_sql = " ALTER TABLE $table_name ADD quote_filter TEXT ";
            $wpdb->query($alter_sql);
        }
    }

    public static function register_request_a_quote_page()
    {
        add_submenu_page(
            'tools.php',
            'Request a Quote',
            'Request a Quote',
            'manage_options',
            'request-a-quote',
            ['RequestProductQuote', 'display_request_a_quote_page']
        );
    }

    private static function get_user_info_with_meta($user_email, $meta_key = 'billing_company')
    {
        global $wpdb;
        $query = $wpdb->prepare(
            "SELECT u.ID as user_id, u.user_email, u.display_name, um.meta_value as company
            FROM {$wpdb->users} u
            LEFT JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
            WHERE u.user_email = %s
            AND um.meta_key = %s",
            $user_email,
            $meta_key
        );
        $result = $wpdb->get_row($query);

        if ($result) {
            $user_id = $result->user_id;
            $display_name = $result->display_name;
            $company = $result->company;

            return array(
                'user_id' => $user_id,
                'display_name' => $display_name,
                $meta_key => $company
            );
        } else {
            return null;
        }
    }

    public
    static function display_request_a_quote_page()
    {
        $args = array(
            'columns' => ['email'],
        );

        $records = self::get($args, ARRAY_A);
        $emails = [];
        if ($records) {
            foreach ($records as $record) {
                if (isset($emails[$record['email']])) continue;
                $user = self::get_user_info_with_meta($record['email']);
                $emails[$record['email']] = $user;
            }
        }

        wc_get_template('template-parts/quote-admin-content.php', ['users' => $emails], '', WBC_PLUGIN_DIR);
    }

    public
    static function cabling_request_quote_callback()
    {
        try {
            $data = $_REQUEST;
            if (
                isset($data['_wp_quote_nonce']) &&
                wp_verify_nonce($data['_wp_quote_nonce'], 'save_request_quote_cabling')
            ) {
                $files = $_FILES;

                $uploaded_files = self::uploadToMedia($files);
                $data['files'] = empty($uploaded_files) ? '' : implode(',', $uploaded_files);

                if (is_user_logged_in()) {
                    $user = wp_get_current_user();
                    $userId = get_master_account_id($user->ID);

                    $data['email'] = $user->user_email;
                    $data['name'] = $user->display_name;
                    $data['company'] = get_user_meta($userId, 'billing_company', true);
                    $data['user_title'] = get_user_meta($userId, 'user_title', true);
                    $data['billing_address_1'] = get_user_meta($userId, 'billing_address_1', true);
                    $data['billing_city'] = get_user_meta($userId, 'billing_city', true);
                    $data['billing_postcode'] = get_user_meta($userId, 'billing_postcode', true);
                    $data['billing_country'] = get_user_meta($userId, 'billing_country', true);
                    $data['billing_phone'] = get_user_meta($userId, 'billing_phone', true);
                    $data['billing_phone_code'] = get_user_meta($userId, 'billing_phone_code', true);
                    $data['company-sector'] = get_user_meta($userId, 'company-sector', true);
                    $data['phone_number'] = get_user_phone_number($userId);
                }

                $data['quote_filter'] = json_decode(base64_decode($data['filter-params'])) ?? [];

                self::saveQuote($data);
                self::sendMail($data['email'], 'Request a quote', $data, $files);

                $message = '<div class="woocommerce-message woo-notice" role="alert">' . __('Request a quote successfully', 'cabling') . '</div>';

                wp_send_json_success($message);
            }
            $message = '<div class="woocommerce-error woo-notice" role="alert">' . __('Something went wrong. Please try again!', 'cabling') . '</div>';
            wp_send_json_error($message);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    private
    static function saveQuote($data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'request_a_quote';

        $data['data_company'] = array(
            'name' => $data['name'] ?? '-',
            'title-function' => $data['title-function'] ?? '-',
            'company' => $data['company'] ?? '-',
            'company-sector' => $data['company-sector'] ?? '-',
            'billing_address_1' => $data['billing_address_1'] ?? '-',
            'billing_city' => $data['billing_city'] ?? '-',
            'billing_postcode' => $data['billing_postcode'] ?? '-',
            'billing_country' => $data['billing_country'] ?? '-',
            'billing_phone' => $data['billing_phone'] ?? '-',
            'billing_phone_code' => $data['billing_phone_code'] ?? '-',
        );

        $data_to_insert = array(
            'email' => sanitize_email($data['email']),
            'name' => sanitize_text_field($data['name']),
            'company' => sanitize_text_field($data['company']),
            'company_sector' => sanitize_text_field($data['company-sector']),
            'company_address' => sanitize_text_field($data['billing_address_1']),
            'additional_information' => sanitize_text_field($data['additional-information']),
            'object_id' => $data['object_id'] ?? 0,
            'object_type' => $data['object_type'] ?? '',
            'files' => $data['files'],
            'quote_price' => sanitize_text_field($data['quote_price']) ?? '',
            'quote_number' => sanitize_text_field($data['quote_number']) ?? '',
            'product_of_interest' => sanitize_text_field($data['product-of-interest']) ?? '',
            'when_needed' => sanitize_text_field($data['when-needed']) ?? '',
            'volume' => sanitize_text_field($data['volume']) ?? '',
            'dimension' => sanitize_text_field($data['dimension']) ?? '',
            'part_number' => sanitize_text_field($data['part-number']) ?? '',
            'country_of_origin' => sanitize_text_field($data['country-of-origin']) ?? '',
            'current_suppliers' => sanitize_text_field($data['current-suppliers']) ?? '',
            'potential_order_size' => sanitize_text_field($data['potential-order-size']) ?? '',
            'data_company' => serialize($data['data_company']),
            'data_o_ring' => serialize($data['o_ring']),
            'quote_filter' => serialize($data['quote_filter']),
            'status' => 'Pending'
        );

        $wpdb->insert($table_name, $data_to_insert);

        return $wpdb->insert_id;
    }

    private
    static function uploadToMedia($files): array
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'];

        if (!file_exists($upload_path)) {
            wp_mkdir_p($upload_path);
        }
        $uploaded_files = array();
        foreach ($files['file']['name'] as $key => $file_name) {
            $file = array(
                'name' => $file_name,
                'type' => $files['file']['type'][$key],
                'tmp_name' => $files['file']['tmp_name'][$key],
                'error' => $files['file']['error'][$key],
                'size' => $files['file']['size'][$key],
            );

            $uploaded_file = media_handle_sideload($file);

            if (!is_wp_error($uploaded_file)) {
                $uploaded_files[] = $uploaded_file;
            }
        }

        return $uploaded_files;
    }

    private
    static function uploadTempFile($files): array
    {
        $attachments = array();
        if (!empty($files['file']['name'])) {
            foreach ($files['file']['tmp_name'] as $key => $tmp_name) {
                $file_name = $files['file']['name'][$key];
                $file_tmp = $files['file']['tmp_name'][$key];

                // Generate a unique filename to prevent overwriting existing files
                $temp_file = wp_unique_filename(wp_upload_dir()['path'], $file_name);
                move_uploaded_file($file_tmp, $temp_file);

                // Add the file to the attachment array
                $attachments[] = $temp_file;
            }
        }
        return $attachments;
    }

    public
    static function get_quote_data_ajax_callback()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'request_a_quote';
        $data = $_REQUEST;

        $order_by_column = 'email';
        $order_direction = 'asc';
        if (!empty($data['order'][0])) {
            $order_column = $data['order'][0]['column'] ?? 0;
            $order_direction = $data['order'][0]['dir'] ?? '';
            $order_by_column = $data['columns'][$order_column]['data'] ?? '';
        }

        $status_filter = $data['columns'][0]['search']['value'] ?? null;
        $email_filter = $data['columns'][1]['search']['value'] ?? null;
        $date_filter = $data['columns'][2]['search']['value'] ?? null;

        $search_string = $data['search']['value'] ?? '';

        $args = array(
            'order_by' => $order_by_column,
            'order' => $order_direction,
            'status' => $status_filter,
            'date' => $date_filter,
            'email' => $email_filter,
            'search' => $search_string,
        );

        $recordsFiltered = self::get($args);

        $args['start'] = (int)$data['start'];
        $args['length'] = (int)$data['length'];

        $records = self::get($args);

        $recordsTotal = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        $response = [
            'data' => $records,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => sizeof($recordsFiltered),
        ];

        wp_send_json($response);
    }

    public
    static function get($args = [], $type = OBJECT): array|object|null
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'request_a_quote';

        $offset = empty($args['start']) ? null : (int)$args['start'];
        $limit = empty($args['length']) ? null : (int)$args['length'];
        $order_by_column = $args['order_by'] ?? 'date';
        $order_direction = $args['order'] ?? 'asc';

        $columns = empty($args['columns']) ? '*' : implode(',', $args['columns']);

        $sql = "SELECT $columns FROM $table_name";
        $sql .= " WHERE 1=1 ";

        if (!empty($args['search'])) {
            $like_conditions = array();
            $columns = array('email', 'name', 'company', 'company_sector', 'company_address', 'additional_information', 'object_id', 'object_type', 'quote_number', 'quote_price');

            foreach ($columns as $column) {
                $like_conditions[] = "$column LIKE '%" . esc_sql($args['search']) . "%'";
            }

            $sql .= " AND (" . implode(' OR ', $like_conditions) . ")";
        }

        if (!empty($args['status'])) {
            $sql .= $wpdb->prepare(" AND status = %s", $args['status']);
        }

        if (!empty($args['email'])) {
            $sql .= $wpdb->prepare(" AND email = %s", $args['email']);
        }

        if (!empty($args['date'])) {
            $sql .= " AND DATE(date) = '" . esc_sql($args['date']) . "'";
        }

        $sql .= " ORDER BY $order_by_column $order_direction";

        if ($offset && $limit)
            $sql .= " LIMIT $offset, $limit";

        return $wpdb->get_results($sql, $type);
    }

    private
    static function sendMail($to, $subject, $data, $files)
    {
        $mailer = WC()->mailer();

        $to = sanitize_email($to);
        $subject = sanitize_text_field($subject);

        ob_start();
        include WBC_PLUGIN_DIR . '/template-parts/email-quote-content.php';
        $content = ob_get_clean();

        $content = wp_kses_post($content);

        $mailer->recipient = $to;

        // Check if there are uploaded files
        $attachments = self::uploadTempFile($files);
        $admin_email = get_option('admin_email');
        $emails = [$admin_email, $to];
        $type = 'emails/reply-contact-form.php';
        $mail_content = cabling_get_custom_email_html('', $subject, $mailer, $type, $content);
        $headers = "Content-Type: text/html\r\n";

        $mailer->send(implode(',', $emails), $subject, $mail_content, $headers, $attachments);
    }

    public
    static function cabling_add_product_quote_popup()
    {
        wc_get_template('template-parts/quote-product-popup.php', [], '', WBC_PLUGIN_DIR);
    }

    public
    static function cabling_get_product_quote_modal_callback()
    {
        ob_start();
        $is_user_logged_in = is_user_logged_in();
        $args = [];
        $post_type = get_post_type($_REQUEST['data']);
        $object = [
            'object_id' => $_REQUEST['data'],
        ];

        $args['filter_params'] = $_REQUEST['filter_params'] ?? [] ;

        if ($post_type === 'product') {
            $product = wc_get_product($_REQUEST['data']);
            $product_material = get_field('product_material', $product->get_id());
            $object['object_type'] = 'product';
            $inches_id = get_field('inches_id', $product->get_id());
            $inches_od = get_field('inches_od', $product->get_id());
            $inches_width = get_field('inches_width', $product->get_id());
            $args['product'] = $product;
            $args['product_of_interest'] = $product->get_sku();
            $args['material'] = $product_material ? get_the_title($product_material) : '';
            $args['hardness'] = get_field('product_hardness', $product->get_id());
            $args['temperature'] = get_field('product_operating_temp', $product->get_id());
            $args['dimension'] = sprintf('%sx%sx%s', $inches_id, $inches_od, $inches_width);
        } else {
            $args['post'] = get_post($_REQUEST['data']);

            $object['object_type'] = $args['post']->post_type;
        }
        $args['object'] = $object;
        $args['is_user_logged_in'] = $is_user_logged_in;

        if ($is_user_logged_in) {
            $user = wp_get_current_user();
            $userId = get_master_account_id($user->ID);

            $args['email'] = $user->user_email;
            $args['name'] = $user->display_name;
            $args['company'] = get_user_meta($userId, 'billing_company', true);
            $args['user_title'] = get_user_meta($userId, 'user_title', true);
            $args['billing_address_1'] = get_user_meta($userId, 'billing_address_1', true);
            $args['billing_city'] = get_user_meta($userId, 'billing_city', true);
            $args['billing_postcode'] = get_user_meta($userId, 'billing_postcode', true);
            $args['billing_country'] = get_user_meta($userId, 'billing_country', true);
            $args['billing_phone'] = get_user_meta($userId, 'billing_phone', true);
            $args['billing_phone_code'] = get_user_meta($userId, 'billing_phone_code', true);
            $args['phone_number'] = get_user_phone_number($userId);
        }
        wc_get_template('template-parts/quote-product-content.php', $args, '', WBC_PLUGIN_DIR);
        $content = ob_get_clean();
        wp_send_json_success($content);
    }
}

new RequestProductQuote();
