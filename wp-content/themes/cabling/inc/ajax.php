<?php
function cabling_create_webshop_user_ajax_callback(): void
{
    parse_str($_REQUEST['data'], $data);

    $err = false;
    $message = '';
    if (email_exists($data['user_email'])) {
        $err = true;
        $message = '<div class="woocommerce-error woo-notice" role="alert">' . sprintf(__('The email <strong>%s</strong> was registered, please try with others.', 'cabling'), $data['user_email']) . '</div>';
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
            $mess = '<div class="woocommerce-error woo-notice" role="alert">' . __('Please check your Email or Password.', 'cabling') . '</div>';
        } else {
            $creds = array(
                'user_login' => $data['log'],
                'user_password' => $data['pwd'],
                'remember' => $data['rememberme']
            );

            $user = wp_signon($creds, is_ssl());

            if (is_wp_error($user)) {
                if ($user->get_error_code() === 'invalid_email') {
                    $error = __('Unknown email address. Please check again!', 'cabling');
                } else {
                    $error = $user->get_error_message();
                }
                $err = true;
                $mess = '<div class="woocommerce-error woo-notice" role="alert">' . $error . '</div>';
            } else {
                $redirect_to = $data['_wp_http_referer'] ?? wc_get_account_endpoint_url('');
                $mess = '<div class="woocommerce-message woo-notice" role="alert">' . __('Success! Redirecting...', 'cabling') . '</div>';
            }
        }
    } else {
        $err = true;
        $mess = '<div class="woocommerce-error woo-notice" role="alert">' . __('reCAPTCHA verification failed. Please try again!', 'cabling') . '</div>';
    }

    $response = array(
        'redirect' => $redirect_to,
        'error' => $err,
        'mess' => $mess
    );
    wp_send_json($response);
}

add_action('wp_ajax_nopriv_cabling_login_ajax', 'cabling_login_ajax_callback');

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
        <p class="mb-0"><?php echo esc_attr_x('Sorry – we can’t find directly what you’re looking for, but we’ve provided further information related to your search in the chat', 'submit button') ?></p>
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
                $message = '<div class="woocommerce-error woo-notice" role="alert">' . __('Old password is incorrect.', 'cabling') . '</div>';
                wp_send_json_error($message . $user->get_error_message());
            }

            // Update the password
            wp_set_password($data['new-password'], get_current_user_id());

            wp_set_auth_cookie($current_user->ID);

            $message = '<div class="woocommerce-message woo-notice" role="alert">' . __('Password updated successfully!', 'cabling') . '</div>';
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

            $oauthTokenUrl = 'https://oauthasservices-a4b9bd800.hana.ondemand.com/oauth2/api/v1/token';
            $apiEndpointBasic = 'https://e2515-iflmap.hcisbt.eu1.hana.ondemand.com/http/GICHANNELS/';
            $clientId = 'e27dfb2c-9961-3756-9720-32c99ec819ac';
            $clientSecret = '9ad9a0c8-02ef-3253-993b-8faa20d6965b';
            $oauthClient = new GIWebServices($oauthTokenUrl, $clientId, $clientSecret);

            if (empty($data['api_service'])) {
                wp_send_json_error('Missing API Service');
            }

            $sap_no = get_user_meta(get_current_user_id(), 'sap_customer', true);

            $data['api']['sapcustomer'] = $sap_no;

            /*if (!empty($data['api']['due_date'])) {
                $dateTime = new DateTime($data['api']['due_date']);
                $data['api']['due_date'] = $dateTime->format('Y-d-m\TH:i:s.u');
            }*/

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

            $type = 'ZDD_I_SD_PIM_MaterialBacklogCE';
            $type_level_2 = 'ZDD_I_SD_PIM_MaterialBacklogCEType';

            switch ($data['api_page']) {
                case 'inventory':
                    $apiEndpoint = $apiEndpointBasic . 'GET_DATA_PRICE';
                    $apiStockEndpoint = $apiEndpointBasic . 'GET_DATA_STOCK';
                    $template = $data['api_page'] . '-item.php';
                    $parcomaterial = $data['api']['parcomaterial'];
                    $sapmaterial = $data['api']['sapmaterial'];
                    $parcocompound = $data['api']['parcocompound'];

                    $bodyPriceParams = array(
                        array(
                            'Field' => 'sapcustomer',
                            'Value' => $sap_no,
                            'Operator' => '',
                        ),
                    );
                    $inventoryParams = array();

                    if (!empty($parcomaterial) && !empty($parcocompound)) {
                        $inventoryParams[] = array(
                            'Field' => 'parcomaterial',
                            'Value' => $parcomaterial,
                            'Operator' => '',
                        );
                        $inventoryParams[] = array(
                            'Field' => 'parcocompound',
                            'Value' => $parcocompound,
                            'Operator' => '',
                        );
                    } elseif (!empty($sapmaterial)) {
                        $inventoryParams[] = array(
                            'Field' => 'sapmaterial',
                            'Value' => $sapmaterial,
                            'Operator' => '',
                        );
                        $bodyPriceParams[] = array(
                            'Field' => 'sapmaterial',
                            'Value' => $sapmaterial,
                            'Operator' => '',
                        );
                    }

                    $responsePrice = $oauthClient->makeApiRequest($apiEndpoint, $bodyPriceParams);
                    $responseStock = $oauthClient->makeApiRequest($apiStockEndpoint, $inventoryParams);

                    $dataPrice = getDataResponse($responsePrice, 'ZDD_I_SD_PIM_MaterialPriceCE', 'ZDD_I_SD_PIM_MaterialPriceCEType');
                    $dataStock = getDataResponse($responseStock, 'ZDD_I_SD_PIM_MaterialStockCE', 'ZDD_I_SD_PIM_MaterialStockCEType');

                    $responseData = array(
                        'price' => $dataPrice,
                        'stock' => $dataStock,
                        /*'data' => [
                            $bodyPriceParams,
                            $inventoryParams,
                        ]*/
                    );

                    break;
                default:
                    $apiEndpoint = $apiEndpointBasic . 'GET_DATA_BACKLOG';
                    $template = $data['api_page'] . '-item.php';
                    $response = $oauthClient->makeApiRequest($apiEndpoint, $bodyParams);

                    if ($response['error']) {
                        wp_send_json_error('API error: ' . $response['error']);
                    }

                    $responseData = getDataResponse($response, $type, $type_level_2);
                    break;
            }

            ob_start();
            wc_get_template('myaccount/api/' . $template, ['data' => $responseData]);
            $result = ob_get_clean();

            wp_send_json_success([
                'data' => $result,
                //'$data' => $data,
                //'raw' => $responseData,
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
