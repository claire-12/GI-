<?php
function cabling_create_webshop_user_ajax_callback(): void
{
    parse_str($_REQUEST['data'], $data);

    $err = false;
    $message = '';
    if (email_exists($data['user_email'])) {
        $err = true;
        //$message = '<div class="woocommerce-error woo-notice" role="alert">' . sprintf(__('The email <strong>%s</strong> was registered, please try with others.', 'cabling'), $data['user_email']) . '</div>';
        $message = '<div class="woocommerce-error woo-notice" role="alert">' . sprintf(__('We already have an account registered under %s . Please log in with the password linked to this  account.', 'cabling'), $data['user_email']) . '</div>';

    } else {
        $parent_id = get_current_user_id();
        $sap_no = get_user_meta($parent_id, 'sap_no', true);
        $group = get_user_meta($parent_id, 'wcb2b_group', true);
        $password = wp_generate_password();
        $user_data = array(
            'customer_parent' => $parent_id,
            'customer_level' => '1',
            'sap_no' => $sap_no,
            'wcb2b_group' => $group,
            'has_approve' => 'false',
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'billing_first_name' => $data['first_name'],
            'billing_last_name' => $data['last_name'],
            'billing_phone' => $data['billing_phone'],
            'billing_phone_code' => $data['billing_phone_code'],
            'job_title' => $data['job-title'],
            'user_department' => $data['user-department'],
            'user_title' => $data['user-title'],
            'user_telephone' => $data['user_telephone'],
            'user_telephone_code' => $data['user_telephone_code'],
            'display_name' => $data['first_name'] . ' ' . $data['last_name'],
            'nickname' => $data['first_name'] . ' ' . $data['last_name'],
        );
        $customer_id = wc_create_new_customer($data['user_email'], $data['user_email'], $password, ['meta_input' => $user_data]);

        send_verify_email($data['user_email'], $customer_id);

        $message = '<div class="woocommerce-message woo-notice" role="alert">' . __('Registration successful!', 'cabling') . '</div>';
    }

    $response = array(
        'error' => $err,
        'message' => $message,
    );
    wp_send_json($response);
}

add_action('wp_ajax_cabling_create_webshop_user_ajax', 'cabling_create_webshop_user_ajax_callback');

function cabling_delete_webshop_user_ajax_callback(): void
{
    $email = $_REQUEST['data'];
    $user = get_user_by('email', $email);
    if ($user) {
        wp_delete_user($user->ID);
    }

    $response = array(
        'success' => true,
    );
    wp_send_json($response);
}

add_action('wp_ajax_cabling_delete_webshop_user_ajax', 'cabling_delete_webshop_user_ajax_callback');


function get_contact_group()
{
    global $wpdb;
    $search = $_POST['group_name'] ?? '';
    $results = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'contact_group' GROUP BY meta_value");
    $html = '<ul>';
    if (!empty($results)) {
        foreach ($results as $value) {
            if (strpos(strtolower($value->meta_value), strtolower($search)) !== false) {
                $html .= '<li>' . $value->meta_value . '</li>';
            }
        }
    }
    $html .= '</ul>';
    wp_send_json($html);
}

add_action('wp_ajax_get_contact_group', 'get_contact_group');
add_action('wp_ajax_nopriv_get_contact_group', 'get_contact_group');

function cabling_login_ajax_callback()
{
    parse_str($_REQUEST['data'], $data);

    $verify_recaptcha = cabling_verify_recaptcha($data['g-recaptcha-response']);

    $err = false;
    $redirect_to = '';
    if ($verify_recaptcha) {
        if (empty($data['log']) || empty($data['pwd'])) {
            $err = true;
            $mess = '<div class="woo-notice alert alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . __('Please check your Email or Password.', 'cabling') . '</div>';
        } else {
            $creds = array(
                'user_login' => $data['log'],
                'user_password' => $data['pwd'],
                'remember' => $data['rememberme']
            );

            /*$user = get_user_by('email', $data['log']);
            do_action( 'wp_login', $data['log'], $user );*/

            $user = wp_signon($creds, is_ssl());

            if (is_wp_error($user)) {
                if ($user->get_error_code() === 'invalid_email') {
                    $error = __('Unknown email address. Please check again!', 'cabling');
                } else {
                    $error = $user->get_error_message();
                }
                $err = true;
                $mess = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . $error . '</div>';
            } else {

                $redirect_to = $data['_wp_http_referer'] ?? wc_get_account_endpoint_url('');
                $mess = '<div class="alert woo-notice alert-success" role="alert"><i class="fa-solid fa-circle-check me-2"></i>' . __('Success! Redirecting...', 'cabling') . '</div>';
            }
        }
    } else {
        $err = true;
        $mess = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . __('reCAPTCHA verification failed. Please try again!', 'cabling') . '</div>';
    }

    $response = array(
        'redirect' => $redirect_to,
        'error' => $err,
        'mess' => $mess
    );
    wp_send_json($response);
}

add_action('wp_ajax_nopriv_cabling_login_ajax', 'cabling_login_ajax_callback');
function cabling_register_account_ajax_callback()
{
    parse_str($_REQUEST['data'], $data);

    $verify_recaptcha = cabling_verify_recaptcha($data['g-recaptcha-response']);

    if (empty($verify_recaptcha)) {
        $message = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . __('reCAPTCHA verification failed. Please try again!', 'cabling') . '</div>';
        wp_send_json_error($message);
    }
    $recipient = $data['register_email'];

    $register = home_url('/register/');
    if (!email_exists($recipient)) {
        $email = urlencode($recipient);
        $hash = MD5($recipient . CABLING_SECRET);

        $arg = json_encode(array('email' => $email, 'code' => $hash));

        $verify_link = add_query_arg(array(
            'code' => base64_encode($arg),
        ), $register);

        set_transient($recipient, $hash, DAY_IN_SECONDS);

        // load the mailer class
        $mailer = WC()->mailer();
        $mailer->recipient = $recipient;
        $type = 'emails/confirm_create_account.php';
        $subject = __("Datwyler Sealing Solutions: Confirming Your My Account Opening Request", 'cabling');
        $content = cabling_get_custom_email_html($verify_link, $subject, $mailer, $type);
        $headers = "Content-Type: text/html\r\n";

        $mailer->send($recipient, $subject, $content, $headers);

        //$message = '<div class="alert woo-notice alert-success" role="alert"><i class="fa-solid fa-circle-check me-2"></i>' . sprintf(__('A confirmation email has been sent to your mailbox <strong>%s</strong><br> Please check your email box and continue your registration within 24 hours', 'cabling'), $recipient) . '</div>';
        $message = '<div class="alert woo-notice alert-success" role="alert"><i class="fa-solid fa-circle-check me-2"></i>' . __('Thanks for requesting access to Datwyler My Account. We follow tough standards in how we manage your data at Datwyler. That’s why you’ll now receive an e-mail from us to confirm your request. If you don’t receive a message, please check your junk folder.', 'cabling') . '</div>';
        wp_send_json_success($message);
    } else {
        //$message = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . sprintf(__('The email <strong>%s</strong> was registered, please try with others.', 'cabling'), $recipient) . '</div>';
        $message = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . sprintf(__('We already have an account registered under %s . Please log in with the password linked to this  account.', 'cabling'), $recipient) . '</div>';

        wp_send_json_error($message);
    }
    wp_die();
}

add_action('wp_ajax_nopriv_cabling_register_account_ajax', 'cabling_register_account_ajax_callback');
function cabling_confirm_recaptcha_ajax_callback()
{
    $verify_recaptcha = cabling_verify_recaptcha($_REQUEST['recaptcha']);

    if (empty($verify_recaptcha)) {
        $message = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . __('reCAPTCHA verification failed. Please try again!', 'cabling') . '</div>';
        wp_send_json_error($message);
    } else {
        wp_send_json_success();
    }
    wp_die();
}

add_action('wp_ajax_nopriv_cabling_confirm_recaptcha_ajax', 'cabling_confirm_recaptcha_ajax_callback');
function cabling_register_new_account_ajax_callback()
{
    parse_str($_REQUEST['data'], $data);

    $verify_recaptcha = cabling_verify_recaptcha($data['g-recaptcha-response']);

    if (empty($verify_recaptcha)) {
        $message = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . __('reCAPTCHA verification failed. Please try again!', 'cabling') . '</div>';
        wp_send_json_error($message);
    }

    $user_data = array(
        'has_approve' => 'false',
        'customer_level' => '1',
        'first_name' => $data['first-name'],
        'last_name' => $data['last-name'],
        'billing_first_name' => $data['first-name'],
        'shipping_first_name' => $data['first-name'],
        'billing_last_name' => $data['last-name'],
        'shipping_last_name' => $data['last-name'],
        'billing_phone' => $data['billing_phone'],
        'billing_phone_code' => $data['billing_phone_code'],
        'billing_company' => $data['company-name'],
        'shipping_company' => $data['company-name'],
        'billing_address_1' => $data['billing_address_1'],
        'shipping_address_1' => $data['billing_address_1'],
        'billing_country' => $data['billing_country'],
        'shipping_country' => $data['billing_country'],
        'billing_city' => $data['billing_city'],
        'shipping_city' => $data['billing_city'],
        'billing_state' => $data['billing_state'],
        'shipping_state' => $data['billing_state'],
        'billing_postcode' => $data['billing_postcode'],
        'shipping_postcode' => $data['billing_postcode'],
        'function' => $data['function'],
        'billing_vat' => $data['billing_vat'],
        'job_title' => $data['job-title'],
        // JM 20230914
        'display_name' => $data['first-name'] . ' ' . $data['last-name'],
        'nickname' => $data['first-name'] . ' ' . $data['last-name'],
    );

    if (!empty($data['existing-customer']) && !empty($data['client-number'])) {
        $user_data['client-number'] = $data['client-number'];
    }

    $customer_id = wc_create_new_customer(
        $data['user_email'],
        $data['user_email'],
        $data['password'],
        [
            'meta_input' => $user_data
        ]
    );

    if ($customer_id) {
        $data['customer_id'] = $customer_id;
        do_action('gi_created_new_customer', $data);

        //JM 20230914
        wp_update_user(array('ID' => $customer_id, 'display_name' => $data['first-name'] . ' ' . $data['last-name']));


        delete_transient(urldecode($data['user_email']));

        //send email to customer
        if (isset($data['verify-nounce'])) {
            $mailer = WC()->mailer();
            $mailer->recipient = $data['user_email'];
            $verify_link = '';
            $type = 'emails/register-verify.php';
            $recipient = get_option('admin_email');
            $subject = __("New account need to verify!", 'cabling');
            $content = cabling_get_custom_email_html($verify_link, $subject, $mailer, $type);
            $headers = "Content-Type: text/html\r\n";

            $mailer->send($recipient, $subject, $content, $headers);
        }

        $message = '<div class="alert woo-notice alert-success" role="alert"><i class="fa-solid fa-circle-check me-2"></i>' . __('Thanks for signing up to My Account – just click on the <a href="/my-account/">link</a> to log in and explore.', 'cabling') . '</div>';
        wp_send_json_success($message);
    }

    $message = '<div class="alert woo-notice alert-danger" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>' . __('Something went wrong. Please try again!', 'cabling') . '</div>';
    wp_send_json_error($message);
}

add_action('wp_ajax_nopriv_cabling_register_new_account_ajax', 'cabling_register_new_account_ajax_callback');
add_action('wp_ajax_cabling_register_new_account_ajax', 'cabling_register_new_account_ajax_callback');

function cabling_verify_user_ajax()
{
    $user_id = (int)$_REQUEST['data'];

    $user = get_user_by('id', $user_id);
    $err = false;
    if ($user) {
        update_user_meta($user->ID, 'has_approve', 'true');
        update_user_meta($user->ID, 'customer_level', '2');
        update_user_meta($user->ID, 'has_approve_date', current_time('mysql'));
        send_email_verified_success($user->ID);
        $mess = 'Verify successfully!';
    } else {
        $err = true;
        $mess = 'Something went wrong! Please try again.';
    }

    $response = array(
        'error' => $err,
        'mess' => $mess
    );
    wp_send_json($response);
}

add_action('wp_ajax_cabling_verify_user_ajax', 'cabling_verify_user_ajax');

function cabling_get_product_single_ajax_callback()
{
    $product_id = (int)$_REQUEST['product'];

    global $product;
    $product = wc_get_product($product_id);

    $status = true;
    if ($product) {
        ob_start();
        wc_get_template('content-quickview.php');
        $data = ob_get_clean();
    } else {
        $status = false;
    }

    $response = array(
        'status' => $status,
        'data' => $data
    );
    wp_send_json($response);
}

add_action('wp_ajax_cabling_get_product_single_ajax', 'cabling_get_product_single_ajax_callback');
add_action('wp_ajax_nopriv_cabling_get_product_single_ajax', 'cabling_get_product_single_ajax_callback');

function cabling_filter_product_ajax_callback()
{
    parse_str($_REQUEST['data'], $data);

    if (!empty($data)) {
        global $wp_query;

        $paged = isset($_REQUEST['num']) ? (int)$_REQUEST['num'] : 1;

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'paged' => $paged,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $_REQUEST['filter']
                )
            ),
            'meta_query' => array(
                'relation' => 'AND',
            ),
        );

        foreach ($data as $key => $meta) {
            if (empty($meta) || empty($key))
                continue;

            $args['meta_query'][] = array(
                'key' => $key,
                'value' => $meta,
            );
        }

        $wp_query = new WP_Query($args);
        if ($wp_query->have_posts()) {
            ob_start();
            while ($wp_query->have_posts()) {
                $wp_query->the_post();

                if ('Grid' === get_field('_woo_product_list', 'option'))
                    wc_get_template_part('content', 'product-grid');
                else
                    wc_get_template_part('content', 'product');
            }
            $result = ob_get_clean();

            ob_start();
            $total_pages = $wp_query->max_num_pages;
            $current_page = max(1, $paged);
            if ($total_pages > 1): global $wp; ?>
                <div class="wp-pagenavi filter-pagination" role="navigation">
                    <?php for ($i = 1; $i <= $total_pages; $i++) {
                        $url = home_url(add_query_arg(array('num' => $i), $wp->request));

                        if ($current_page != $i)
                            echo '<a class="page filter-page larger" data-paged="' . $i . '" href="' . esc_url($url) . '">' . $i . '</a>';
                        else
                            echo '<span class="page larger current">' . $i . '</span>';

                    } ?>
                </div>
            <?php endif;
            $pagination = ob_get_clean();
        }
        wp_reset_postdata();
    }

    $response = array(
        'pagination' => $pagination,
        'data' => $result,
        'args' => $args,
    );
    wp_send_json($response);
}

add_action('wp_ajax_cabling_filter_product_ajax', 'cabling_filter_product_ajax_callback');
add_action('wp_ajax_nopriv_cabling_filter_product_ajax', 'cabling_filter_product_ajax_callback');

function search_ajax()
{
    $search_query = isset($_REQUEST['key_search']) ? sanitize_text_field($_REQUEST['key_search']) : null;
    $paged = (int)$_REQUEST['paged'] ?? 1;

    $posts_per_page = 5;
    $data = [];
    $post_type = [];
    $tax_query = [];
    if (!empty($_REQUEST['data'])) {
        parse_str($_REQUEST['data'], $data);

        if (empty($data['search-all'])) {
            if (!empty($data['search-blog'])) {
                $post_type[] = 'post';
            }
            if (!empty($data['search-news'])) {
                $post_type[] = 'company_news';
            }
            if (!empty($data['search-product'])) {
                $post_type[] = 'product';

                if (!empty($data['product_cat'])) {
                    $tax_query = array(
                        array(
                            'taxonomy' => 'product_group',
                            'field' => 'term_id',
                            'terms' => $data['product_group'],
                        )
                    );
                }
            }
        }
    }

    $args = [
        's' => $search_query,
        'posts_per_page' => $posts_per_page,
        'post_type' => $post_type,
        'tax_query' => $tax_query,
        'paged' => $paged,
    ];

    // If a search query is present use SWP_Query
    // else fall back to WP_Query
    if (!empty($search_query)) {
        $swp_query = new SWP_Query($args);
    } else {
        $swp_query = new WP_Query($args);
    }
    $pagination = '';
    ob_start();
    if ($swp_query->have_posts()) {
        while ($swp_query->have_posts()) :
            $swp_query->the_post();

            $post_type = get_post_type();
            switch ($post_type) {
                case 'post':
                    $post_type_name = __('Blog', 'cabling');
                    break;
                case 'company_news':
                    $post_type_name = __('News', 'cabling');
                    break;
                case 'production-equipment':
                    $post_type_name = __('Production Equipment', 'cabling');
                    break;
                case 'gi_learn':
                    $post_type_name = __('Learns', 'cabling');
                    break;
                case 'page':
                    $page_id = wp_get_post_parent_id();
                    $post_type_name = get_the_title($page_id);
                    break;
                default:
                    $post_type_name = $post_type;
                    break;
            }
            $title = get_the_title();
            $content = wp_trim_words(get_the_content(), 40);
            ?>
            <div class="search-result post-item">
                <div class="entry-content row">
                    <div class="featured-image col-12 col-lg-4">
                        <a href="<?php echo get_permalink(); ?>">
                            <?php if (has_post_thumbnail()): the_post_thumbnail(); ?>
                            <?php else: echo wp_get_attachment_image(1032601); endif; ?>
                        </a>
                    </div>
                    <div class="info col-12 col-lg-8">
                        <div class="post-type">
                            <h5><?php echo $post_type_name ?></h5>
                        </div>
                        <h4><a href="<?php echo get_permalink(); ?>"><?php echo $title; ?></a></h4>
                        <div class="meta"><?php echo the_date('M d, Y'); ?></div>
                        <div class="desc"><?php echo $content; ?></div>
                        <?php //echo do_shortcode('[Sassy_Social_Share url="' . get_permalink() . '"]')
                        ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        <?php
        // Output pagination links
        $total_posts = $swp_query->found_posts;
        $current_page = max(1, $paged);
        $start_post = min(($current_page - 1) * $posts_per_page + 1, $total_posts);
        $end_post = min($current_page * $posts_per_page, $total_posts);

        $pagination .= '<div class="pagination">';
        $pagination .= '<div class="pagination-info">';
        $pagination .= sprintf('%d-%d of %d', $start_post, $end_post, $total_posts);
        $pagination .= '</div>';
        // Previous link
        if ($current_page > 1) {
            $prev_page = $current_page - 1;
            $pagination .= '<a href="#" data-action="' . $prev_page . '" class="filter-pagination prev"><i class="fa-light fa-chevron-left"></i></a>';
        }

        // Next link
        if ($current_page < $swp_query->max_num_pages) {
            $next_page = $current_page + 1;
            $pagination .= '<a href="#" data-action="' . $next_page . '" class="filter-pagination next"><i class="fa-light fa-chevron-right"></i></a>';
        }

        $pagination .= '</div>';
    } else { ?>
        <p class="mb-0"><?php echo esc_attr_x('Sorry – we can’t find directly what you’re looking for, but we’ve provided further information related to your search.', 'submit button') ?></p>
        <?php
    }
    $data = ob_get_clean();
    $response = array(
        'data' => $data,
        'search_query' => $search_query,
        'pagination' => $pagination,
    );
    wp_send_json($response);
}

add_action('wp_ajax_search_ajax', 'search_ajax');
add_action('wp_ajax_nopriv_search_ajax', 'search_ajax');

function cabling_get_customer_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            $customer_email = $_REQUEST['data'];
            $customer = get_user_by('email', $customer_email);
            $customer_meta = get_user_meta($customer->ID);

            $data = [];
            foreach ($customer_meta as $key => $value) {
                if (in_array($key, ['customer_parent', 'rich_editing', 'show_admin_bar_front', 'use_ssl', 'verification_key', 'ws_capabilities', 'ws_user_level', 'comment_shortcuts']))
                    continue;
                $data[$key] = $value[0];
            }

            ob_start();
            include_once get_template_directory() . "/woocommerce/myaccount/popup/edit-customer.php";
            $modal_content = ob_get_clean();

            wp_send_json_success($modal_content);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_get_customer_ajax', 'cabling_get_customer_ajax_callback');

function cabling_update_customer_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);
            update_user_meta($data['customer_id'], 'user_title', $data['user_title']);
            update_user_meta($data['customer_id'], 'first_name', $data['billing_first_name']);
            update_user_meta($data['customer_id'], 'billing_first_name', $data['billing_first_name']);
            update_user_meta($data['customer_id'], 'last_name', $data['billing_last_name']);
            update_user_meta($data['customer_id'], 'billing_last_name', $data['billing_last_name']);
            update_user_meta($data['customer_id'], 'job_title', $data['job_title']);
            update_user_meta($data['customer_id'], 'user_department', $data['user_department']);
            update_user_meta($data['customer_id'], 'user_telephone', str_replace(' ', '', $data['user_telephone']));
            update_user_meta($data['customer_id'], 'user_telephone_code', $data['user_telephone_code']);
            update_user_meta($data['customer_id'], 'billing_phone', str_replace(' ', '', $data['billing_phone']));
            update_user_meta($data['customer_id'], 'billing_phone_code', $data['billing_phone_code']);

            wp_update_user(array('ID' => $data['customer_id'], 'display_name' => $data['billing_first_name'] . ' ' . $data['billing_last_name']));

            wp_send_json_success($data);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_update_customer_ajax', 'cabling_update_customer_ajax_callback');
function cabling_share_page_email_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);

            $verify_recaptcha = cabling_verify_recaptcha($data['g-recaptcha-response']);

            if (empty($verify_recaptcha)) {
                wp_send_json_error('Please verify the Captcha.');
            }

            $mailer = WC()->mailer();
            $mailer->recipient = $data['to'];
            $type = 'emails/share-this-page.php';
            $content = cabling_get_custom_email_html('', $data['subject'], $mailer, $type, $data['message_content']);
            $headers = "Content-Type: text/html\r\n";

            $mailer->send($data['to'], $data['subject'], $content, $headers);

            $message = '<div class="woocommerce-message woo-notice" role="alert">' . __('Share successful!', 'cabling') . '</div>';

            wp_send_json_success(array(
                'data' => $message,
            ));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_share_page_email_ajax', 'cabling_share_page_email_ajax_callback');
add_action('wp_ajax_nopriv_cabling_share_page_email_ajax', 'cabling_share_page_email_ajax_callback');
function cabling_load_blog_ajax_callback()
{
    check_ajax_referer('cabling-ajax-nonce', 'nonce');
    try {
        parse_str($_REQUEST['data'], $data);

        $page = (int)$_REQUEST['paged'];
        $posts_per_page = $data['posts_per_page'] ?? get_option('posts_per_page');
        $paged = $_REQUEST['load_more'] === 'false' ? 1 : ++$page;
        $total_posts = $posts_per_page * $paged;
        $post_type = $data['post_type'] ?: 'post';
        $filter_params = [];

        if ($data['order'] === 'newest')
            $order = 'desc';
        else
            $order = 'asc';

        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish',
            'order' => $order,
            'tax_query' => array(
                'relation' => 'AND'
            ),
        );
        if ($post_type === 'post') {
            $args['category_name'] = 'blog';
        }
        if (!empty($data['from-date']) && !empty($data['to-date'])) {
            $date = explode(' to ', $data['from-date']);

            $args['date_query'] = array(
                array(
                    'after' => $date[0],
                    'before' => $date[1],
                    'inclusive' => true,
                ),
            );
            $from_date = DateTime::createFromFormat('Y-m-d', $date[0]);
            $to_date = DateTime::createFromFormat('Y-m-d', $date[1]);
            $filter_params[] = '<div class="item item-date me-2 mb-2" data-action="8028">' . $from_date->format('Y') . ' - ' . $to_date->format('Y') . '<span class="clear ms-1"><i class="fa-thin fa-circle-xmark"></i></span></div>';
        }

        if (!empty($data['category'])) {
            $args['cat'] = implode(',', $data['category']);
            $categories = get_terms([
                'taxonomy' => 'category',
                'include' => $data['category']
            ]);
            foreach ($categories as $category) {
                $filter_params[] = '<div class="item item-cat me-2 mb-2" data-action="' . $category->term_id . '">' . ucfirst($category->name) . '<span class="clear ms-1"><i class="fa-thin fa-circle-xmark"></i></span></div>';
            }
        }

        if (!empty($data['news-category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'news-category',
                'field' => 'term_id',
                'terms' => $data['news-category'],

            );
            $news_cat = get_terms([
                'taxonomy' => 'news-category',
                'include' => $data['news-category']
            ]);
            foreach ($news_cat as $cat) {
                $filter_params[] = '<div class="item item-cat me-2 mb-2" data-action="' . $cat->term_id . '">' . ucfirst($cat->name) . '<span class="clear ms-1"><i class="fa-thin fa-circle-xmark"></i></span></div>';
            }
        }

        if (!empty($data['news_tag'])) {
            $args['tax_query'][] =
                array(
                    'taxonomy' => 'news_tag',
                    'field' => 'term_id',
                    'terms' => $data['news_tag'],
                );
            $news_tags = get_terms([
                'taxonomy' => 'news_tag',
                'include' => $data['news_tag']
            ]);
            foreach ($news_tags as $tagn) {
                $filter_params[] = '<div class="item item-cat me-2 mb-2" data-action="' . $tagn->term_id . '">' . ucfirst($tagn->name) . '<span class="clear ms-1"><i class="fa-thin fa-circle-xmark"></i></span></div>';
            }
        }

        if (!empty($data['tag'])) {
            $args['tag__in'] = $data['tag'];
            $tags = get_terms([
                'taxonomy' => 'post_tag',
                'include' => $data['tag']
            ]);
            foreach ($tags as $tag) {
                $filter_params[] = '<div class="item item-cat me-2 mb-2" data-action="' . $tag->term_id . '">' . ucfirst($tag->name) . '<span class="clear ms-1"><i class="fa-thin fa-circle-xmark"></i></span></div>';
            }
        }

        if ($filter_params && count($filter_params)) {
            $filter_clear = '<div class="clear-item me-2 mb-2">' . sprintf(__('Applied filters (%d)', 'cabling'), count($filter_params)) . '<a class="ms-1" href="javascript:void(0)">' . __('Clear all', 'cabling') . '</a></div>';
            $filter_params = $filter_clear . implode('', $filter_params);
        } else {
            $filter_params = '';
        }

        $query = new WP_Query($args);
        ob_start();
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                get_template_part('template-parts/ajax/content', $post_type);
            endwhile;
        else :
            echo '<div class="woocommerce-no-products-found">
                            <div class="woocommerce-info">
                                ' . __('No blog was found matching your selection.', 'cabling') . '
                            </div>
                        </div>';
        endif;
        wp_reset_postdata();
        $posts = ob_get_clean();

        if ($paged === 1) {
            $found_posts = $query->post_count;
        } else if ($query->max_num_pages == $paged) {
            $found_posts = $query->found_posts;
        } else {
            $found_posts = $total_posts;
        }

        wp_send_json_success(array(
            'posts' => $posts,
            'paged' => $paged,
            'found_posts' => $query->found_posts,
            'filter_params' => $filter_params,
            'load_more.' => $_REQUEST['load_more'],
            'number_posts' => sprintf(__('Showing %s of %s Articles', 'cabling'), $found_posts, $query->found_posts),
            'last_paged' => $query->max_num_pages == $paged,
        ));
    } catch (Exception $e) {
        wp_send_json_error($e->getMessage());
    }
}

add_action('wp_ajax_cabling_load_blog_ajax', 'cabling_load_blog_ajax_callback');
add_action('wp_ajax_nopriv_cabling_load_blog_ajax', 'cabling_load_blog_ajax_callback');
function cabling_resend_verify_email_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);
            $user_id = $_REQUEST['data'];
            $user_email = $_REQUEST['email'];

            send_verify_email($user_email, $user_id);

            $message = '<div class="woocommerce-message woo-notice" role="alert">' . __('Resend successfully!', 'cabling') . '</div>';

            wp_send_json_success($message);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_resend_verify_email_ajax', 'cabling_resend_verify_email_ajax_callback');
function cabling_reset_password_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);
            $current_user = wp_get_current_user();


            // Check the old password
            $user = wp_authenticate($current_user->user_email, $data['old-password']);

            if (is_wp_error($user)) {
                $message = '<div class="alert woo-notice alert-danger d-flex align-items-center" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <div>
                        ' . __('Old password is incorrect.', 'cabling') . '
                    </div>
                </div>';
                wp_send_json_error($message . $user->get_error_message());
            }

            // Update the password
            wp_set_password($data['new-password'], get_current_user_id());

            wp_set_auth_cookie($current_user->ID);

            $message = '<div class="alert woo-notice alert-success d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            <div>
                                ' . __('Password updated successfully!', 'cabling') . '
                            </div>
                        </div>';
            wp_send_json_success($message);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_reset_password_ajax', 'cabling_reset_password_ajax_callback');

function cabling_get_products_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);
            $productTypeId = $_REQUEST['category'] ?? 0;

            if (empty($data['attributes']['nominal_size_id'])) {
                unset($data['attributes']['nominal_size_id']);
            }
            if (empty($data['attributes']['nominal_size_od'])) {
                unset($data['attributes']['nominal_size_od']);
            }
            if (empty($data['attributes']['nominal_size_width'])) {
                unset($data['attributes']['nominal_size_width']);
            }
            $total = 0;
            $isSizeFilter = false;
            $results = '';
            $termFilters = [];
            $product_ids = [];

            if (empty($productTypeId)) {
                $productGroupIds = [];
                if (!empty($data['group-type'])) {
                    $productGroupIds = $data['group-type'];
                }

                if (!empty($data['attributes'])) {
                    $isSizeFilter = checkFilterHasSize($data['attributes']);
                    //$product_compound = [];
                    if (!empty($data['attributes']['product_compound'])) {
                        $certifications = $data['attributes']['product_compound'];
                        $data['attributes']['compound_certification'] = array_shift($certifications);
                        $compounds = get_compound_product($data['attributes']['product_compound']);
                        $data['attributes']['product_compound'] = $compounds;
                    }

                    if (!empty($data['attributes']['product_compound_single'])) {
                        if (empty($data['attributes']['product_compound'])) {
                            $data['attributes']['product_compound'] = $data['attributes']['product_compound_single'];
                        } else {
                            $data['attributes']['product_compound'] = array_merge($data['attributes']['product_compound'], $data['attributes']['product_compound_single']);
                        }

                        unset($data['attributes']['product_compound_single']);
                    }

                    $product_ids = search_product_by_meta($data['attributes']);

                    if (!empty($data['attributes']['product_contact_media'])) {
                        foreach ($data['attributes']['product_contact_media'] as $media) {
                            $lines = get_the_terms($media, 'product_line');
                            if ($lines) {
                                foreach ($lines as $line) {
                                    $productLines[] = $line;
                                }
                            }
                        }
                    } else {
                        $productGroupIncludes = get_term_ids_by_attributes($product_ids, 'product_line');
                        if (!empty($productGroupIncludes)) {
                            $productLines = get_product_line_category('product_line', 'group_category', $productGroupIds, false, $productGroupIncludes);
                        }
                    }

                } else {
                    $productLines = get_product_line_category('product_line', 'group_category', $productGroupIds);
                }

                if (isset($productLines) && is_array($productLines)) {
                    ob_start();
                    foreach ($productLines as $line) {
                        $productLineIds = [$line->term_id];

                        if (!empty($data['attributes'])) {
                            $productTypeIncludes = get_term_ids_by_attributes($product_ids, 'product_custom_type');
                            if ($productTypeIncludes) {
                                $productTypes = get_product_line_category('product_custom_type', 'product_line', $productLineIds, false, $productTypeIncludes);
                            }
                        } else {
                            $productTypes = get_product_line_category('product_custom_type', 'product_line', $productLineIds);
                        }

                        if (isset($productTypes)) {
                            get_template_part('template-parts/product', 'category', [
                                'category' => $line,
                                'children' => $productTypes,
                            ]);
                            $total += sizeof($productTypes);

                            $productTypesArray = array();
                            foreach ($productTypes as $productType) {
                                $productTypesArray[] = $productType->term_id;
                            }

                            $termFilters = array_merge($productTypesArray, $termFilters);
                        }
                    }
                    $results = ob_get_clean();
                }
            } else {
                $productType = get_term_by('term_id', $productTypeId, 'product_custom_type');
                if ($productType) {
                    $term_link = get_term_link($productType);
                    $redirect = add_query_arg('data-filter', base64_encode(json_encode($data)), $term_link);
                }
            }

            //we will get the meta-value of all product filters, and filter all options in the product filter
            if (!empty($data['attributes'])) {
                $resultMetas = get_available_attributes($product_ids);
                if (empty($resultMetas['product_compound']) && !empty($data['attributes']['compound_certification'])) {
                    $resultMetas['product_compound'] = $data['attributes']['compound_certification'];
                } else {
                    $productCompoundCertification = get_term_ids_by_attributes($product_ids, 'compound_certification');
                    $resultMetas['product_compound'] = $productCompoundCertification;
                }
            }
            wp_send_json_success([
                'category' => $category->name ?? '',
                'results' => $results,
                'total' => $total,
                //'data' => $data,
                'filter_meta' => $resultMetas ?? null,
                //'$product_ids' => implode(',',$product_ids) ?? null,
                'isSizeFilter' => $isSizeFilter,
                'redirect' => $redirect ?? null,
            ]);
        } catch (Exception $e) {
            wp_send_json_error('cabling_get_products_ajax_callback' . $e->getMessage() . '###' . $e->getTraceAsString());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_get_products_ajax', 'cabling_get_products_ajax_callback');
add_action('wp_ajax_nopriv_cabling_get_products_ajax', 'cabling_get_products_ajax_callback');
function cabling_get_api_ajax_callback()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);

            //$oauthTokenUrl = 'https://oauthasservices-a4b9bd800.hana.ondemand.com/oauth2/api/v1/token';
            //$apiEndpointBasic = 'https://e2515-iflmap.hcisbt.eu1.hana.ondemand.com/http/GICHANNELS/';
            $oauthTokenUrl = 'https://oauthasservices-a3c9ce896.hana.ondemand.com/oauth2/api/v1/token';
            $apiEndpointBasic = 'https://l2515-iflmap.hcisbp.eu1.hana.ondemand.com/http/GICHANNELS/';
            $clientId = 'e27dfb2c-9961-3756-9720-32c99ec819ac';
            $clientSecret = '9ad9a0c8-02ef-3253-993b-8faa20d6965b';
            $webServices = new GIWebServices($oauthTokenUrl, $clientId, $clientSecret);

            if (empty($data['api_service'])) {
                wp_send_json_error('Missing API Service');
            }

            $sap_no = get_user_meta(get_current_user_id(), 'sap_customer', true);
            $user_plant = get_user_meta(get_current_user_id(), 'sales_org', true);

            $data['api']['SoldToParty'] = $sap_no;

            // Add show_ponumber
            // if( $data['api_page'] == 'backlog' && !empty( $data['show_ponumber'] ) ){
            //     $data['api']['PurchaseOrderByCustomer'] = $data['show_ponumber'];
            // }

            $bodyParams = array();
            foreach ($data['api'] as $name => $value) {
                if (empty($value)) {
                    continue;
                }
                $bodyParams[] = array(
                    'Field' => $name,
                    'Value' => $value,
                    'Operator' => 'and',
                );
            }

            $type = 'ZDD_I_SD_PIM_MaterialBacklog';
            $type_level_2 = 'ZDD_I_SD_PIM_MaterialBacklogType';
            switch ($data['api_page']) {
                case 'inventory':
                    $apiEndpoint = $apiEndpointBasic . 'GET_DATA_PRICE_CDS';
                    $apiStockEndpoint = $apiEndpointBasic . 'GET_DATA_STOCK_CDS';
                    $template = $data['api_page'] . '-item.php';
                    $oldMaterialNumber = $data['api']['MaterialOldNumber'];
                    $material = $data['api']['Material'];
                    $basicMaterial = $data['api']['BasicMaterial'];

                    $stockParams = array(
                        array(
                            'Field' => 'SalesOrganization',
                            'Value' => empty($user_plant) ? '2141' : $user_plant,
                            'Operator' => '',
                        ),
                    );
                    $priceParams = array(
                        array(
                            'Field' => 'SalesOrganization',
                            'Value' => empty($user_plant) ? '2141' : $user_plant,
                            'Operator' => 'and',
                        )
                    );

                    if (!empty($oldMaterialNumber) && !empty($basicMaterial)) {
                        $priceParams[] = array(
                            'Field' => 'MaterialOldNumber',
                            'Value' => $oldMaterialNumber,
                            'Operator' => '',
                        );
                        $priceParams[] = array(
                            'Field' => 'BasicMaterial',
                            'Value' => $basicMaterial,
                            'Operator' => '',
                        );

                        $stockParams[] = array(
                            'Field' => 'OldMaterialNumber',
                            'Value' => $oldMaterialNumber,
                            'Operator' => '',
                        );
                        $stockParams[] = array(
                            'Field' => 'BasicMaterial',
                            'Value' => $basicMaterial,
                            'Operator' => '',
                        );
                    } elseif (!empty($material)) {
                        $priceParams[] = array(
                            'Field' => 'Material',
                            'Value' => $material,
                            'Operator' => '',
                        );
                        $priceParams[] = array(
							'Field' => '(Customer',
							'Sign' => 'eq',
							'Value' => $sap_no,
							'Operator' => 'or',
						);
						$priceParams[] = array(
							'Field' => 'Customer',
							'Sign' => 'eq',
							'Value' => "",
							'Operator' => ')',
						);
                        $stockParams[] = array(
                            'Field' => 'Material',
                            'Value' => $material,
                            'Operator' => '',
                        );
                    }

                    $responsePrice = $webServices->makeApiRequest($apiEndpoint, $priceParams);
                    $responseStock = $webServices->makeApiRequest($apiStockEndpoint, $stockParams);

                    $dataPrice = $webServices->getDataResponse($responsePrice, 'ZDD_I_SD_PIM_MaterialPrice', 'ZDD_I_SD_PIM_MaterialPriceType');
                    $dataStock = $webServices->getDataResponse($responseStock, 'ZDD_I_SD_PIM_MaterialStock', 'ZDD_I_SD_PIM_MaterialStockType');

                    $responseData = array(
                        'price' => $dataPrice,
                        'stock' => $dataStock,
                        'data' => [
                            $priceParams,
                            $stockParams,
                        ]
                    );

                    break;
                default:
                    $apiEndpoint = $apiEndpointBasic . 'GET_DATA_BACKLOG_CDS';
                    $template = $data['api_page'] . '-item.php';
                    $response = $webServices->makeApiRequest($apiEndpoint, $bodyParams);

                    if ($response['error']) {
                        wp_send_json_error('API error: ' . $response['error']);
                    }

                    $responseData = $webServices->getDataResponse($response, $type, $type_level_2);
                    break;
            }

            ob_start();
            wc_get_template('myaccount/api/' . $template, ['data' => $responseData]);
            $result = ob_get_clean();

            wp_send_json_success([
                'data' => $result,
                //'$data' => $data,
                'raw' => $responseData,
            ]);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        // Nonce is invalid; handle the error or exit
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_get_api_ajax', 'cabling_get_api_ajax_callback');
add_action('wp_ajax_nopriv_cabling_get_api_ajax', 'cabling_get_api_ajax_callback');



function cabling_get_api_ajax_callback_checkout()
{
    if (isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'cabling-ajax-nonce')) {
        try {
            parse_str($_REQUEST['data'], $data);
            $oauthTokenUrl = 'https://oauthasservices-a4b9bd800.hana.ondemand.com/oauth2/api/v1/token';
            $apiEndpointBasic = 'https://e2515-iflmap.hcisbt.eu1.hana.ondemand.com/http/GICHANNELS/';
            $clientId = 'e27dfb2c-9961-3756-9720-32c99ec819ac';
            $clientSecret = '9ad9a0c8-02ef-3253-993b-8faa20d6965b';
            $webServices = new GIWebServices($oauthTokenUrl, $clientId, $clientSecret);

            if (empty($data['api_service'])) {
                wp_send_json_error('Missing API Service');
            }

            $sap_no = get_user_meta(get_current_user_id(), 'sap_customer', true);
            $user_plant = get_user_meta(get_current_user_id(), 'sales_org', true);

            $data['api']['SoldToParty'] = $sap_no;

            $bodyParams = array();
            foreach ($data['api'] as $name => $value) {
                if (empty($value)) {
                    continue;
                }
                $bodyParams[] = array(
                    'Field' => $name,
                    'Value' => $value,
                    'Operator' => 'and',
                );
            }
            $skuData = [];
            $type = 'ZDD_I_SD_PIM_MaterialBacklog';
            $type_level_2 = 'ZDD_I_SD_PIM_MaterialBacklogType';
            switch ($data['api_page']) {
                case 'inventory':
                    $material = [];
                    foreach ( WC()->cart->get_cart() as $cart_key => $cart_item ) {
                        $product_id = $cart_item['product_id'];
                        $productObj = wc_get_product($product_id);
                        $material[] = [
                            $productObj->sku,
                            $cart_item['quantity']
                        ];
                    }
                    $apiEndpoint = $apiEndpointBasic . 'GET_DATA_PRICE_CDS';
                    $apiStockEndpoint = $apiEndpointBasic . 'GET_DATA_STOCK_CDS';
                    $rawData = [];
                    if (!empty($material)) {
                        foreach ($material as $material_value){
                            $material_val = $material_value[0];
                            $priceParams = [
                                array(
                                    'Field' => 'SalesOrganization',
                                    'Value' => empty($user_plant) ? '2141' : $user_plant,
                                    'Operator' => 'and',
                                ),
                                array(
                                    'Field' => 'Material',
                                    'Value' => $material_val,
                                    'Operator' => '',
                                ),
                                array(
                                    'Field' => '(Customer',
                                    'Sign' => 'eq',
                                    'Value' => $sap_no,
                                    'Operator' => 'or',
                                ),
                                array(
                                    'Field' => 'Customer',
                                    'Sign' => 'eq',
                                    'Value' => "",
                                    'Operator' => ')',
                                )
                            ];
                            $stockParams = [
                                array(
                                    'Field' => 'SalesOrganization',
                                    'Value' => empty($user_plant) ? '2141' : $user_plant,
                                    'Operator' => '',
                                ),
                                array(
                                    'Field' => 'Material',
                                    'Value' => $material_val,
                                    'Operator' => '',
                                )
                            ];

                            $responseStock = $webServices->makeApiRequest($apiStockEndpoint, $stockParams);
                            $dataStock = $webServices->getDataResponse($responseStock, 'ZDD_I_SD_PIM_MaterialStock', 'ZDD_I_SD_PIM_MaterialStockType');
                            $rawData[] = array(
                                'stock' => $dataStock,
                                'quantity' => $material_value[1]
                            );
                        }
                    }
                    break;
                default:
                    $apiEndpoint = $apiEndpointBasic . 'GET_DATA_BACKLOG_CDS';
                    $response = $webServices->makeApiRequest($apiEndpoint, $bodyParams);

                    if ($response['error']) {
                        wp_send_json_error('API error: ' . $response['error']);
                    }
                    $rawData = $webServices->getDataResponse($response, $type, $type_level_2);
                    break;
            }

            wp_send_json_success([
                'raw' => $rawData
            ]);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    } else {
        wp_send_json_error('Invalid nonce.');
    }
    wp_die();
}

add_action('wp_ajax_cabling_get_api_ajax_checkout', 'cabling_get_api_ajax_callback_checkout');
add_action('wp_ajax_nopriv_cabling_get_api_ajax_checkout', 'cabling_get_api_ajax_callback_checkout');
