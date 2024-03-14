<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package cabling
 */

use GeoIp2\Database\Reader;

add_action('init', function () {
    add_post_type_support('product', 'page-attributes');
});

function cabling_woocommerce_setup()
{
    add_theme_support('woocommerce');
    //add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

add_action('after_setup_theme', 'cabling_woocommerce_setup');

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function cabling_woocommerce_scripts()
{
    wp_enqueue_style('cabling-woocommerce-style', get_template_directory_uri() . '/woocommerce.css');

    $font_path = WC()->plugin_url() . '/assets/fonts/';
    $inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

    wp_add_inline_style('cabling-woocommerce-style', $inline_font);
}

add_action('wp_enqueue_scripts', 'cabling_woocommerce_scripts');

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function cabling_woocommerce_active_body_class($classes)
{
    $classes[] = 'woocommerce-active';

    return $classes;
}

add_filter('body_class', 'cabling_woocommerce_active_body_class');

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function cabling_woocommerce_products_per_page()
{
    return 12;
}

add_filter('loop_shop_per_page', 'cabling_woocommerce_products_per_page');

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function cabling_woocommerce_thumbnail_columns()
{
    return 4;
}

add_filter('woocommerce_product_thumbnails_columns', 'cabling_woocommerce_thumbnail_columns');

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function cabling_woocommerce_loop_columns()
{
    return is_product_category() ? 1 : 3;
}

add_filter('loop_shop_columns', 'cabling_woocommerce_loop_columns');

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function cabling_woocommerce_related_products_args($args)
{
    $defaults = array(
        'posts_per_page' => 3,
        'columns' => 3,
    );

    $args = wp_parse_args($defaults, $args);

    return $args;
}

add_filter('woocommerce_output_related_products_args', 'cabling_woocommerce_related_products_args');

if (!function_exists('cabling_woocommerce_product_columns_wrapper')) {
    /**
     * Product columns wrapper.
     *
     * @return  void
     */
    function cabling_woocommerce_product_columns_wrapper()
    {
        if ('Grid' === get_field('_woo_product_list', 'option'))
            $product_class = ' product-grid ';
        else
            $product_class = ' product-list ';

        $columns = cabling_woocommerce_loop_columns();
        echo '<div class="columns-' . absint($columns) . $product_class . '">';
    }
}
add_action('woocommerce_before_shop_loop', 'cabling_woocommerce_product_columns_wrapper', 40);

if (!function_exists('cabling_woocommerce_product_columns_wrapper_close')) {
    /**
     * Product columns wrapper close.
     *
     * @return  void
     */
    function cabling_woocommerce_product_columns_wrapper_close()
    {
        echo '</div>';
    }
}
add_action('woocommerce_after_shop_loop', 'cabling_woocommerce_product_columns_wrapper_close', 40);

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

function cabling_woocommerce_wrapper_before()
{
    $showFilter = false;
    if (is_tax('product_cat') || is_tax('compound_cat') || is_tax('product_custom_type')) {
        $showFilter = true;
    }
    ?>
    <div id="primary" class="content-area <?php echo $showFilter ? 'has_sidebar container ' : '' ?>">
    <?php if ($showFilter) get_template_part('template-parts/filter', 'product_category'); ?>
    <main id="main" class="site-main" role="main">
    <div class="container">
    <?php
}

add_action('woocommerce_before_main_content', 'cabling_woocommerce_wrapper_before');
function cabling_woocommerce_after_main_content()
{
    if (is_tax('product_cat') || is_tax('compound_cat') || is_product()) {
        cabling_add_quote_section();
    }

    if (is_tax('product_custom_type')) {
        cabling_related_complementary_section();
    }
}

add_action('woocommerce_sidebar', 'cabling_woocommerce_after_main_content');

if (!function_exists('cabling_woocommerce_wrapper_after')) {
    /**
     * After Content.
     *
     * Closes the wrapping divs.
     *
     * @return void
     */
    function cabling_woocommerce_wrapper_after()
    {
        ?>
        </div>
        </main><!-- #main -->
        </div><!-- #primary -->
        <?php
    }
}
add_action('woocommerce_after_main_content', 'cabling_woocommerce_wrapper_after');

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
 * <?php
 * if ( function_exists( 'cabling_woocommerce_header_cart' ) ) {
 * cabling_woocommerce_header_cart();
 * }
 * ?>
 */

if (!function_exists('cabling_woocommerce_cart_link_fragment')) {
    /**
     * Cart Fragments.
     *
     * Ensure cart contents update when products are added to the cart via AJAX.
     *
     * @param array $fragments Fragments to refresh via AJAX.
     * @return array Fragments to refresh via AJAX.
     */
    function cabling_woocommerce_cart_link_fragment($fragments)
    {
        ob_start();
        cabling_woocommerce_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'cabling_woocommerce_cart_link_fragment');

if (!function_exists('cabling_woocommerce_cart_link')) {
    /**
     * Cart Link.
     *
     * Displayed a link to the cart including the number of items present and the cart total.
     *
     * @return void
     */
    function cabling_woocommerce_cart_link()
    {
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'cabling'); ?>">
            <?php
            $item_count_text = sprintf(
            /* translators: number of items in the mini cart. */
                _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'cabling'),
                WC()->cart->get_cart_contents_count()
            );
            ?>
            <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span
                    class="count"><?php echo esc_html($item_count_text); ?></span>
        </a>
        <?php
    }
}

if (!function_exists('cabling_woocommerce_header_cart')) {
    /**
     * Display Header Cart.
     *
     * @return void
     */
    function cabling_woocommerce_header_cart()
    {
        if (is_cart()) {
            $class = 'current-menu-item';
        } else {
            $class = '';
        }
        ?>
        <ul id="site-header-cart" class="site-header-cart">
            <li class="<?php echo esc_attr($class); ?>">
                <?php cabling_woocommerce_cart_link(); ?>
            </li>
            <li>
                <?php
                $instance = array(
                    'title' => '',
                );

                the_widget('WC_Widget_Cart', $instance);
                ?>
            </li>
        </ul>
        <?php
    }
}

function cabling_woocommerce_breadcrumb()
{
    if (is_shop()) return;
    $link = home_url('/products-and-services/');
    if (is_product() && isset($_REQUEST['data-history'])){
        $link = base64_decode($_REQUEST['data-history']);
    } elseif (is_tax('product_custom_type')){
        $link = get_product_filter_link(true);
    }
    echo '<div class="container mb-3">';
    echo '<div class="woo-breadcrumbs d-flex align-items-center">';
    echo '<a href="' . $link . '" class="back-button"><i class="fa-light fa-arrow-left"></i>' . __('Back to Results', 'cabling') . '</a>';
    woocommerce_breadcrumb(
        array(
            'delimiter' => ' / ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb"><span>' . __('<span>Products & Services / </span>', 'cabling') . '</span>',
            'home' => ''
        )
    );
    echo '</div>';
    echo '</div>';
}

function cabling_woocommerce_breadcrumb_back_button()
{
    $breadcrumbs = new WC_Breadcrumb();
    $breadcrumb = $breadcrumbs->generate();
    $count = count($breadcrumb);

    echo '<div class="back-btn-wrap w-100">';
    if ($count > 1 && !empty($breadcrumb[$count - 2])) {
        echo '<a href="' . $breadcrumb[$count - 2][1] . '" class="backbutton box-shadow">' . __('Back to: ', 'cabling') . $breadcrumb[$count - 2][0] . '</a>';
    } else if (is_product() || is_product_category()) {
        echo '<a href="' . home_url('/products-and-services/') . '" class="backbutton box-shadow">' . __('Back to: Products & Services', 'cabling') . '</a>';
    }
    echo '</div>';
}


function get_customer_level($userId): int
{
    $level = 1;
    $has_approved = get_user_meta($userId, 'has_approve', true);
    $customer_level = get_user_meta($userId, 'customer_level', true);
    if ('true' == $has_approved || $customer_level === '2')
        $level = 2;

    return $level;
}

function get_master_account_id($userId): string
{
    $customer_parent = get_user_meta($userId, 'customer_parent', true);

    return $customer_parent ?: $userId;
}

function get_customer_type($userId): string
{
    $type = CHILD_ACCOUNT;
    $customer_parent = get_user_meta($userId, 'customer_parent', true);
    if (empty($customer_parent))
        $type = MASTER_ACCOUNT;

    return $type;
}

function get_customer_type_label($user_id): string
{
    $customer_type = get_customer_type($user_id);

    return $customer_type === MASTER_ACCOUNT ? 'Master Account' : 'Child Account';
}

/**
 * Account menu Customer
 *
 */
function cabling_account_menu_items()
{
    $user_id = get_current_user_id();
    $customer_level = get_customer_level($user_id);
    $customer_type = get_customer_type($user_id);
    $sap_customer = get_user_meta($user_id, 'sap_customer', true);

    $new_items = array(
        'dashboard' => __('Datwyler My Account', 'cabling'),
        'edit-account' => __('Account Information', 'cabling'),
        //'edit-address' => __('Billing/shipping address', 'cabling'),
        'setting-account' => __('Keep Me Informed', 'cabling'),
    );

    // JM 20230913 restricted menu to master account only
    if ($customer_level === 2 && $customer_type === MASTER_ACCOUNT) {
        $new_items['users-management'] = __('Users management', 'cabling');
    }

    if (!empty($sap_customer)) {
        $new_items['sales-backlog'] = __('Sales Backlog', 'cabling');
        $new_items['inventory'] = __('Inventory, Lead Time and Pricing', 'cabling');
        $new_items['shipment'] = __('Shipments Last 12 Months ', 'cabling');
    }
    //$new_items['orders'] = __('Order history', 'cabling');
    //$new_items['products'] = __('Purchases', 'cabling');

    //$new_items['quotations'] = __('My Quotes', 'cabling');

    //$new_items['messages'] = __('Messages', 'cabling');
    $new_items['request-a-quote'] = __('REQUEST A QUOTE', 'cabling');
    $new_items['contact-form'] = __('Help & Contact', 'cabling');

    // JM 20230913 added logout button
    $new_items['customer-logout'] = __('Log out', 'cabling');

    return $new_items;
}

add_filter('woocommerce_account_menu_items', 'cabling_account_menu_items', 999, 1);

function endArray($array)
{
    return end($array);
}

/**
 * get data response from API endpoint
 * @param array $response
 * @param string $type
 * @param string $type_level_2
 * @return array
 */
function getDataResponse(array $response, string $type, string $type_level_2): array
{
    $responseData = array();
    if (isset($response[$type][$type_level_2])) {
        $responseData = $response[$type][$type_level_2];

        $responseData = is_array($responseData[0]) ? $responseData : [$responseData];
    }
    return $responseData;
}

function show_value_from_api($key, $value)
{
    if (empty($value)) {
        return '-';
    }

    if (str_contains($key, 'quantity') || str_contains($key, 'scale_from') || str_contains($key, 'scale_to')) {
        return number_format($value, 0, '.', ' ');
    }

    if (str_contains($key, 'cure_date')) {
        return $value;
    }

    if (str_contains($key, 'date')) {
        $dateTime = new DateTime($value);

        return $dateTime->format("m/d/Y");
    }

    if (str_contains($key, 'price')) {
        return '$' . number_format($value, 2, '.', ' ');
    }

    return $value;
}

/**
 * Add Customer endpoint
 */
function cabling_add_my_account_endpoint()
{
    add_rewrite_endpoint('users-management', EP_PAGES);
    add_rewrite_endpoint('setting-account', EP_PAGES);
    add_rewrite_endpoint('products', EP_PAGES);
    add_rewrite_endpoint('quotations', EP_PAGES);
    add_rewrite_endpoint('messages', EP_PAGES);
    add_rewrite_endpoint('customer-service', EP_PAGES);

    $sap_customer = get_user_meta(get_current_user_id(), 'sap_customer', true);

    if (!empty($sap_customer)) {
        add_rewrite_endpoint('sales-backlog', EP_PAGES);
        add_rewrite_endpoint('inventory', EP_PAGES);
        add_rewrite_endpoint('shipment', EP_PAGES);
    }
}

add_action('init', 'cabling_add_my_account_endpoint');

/**
 * Users management content
 */
function cabling_customer_endpoint_content()
{
    $user_id = get_current_user_id();
    $customer_level = get_customer_level($user_id);

    if ($customer_level === 2) {
        wc_get_template('myaccount/customer.php');
    }
}

add_action('woocommerce_account_users-management_endpoint', 'cabling_customer_endpoint_content');

/**
 * Users sales-backlog content
 */
function cabling_backlog_endpoint_content()
{
    wc_get_template('myaccount/sales-backlog.php');
}

add_action('woocommerce_account_sales-backlog_endpoint', 'cabling_backlog_endpoint_content');
/**
 * Users inventory content
 */
function cabling_inventory_endpoint_content()
{
    wc_get_template('myaccount/inventory.php');
}

add_action('woocommerce_account_inventory_endpoint', 'cabling_inventory_endpoint_content');
/**
 * Users shipment content
 */
function cabling_shipment_endpoint_content()
{
    wc_get_template('myaccount/shipment.php');
}

add_action('woocommerce_account_shipment_endpoint', 'cabling_shipment_endpoint_content');

/**
 * Products content
 */
function cabling_products_endpoint_content()
{
    wc_get_template('myaccount/products.php');
}

add_action('woocommerce_account_products_endpoint', 'cabling_products_endpoint_content');
/**
 * quotations content
 */
function cabling_quotations_endpoint_content()
{
    $user = wp_get_current_user();
    $data = RequestProductQuote::get(['email' => $user->user_email]);
    wc_get_template('myaccount/quotations.php', ['data' => $data]);
}

add_action('woocommerce_account_quotations_endpoint', 'cabling_quotations_endpoint_content');
/**
 * Messages contents
 */
function cabling_messages_endpoint_content()
{
    wc_get_template('myaccount/messages.php');
}

add_action('woocommerce_account_messages_endpoint', 'cabling_messages_endpoint_content');
/**
 * customer-service content
 */
function cabling_customer_service_endpoint_content()
{
    wc_get_template('myaccount/customer-service.php');
}

add_action('woocommerce_account_customer-service_endpoint', 'cabling_customer_service_endpoint_content');


function cabling_get_user_by_customer($user_id)
{
    $args = array(
        'role' => 'customer',
        'meta_key' => 'customer_parent',
        'meta_value' => $user_id,
        'meta_compare' => '=',
    );
    return get_users($args);
}

add_filter('woocommerce_thankyou_order_received_text', 'cabling_custome_thankyou_text', 10, 2);
function cabling_custome_thankyou_text($var, $order)
{
    return sprintf(__('Thank you. Your order has been received. Tracking order number %d has been successfully saved. One of our sales representative will get back to you shortly once the order is confirmed', 'cabling'), $order->get_id());
}

//Custom check-out field
add_filter('woocommerce_checkout_fields', 'cabling_custom_override_checkout_fields');
function cabling_custom_override_checkout_fields($fields)
{
    $fields['billing']['billing_company']['custom_attributes'] = array('readonly' => 'readonly');
    $fields['billing']['billing_country']['custom_attributes'] = array('disabled' => 'disabled');
    $fields['shipping']['shipping_country']['custom_attributes'] = array('disabled' => 'disabled');

    $fields['order']['order_comments']['label'] = '';
    $fields['order']['order_comments']['placeholder'] = __('Shipping Notes', 'cabling');

    $fields['billing']['billing_country']['priority'] = 95;
    $fields['shipping']['shipping_country']['priority'] = 95;

    $fields['billing']['billing_company']['placeholder'] = __('Company', 'cabling');
    $fields['billing']['billing_address_1']['placeholder'] = __('Address 1', 'cabling');
    $fields['billing']['billing_address_2']['placeholder'] = __('Address 2', 'cabling');
    $fields['billing']['billing_city']['placeholder'] = __('City', 'cabling');
    $fields['billing']['billing_postcode']['placeholder'] = __('Zip Code', 'cabling');
    $fields['billing']['billing_state']['placeholder'] = __('State', 'cabling');
    $fields['billing']['billing_email']['placeholder'] = __('Email', 'cabling');
    $fields['billing']['billing_phone']['placeholder'] = __('Phone', 'cabling');

    $fields['shipping']['shipping_address_1']['placeholder'] = __('Address 1', 'cabling');
    $fields['shipping']['shipping_address_2']['placeholder'] = __('Address 2', 'cabling');
    $fields['shipping']['shipping_city']['placeholder'] = __('City', 'cabling');
    $fields['shipping']['shipping_postcode']['placeholder'] = __('Zip Code', 'cabling');
    $fields['shipping']['shipping_state']['placeholder'] = __('State', 'cabling');
    $fields['shipping']['shipping_email']['placeholder'] = __('Email', 'cabling');
    $fields['shipping']['shipping_phone']['placeholder'] = __('Phone', 'cabling');

    return $fields;
}


//add Company Responsible Full Name field to billing address
add_filter('woocommerce_billing_fields', 'cabling_woocommerce_billing_fields');
function cabling_woocommerce_billing_fields($fields)
{
    if (get_customer_type(get_current_user_id()) === MASTER_ACCOUNT) {
        $fields['company_name_responsible'] = array(
            'label' => __('Company Responsible Full Name', 'cabling'),
            'placeholder' => _x('Company Responsible Full Name', 'placeholder', 'cabling'),
            'required' => false,
            'clear' => false,
            'type' => 'text',
            'priority' => 36
        );
    }

    $fields['company_name_responsible'] = array(
        'label' => __('Company Responsible Full Name1', 'cabling'),
        'placeholder' => _x('Company Responsible Full Name1', 'placeholder', 'cabling'),
        'required' => false,
        'clear' => false,
        'type' => 'text',
        'priority' => 36
    );

    return $fields;
}

add_filter('woocommerce_customer_meta_fields', 'cabling_woocommerce_customer_meta_fields');
function cabling_woocommerce_customer_meta_fields($fields)
{

    $fields['billing']['fields']['company_name_responsible'] = array(
        'label' => __('Company Responsible Full Name', 'cabling'),
        'description' => '',
    );

    return $fields;
}

//Archive Sidebar
function product_widgets_init()
{
    register_sidebar(array(
        'name' => __('Archive Sidebar', 'cabling'),
        'id' => 'archive-sidebar',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ));
}

add_action('widgets_init', 'product_widgets_init');


/* uptade 07/04 */

/**
 * Pre-populate Woocommerce checkout fields
 * Note that this filter populates shipping_ and billing_ fields with a different meta field eg 'first_name'
 */
add_filter('woocommerce_checkout_get_value', function ($input, $key) {

    global $current_user;

    switch ($key) :
        case 'billing_first_name':
        case 'shipping_first_name':
            return $current_user->first_name;
            break;

        case 'billing_last_name':
        case 'shipping_last_name':
            return $current_user->last_name;
            break;

        case 'billing_email':
            return $current_user->user_email;
            break;

        case 'billing_phone':
            return $current_user->phone;
            break;

    endswitch;

}, 10, 2);

/**
 * Dynamically pre-populate Woocommerce checkout fields with exact named meta field
 * Eg. field 'shipping_first_name' will check for that exact field and will not fallback to any other field eg 'first_name'
 *
 * @author Joe Mottershaw | https://cloudeight.co
 */
add_filter('woocommerce_checkout_get_value', function ($input, $key) {

    global $current_user;

    // Return the user property if it exists, false otherwise
    return ($current_user->$key
        ? $current_user->$key
        : false
    );
}, 10, 2);

add_filter('woocommerce_get_catalog_ordering_args', 'am_woocommerce_catalog_orderby');
function am_woocommerce_catalog_orderby($args)
{
    //$args['meta_key'] = '_price';
    $args['orderby'] = 'date';
    $args['order'] = 'desc';
    return $args;
}

//custom woocommerce page
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);


//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

add_action('woocommerce_before_main_content', 'cabling_woocommerce_breadcrumb', 1);
add_action('woocommerce_before_single_product_summary', 'cabling_get_brand_product', 4);
add_action('woocommerce_single_product_summary', 'cabling_add_quote_button', 21);
add_action('woocommerce_single_product_summary', 'cabling_additional_information', 22);
//add_action( 'woocommerce_after_single_product_summary', 'cabling_woocommerce_description', 5 );
//add_action( 'woocommerce_after_single_product_summary', 'cabling_woocommerce_pdf_export_button', 10 );
add_action('woocommerce_shop_loop_item_title', 'cabling_product_description', 15);
add_action('woocommerce_before_shop_loop', 'cabling_product_category_heading', 10);
add_action('woocommerce_after_my_account', 'cabling_woocommerce_after_my_account_modal', 99);

function cabling_product_category_heading()
{
    echo '<h4>' . __('Products Available', 'cabling') . '</h4>';
}

function cabling_product_description()
{
    echo '<div class="product-excerpt">';
    the_excerpt();
    echo '</div>';
}

/**
 * Remove product data tabs
 */
add_filter('woocommerce_product_tabs', 'cabling_woo_remove_product_tabs', 98);
function cabling_woo_remove_product_tabs($tabs)
{
    unset($tabs['description']);
    unset($tabs['additional_information']);

    return $tabs;
}

function cabling_get_product_attributes($product_id = 0): array
{
    $attributes = array(
        '_sku' => 'SKU'
    );
    $product_attributes = get_post_meta($product_id, '_product_attributes', true);
    if ($product_attributes) {
        foreach ($product_attributes as $attribute) {
            $attribute_name = str_replace('pa_', '', $attribute['name']);
            $attribute_label = str_replace('-', ' ', $attribute_name);
            $attributes[$attribute['name']] = ucwords($attribute_label);
        }
    }

    return $attributes;
}

function cabling_get_product_table_attributes(): array
{
    $list_fields = array(
        'product_dash_number' => __('Dash Number', 'cabling'),
        'inches_id' => __('Inches I.D.', 'cabling'),
        'inches_width' => __('Inches CS', 'cabling'),
        'milimeters_id' => __('Millimeters I.D.', 'cabling'),
        'milimeters_width' => __('Millimeters CS', 'cabling'),
        'product_hardness' => __('Hardness', 'cabling'),
        '_sku' => __('SKU', 'cabling'), // JM 20240307 Changed column order
        'product_specifications_met' => __('Specifications Met', 'cabling'),
        'product_operating_temp' => __('Temperature Range, °F', 'cabling'),
        'product_colour' => __('Colour', 'cabling'),
        //'_stock' => __('Pkg Qty.', 'cabling'),
        //'_sku' => '',
        //'_sku' => __('SKU', 'cabling'),
    );
    return $list_fields;
}

function cabling_get_product_single_attributes($dynamic_fields, $product_id): array
{
    $list_fields = array();

    $product_id = empty($product_id) ? get_the_ID() : $product_id;

    //$product_cat->name
    $product_cats = get_the_terms($product_id, 'product_cat');
    if ($product_cats && !is_wp_error($product_cats)) {
        $product_cat = reset($product_cats);

        if ($product_cat) {
            $list_fields['attributes']['product_cat'] = array(
                'label' => '',
                'value' => $product_cat->name
            );
        }
    }
    //product_material
    $product_material = get_product_field('product_material', $product_id);
    if (!empty($product_material)) {
        $list_fields['attributes']['product_material'] = array(
            'label' => __('Material', 'cabling'),
            'value' => get_the_title($product_material)
        );
    }
    //o-ring standard
    if (!empty($dynamic_fields)) {
        $o_ring_standard_index = array_search('O-RING SIZE STANDARD', array_column($dynamic_fields, 'label'));
        if ($o_ring_standard_index !== false) {
            $list_fields['attributes']['O-RING SIZE STANDARD'] = $dynamic_fields[$o_ring_standard_index];
        }
    }
    //product_hardness
    $hardness = get_product_field('product_hardness', $product_id);
    if (!empty($hardness)) {
        $list_fields['attributes']['product_hardness'] = array(
            'label' => __('HARDNESS (SHORE A) +/-5', 'cabling'),
            'value' => $hardness
        );
    }
    //product_dash_number
    $product_dash_number = get_product_field('product_dash_number', $product_id);
    if (!empty($product_dash_number)) {
        $list_fields['attributes']['product_dash_number'] = array(
            'label' => __('Dash Number', 'cabling'),
            'value' => str_replace('AS', '', $product_dash_number)
        );
    }

    //nominal_size_width
    $nominal_size_width = get_product_field('nominal_size_width', $product_id);
    if (!empty($nominal_size_width)) {
        $list_fields['size']['nominal_size_width'] = array(
            'label' => __('Nominal CS', 'cabling'),
            'value' => $nominal_size_width
        );
    }
    //inches_width
    $inches_width = get_product_field('inches_width', $product_id);
    if (!empty($inches_width)) {
        $list_fields['size']['inches_width'] = array(
            'label' => __('Inches CS', 'cabling'),
            'value' => $inches_width
        );
    }
    //Nominal Size O.D.
    $nominal_size_od = get_product_field('nominal_size_od', $product_id);
    if (!empty($nominal_size_od)) {
        $list_fields['size']['nominal_size_od'] = array(
            'label' => __('Nominal Size O.D.', 'cabling'),
            'value' => $nominal_size_od
        );
    }
    //inches_od
    $inches_od = get_product_field('inches_od', $product_id);
    if (!empty($inches_od)) {
        $list_fields['size']['inches_od'] = array(
            'label' => __('Inches O.D.', 'cabling'),
            'value' => $inches_od
        );
    }
    //Nominal Size I.D.
    $nominal_size_id = get_product_field('nominal_size_id', $product_id);
    if (!empty($nominal_size_id)) {
        $list_fields['size']['nominal_size_id'] = array(
            'label' => __('Nominal Size I.D.', 'cabling'),
            'value' => $nominal_size_id
        );
    }
    //inches_id
    $inches_id = get_product_field('inches_id', $product_id);
    if (!empty($inches_id)) {
        $list_fields['size']['inches_id'] = array(
            'label' => __('Inches I.D.', 'cabling'),
            'value' => $inches_id
        );
    }
    //inches_id_tol
    $list_fields['size']['--'] = array(
        'label' => '',
        'value' => ''
    );
    //inches_id_tol
    $inches_id_tol = get_product_field('inches_id_tol', $product_id);
    if (!empty($inches_id_tol)) {
        $list_fields['size']['inches_id_tol'] = array(
            'label' => __('Inches I.D. Tol.', 'cabling'),
            'value' => $inches_id_tol
        );
    }
    //inches_id_tol
    $list_fields['size']['---'] = array(
        'label' => '',
        'value' => ''
    );
    //inches_id_tol
    $inches_width_tol = get_product_field('inches_width_tol', $product_id);
    if (!empty($inches_width_tol)) {
        $list_fields['size']['inches_width_tol'] = array(
            'label' => __('Inches CS Tol.', 'cabling'),
            'value' => $inches_width_tol
        );
    }

    return $list_fields;
}

function show_filter_value($fieldList, $product_id)
{
    $class = [];
    foreach ($fieldList as $key => $attribute) {
        $value = get_field($key, $product_id);
        if (is_array($value)) {
            foreach ($value as $vl) {
                $class[] = sanitize_title($key . $vl);
            }
        } else {
            $class[] = sanitize_title($key . $value);
        }
    }
    return $class;
}

function get_product_field($key, $product_id)
{
    $value = get_field($key, $product_id);
    if (empty($value) || $value == 'null') {
        //$value = 'N/A';
        $value = '*';
    } elseif (is_array($value)) {
        $value = implode(', ', $value);
    }
    if ($key === 'product_dash_number') {
        $value = str_replace('AS', '', $value);
    }
    return $value;
}

function cabling_woocommerce_description()
{
    global $product;
    $heading = __('Product Description', 'cabling');
    ob_start();
    if ($product && $product->is_type('variable')) {
        /*$heading = $product->get_name();

        $variations = get_children(array(
            'post_parent' => $product->get_id(),
            'post_type' => 'product_variation',
        ));
        if ($variations) {
            $product_attributes = cabling_get_product_attributes($product->get_id());
            wc_get_template('single-product/product-variation-table.php', [
                'variations' => $variations,
                'attributes' => $product_attributes,
            ]);
        }*/

    } else {
        $key_benefits = get_field('key_benefits', $product->get_id());
        $use_with = get_field('use_with', $product->get_id());
        $do_not_use_with = get_field('do_not_use_with', $product->get_id());
        $typical_values_for_compound = get_field('typical_values_for_compound', $product->get_id());

        wc_get_template('single-product/product-simple.php', [
            'key_benefits' => $key_benefits,
            'use_with' => $use_with,
            'do_not_use_with' => $do_not_use_with,
            'typical_values_for_compound' => $typical_values_for_compound,
        ]);
    }
    $product_data = ob_get_clean();

    ob_start(); ?>
    <div class="product-description mb-5">
        <div class="main-description mb-5">
            <h4><?php echo $heading ?></h4>
            <?php
            echo apply_filters('the_content', get_the_content());
            ?>
        </div>
        <?php echo $product_data; ?>
    </div>
    <?php
    $content = ob_get_clean();

    return $content;
}

// Change Add to Cart text on product archives
function custom_woocommerce_product_add_to_cart_text($text, $product)
{
    return __('FIND OUT MORE', 'cabling');
}

add_filter('woocommerce_product_add_to_cart_text', 'custom_woocommerce_product_add_to_cart_text', 10, 2);

function cabling_add_quote_button($product_id = 0)
{
    $product_id = is_product() ? get_the_ID() : $product_id;
    echo '<div data-action="' . $product_id . '" class="product-request-button show-product-quote">';
    echo '<a class="btn btn-primary" href="#">' . __('Request a quote', 'cabling') . '</a>';
    echo '</div>';
}

function cabling_additional_information()
{
    global $product;
    if ($product && $product->is_type('variable'))
        return;

    ob_start();
    cabling_woocommerce_pdf_document($product);
    echo ob_get_clean();
}

function cabling_add_quote_section()
{
    wc_get_template('single-product/product-add-quote.php');
}

function cabling_related_complementary_section()
{
    wc_get_template('product-complementary-section.php');
}

function cabling_woocommerce_pdf_document($product)
{
    $pdfs = get_field('pdp_document');
    $product_dynamic_fields = get_field('product_dynamic_fields', $product->get_id());
    $icon = get_template_directory_uri() . '/assets/img/%image%.png';

    $product_attributes = [];
    if ($product->has_weight()) {
        $product_attributes['weight'] = array(
            'label' => __('Weight', 'woocommerce'),
            'value' => wc_format_weight($product->get_weight()),
        );
    }

    if ($product->has_dimensions()) {
        $product_attributes['dimensions'] = array(
            'label' => __('Dimensions', 'woocommerce'),
            'value' => wc_format_dimensions($product->get_dimensions(false)),
        );
    }

    $custom_fields = cabling_get_product_single_attributes($product_dynamic_fields, $product->get_id());

    wc_get_template('single-product/product-document.php', array(
        'pdfs' => $pdfs,
        'icon' => $icon,
        'product_attributes' => $product_attributes,
        'custom_fields' => $custom_fields,
        'dynamic_fields' => $product_dynamic_fields,
        'product_id' => $product->get_id(),
    ));
}

function cabling_woocommerce_after_my_account_modal()
{
    wc_get_template('myaccount/popup/reset-password.php');
}

function cabling_woocommerce_find_a_stockist()
{
    wc_get_template('single-product/product-stockist.php');
}

function cabling_get_brand_product($product_id)
{
    global $product;
    if (empty($product_id))
        $product_id = $product->get_id();
    $data = [];
    $brands = get_the_terms($product_id, 'product-brand');
    if ($brands) {
        foreach ($brands as $brand) {
            $brand_image = get_field('taxonomy_image', $brand);
            if ($brand_image)
                $data[] = sprintf('<a href="%s">%s</a>', get_term_link($brand), wp_get_attachment_image($brand_image, 'full'));
        }
    }
    echo '<div class="product-brands">';
    echo implode('', $data);
    echo '</div>';
}

// Add to list of WC Order statuses
function cabling_add_confirming_order_to_order_statuses($order_statuses)
{

    $new_order_statuses = array();

    // add new order status after processing
    foreach ($order_statuses as $key => $status) {

        $new_order_statuses[$key] = $status;

        if ('wc-processing' === $key) {
            $new_order_statuses['wc-confirming-order'] = 'Confirming Order';
        }
    }

    return $new_order_statuses;
}

add_filter('wc_order_statuses', 'cabling_add_confirming_order_to_order_statuses');

// Change WC Order statuses default
add_action('woocommerce_thankyou', 'cabling_woocommerce_thankyou_change_order_status', 10, 1);
function cabling_woocommerce_thankyou_change_order_status($order_id)
{
    if (!$order_id) return;

    $order = wc_get_order($order_id);

    //if( $order->get_status() == 'processing' )
    $order->update_status('confirming-order');
}

//Add client download to my account page
add_action('woocommerce_after_account_downloads', 'cabling_woocommerce_download_client', 10, 1);
function cabling_woocommerce_download_client()
{
    $user_id = get_current_user_id();
    $parent = get_user_meta($user_id, 'customer_parent', true);

    $id = !empty($parent) ? $parent : $user_id;

    $client_document = get_field('client_document', 'user_' . $id);

    if ($client_document) {
        echo '<div id="accordion">';
        foreach ($client_document as $key => $doc) { ?>
            <div class="card mb-2">
                <div class="card-header">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-<?php echo $key; ?>"
                            aria-expanded="true" aria-controls="collapse-<?php echo $key; ?>">
                        <?php echo $doc["file"]["title"]; ?>
                    </button>
                </div>
                <div id="collapse-<?php echo $key; ?>" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <ul class="list-file-download">
                            <li><?php echo $doc["date"]; ?></li>
                            <li><?php echo $doc["description"]; ?></li>
                            <li><a href="<?php echo $doc["file"]["url"]; ?>"
                                   download><?php echo __('Download', 'cabling'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php }
        echo '</div>';
    } else {
        echo '<div class="woocommerce-Message woocommerce-Message--info woocommerce-info" style="display:block;">' . __('No downloads available yet.', 'cabling') . '</div>';
    }
}

add_filter('password_hint', function ($hint) {
    return __('The password should be at least 8 characters long. Use upper and lower case letters, numbers, and symbols like ! " ? $ % ^ &amp; ).');
});

add_filter('woocommerce_save_account_details_required_fields', 'cabling_custom_edit_account_required_fields');
function cabling_custom_edit_account_required_fields($fields)
{
    return array(
        'account_first_name' => __('First name', 'woocommerce'),
        'account_last_name' => __('Last name', 'woocommerce'),
    );
}

// Save the custom field when the user updates their account details
function save_custom_field_my_account_edit($user_id)
{
    if (isset($_POST['user_title'])) {
        update_user_meta($user_id, 'user_title', sanitize_text_field($_POST['user_title']));
    }
    if (isset($_POST['job_title'])) {
        update_user_meta($user_id, 'job_title', sanitize_text_field($_POST['job_title']));
    }
    if (isset($_POST['user_department'])) {
        update_user_meta($user_id, 'user_department', sanitize_text_field($_POST['user_department']));
    }
    if (isset($_POST['billing_company'])) {
        update_user_meta($user_id, 'billing_company', sanitize_text_field($_POST['billing_company']));
    }
    if (isset($_POST['billing_address_1'])) {
        update_user_meta($user_id, 'billing_address_1', sanitize_text_field($_POST['billing_address_1']));
    }
    if (isset($_POST['billing_country'])) {
        update_user_meta($user_id, 'billing_country', sanitize_text_field($_POST['billing_country']));
    }
    if (isset($_POST['billing_city'])) {
        update_user_meta($user_id, 'billing_city', sanitize_text_field($_POST['billing_city']));
    }
    if (isset($_POST['billing_postcode'])) {
        update_user_meta($user_id, 'billing_postcode', sanitize_text_field($_POST['billing_postcode']));
    }
    if (isset($_POST['company_name_responsible'])) {
        update_user_meta($user_id, 'company_name_responsible', sanitize_text_field($_POST['company_name_responsible']));
    }
    if (isset($_POST['company-sector'])) {
        update_user_meta($user_id, 'company-sector', sanitize_text_field($_POST['company-sector']));
    }
    if (isset($_POST['billing_vat'])) {
        update_user_meta($user_id, 'billing_vat', sanitize_text_field($_POST['billing_vat']));
    }
    if (isset($_POST['billing_phone'])) {
        update_user_meta($user_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
    }
    if (isset($_POST['billing_phone_code'])) {
        update_user_meta($user_id, 'billing_phone_code', sanitize_text_field($_POST['billing_phone_code']));
    }
    if (current_user_can('administrator') && isset($_POST['sap_customer'])) {
        update_user_meta($user_id, 'sap_customer', sanitize_text_field($_POST['sap_customer']));
    }
    if (isset($_POST['account_first_name']) && isset($_POST['account_last_name'])) {
        wp_update_user(array('ID' => $user_id, 'display_name' => $_POST['account_first_name'] . ' ' . $_POST['account_last_name']));
    }
}

add_action('woocommerce_save_account_details', 'save_custom_field_my_account_edit');

function cabling_save_verify_cookie()
{
    if (!empty($_REQUEST['custom_action']) && base64_decode($_REQUEST['custom_action']) === 'verify_customer_cabling') {

        $expiration_time = time() + 30 * 60; // 30 minutes in seconds
        setcookie('verify_customer_cabling_' . $_REQUEST['id'], $_REQUEST['key'], $expiration_time);

    }
}

add_action('init', 'cabling_save_verify_cookie');

function cabling_password_reset_handle($user)
{
    $key = $_POST['reset_key'];
    $verify_cookie = $_COOKIE['verify_customer_cabling_' . $user->ID];
    if ($verify_cookie === $key) {
        //update child user
        update_user_meta($user->ID, 'has_approve', 'true');
        update_user_meta($user->ID, 'customer_level', '2');
        update_user_meta($user->ID, 'has_approve_date', current_time('mysql'));
    }
}

add_action('password_reset', 'cabling_password_reset_handle');

add_action('thmaf_after_address_display', 'add_btn_add_shipping_address', 999);
function add_btn_add_shipping_address()
{
    $user_id = get_current_user_id();
    if (class_exists('THMAF_Utils') && (get_customer_type($user_id) === MASTER_ACCOUNT)) {
        $myaccount_page = get_permalink(get_option('woocommerce_myaccount_page_id'));
        $custom_address = THMAF_Utils::get_custom_addresses($user_id, 'shipping');

        if (empty($custom_address) || (sizeof($custom_address) < 3)) {
            echo '<a href="' . $myaccount_page . 'edit-address/shipping/?atype=add-address" class="button primary is-outline">
               <i class="fa fa-plus"></i>
               Add new address
           </a>';
        }
    }
}

add_filter('woocommerce_show_page_title', '__return_false');
function custom_text_before_product_listing()
{
    $cat = get_queried_object();
    if ($cat->taxonomy === 'product_cat') {
        $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
    } else {
        $thumbnail_id = get_field('taxonomy_image', $cat);
    }
    $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
    get_template_part('template-parts/filter_heading', 'product');
    echo wp_get_attachment_image($thumbnail_id, 'full', false, ['class' => 'my-3']);
    echo '<h1 class="woocommerce-products-header__title page-title">' . woocommerce_page_title(false) . '</h1>';
}

add_action('woocommerce_archive_description', 'custom_text_before_product_listing', 5);


/**
 * @param $user_email
 * @param $customer_id
 * @return void
 */
function send_verify_email($user_email, $customer_id): void
{
    $mailer = WC()->mailer();

    $mailer->recipient = $user_email;

    //$verify_link = get_verification_user_link($customer_id);
    $verify_link = get_reset_password_user_link($customer_id);
    $type = 'emails/verify-child-account.php';
    $subject = __("Hi! Please verify your account!", 'cabling');
    $content = cabling_get_custom_email_html($verify_link, $subject, $mailer, $type);
    $headers = "Content-Type: text/html\r\n";

    $mailer->send($user_email, $subject, $content, $headers);
}

function get_product_category($taxonomy = 'product_cat', $isParent = 0, $includes = [])
{
    $args = array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'parent' => $isParent,
        'exclude' => [7889],
        'orderby' => 'term_order'

    );
    if (!empty($includes)) {
        $args['exclude'] = [];
        $args['include'] = $includes;
    }

    return get_terms($args);
}

function get_product_line_category(string $taxonomy = '', string $meta_key = '', array $meta_values = array(), bool $returnId = false, array $includes = [])
{
    $args = array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'include' => $includes,
        'meta_query' => array(
            //'relation' => 'OR',
            array(
                'key' => $meta_key,
                'value' => $meta_values,
                'compare' => 'IN',
            ),
        ),
    );

    $terms = get_terms($args);

    return $returnId ? wp_list_pluck($terms, 'term_id') : $terms;
}

function get_product_type_category(string $meta_value = '')
{
    global $wpdb;
    $meta_key = 'product_line';
    $taxonomy = 'product_custom_type';

    // Custom SQL query to retrieve terms with specific metadata
    $terms = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT t.*, tt.* FROM {$wpdb->terms} t
            INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            INNER JOIN {$wpdb->termmeta} tm ON t.term_id = tm.term_id
            WHERE tt.taxonomy = %s AND tm.meta_key = %s AND tm.meta_value = %s",
            $taxonomy,
            $meta_key,
            $meta_value
        )
    );

    return $terms;
}

function get_product_category_list()
{
    $taxs = get_product_line_category('product_group', 'family_category', ['8626']);;
    $cat = '';
    if ($taxs) {
        $cat .= '<select name="product_group" id="product_group" class="form-select">';
        $cat .= '<option value="">' . __('Select Category', 'cabling') . '</option>';
        foreach ($taxs as $tax) {
            $cat .= '<option value="' . $tax->term_id . '">' . $tax->name . '</option>';
        }
        $cat .= '</select>';
    }
    return $cat;
}

// Define a function to get product IDs by category ID
function get_product_ids_by_category($taxonomy = '', $term_id = array(), $attributes = array())
{
    $args = array(
        'fields' => 'ids',
        'post_type' => 'product',
        'post_status' => 'publish',
        'numberposts' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $term_id,
            ),
        ),
    );

    if (!empty($attributes)) {
        $meta_query = get_meta_query_from_attributes($attributes);
        $args['meta_query'] = $meta_query;
    }

    $posts = get_posts($args);

    return $posts;
}

function get_filter_lists($get_options = true): array
{
    $transient_name = 'filter_lists_transient';

    //$fieldList = get_transient($transient_name);

    // If the transient data is not available, fetch and set it
    //if ($fieldList === false) {
    $field_group_key = 'group_655f1001b4d9e';
    $fields = acf_get_fields($field_group_key);
    $fieldList = array();
    if ($fields) {
        // Loop through the fields and add them to the $fieldList array
        foreach ($fields as $key => $field) {
            $choices = array();
            $value = '';
            $valueType = '';
            if (is_product()) {
                $postId = get_the_ID();
                $value = get_post_meta($postId, $field['name'], true);
            }

            $label = $field['label'];
            if ('product_operating_temp' === $field['name']) {
                $label = 'Operating Temp';
            }
            if (is_tax('product_custom_type')) {
                $term = get_queried_object();
                $attributes = $_POST['attributes'] ?? [];
                $product_ids = get_product_ids_by_category($term->taxonomy, [$term->term_id], $attributes);

                switch ($field['name']) {
                    case 'product_contact_media':
                        $contact_media = get_field('type_contact_media', $term);

                        if ($contact_media) {
                            $choices = array($contact_media => get_the_title($contact_media));
                        }
                        $valueType = 'key';
                        break;
                    case 'product_material':
                        $product_material = get_field('type_material', $term);

                        if ($product_material) {
                            $choices = array($product_material => get_the_title($product_material));
                            $valueType = 'key';
                        }
                        break;
                    case 'product_compound':
                        $choices = get_acf_taxonomy_options('compound_certification');
                        $valueType = 'key';
                        break;
                    case 'product_colour':
                    case 'product_complance':
                    case 'product_dash_number':
                        $choices = get_all_meta_values_cached($field['name'], $product_ids);
                        break;
                    case 'product_type':
                        $choices = ['Standard Size'];
                        break;
                    default:
                        if ($field['type'] === 'post_object') {
                            $choices = get_acf_post_options($field['post_type'], $product_ids);
                            $valueType = 'key';
                        } elseif (!empty($field['choices'])) {
                            $choices = get_all_meta_values_cached($field['name'], $product_ids);
                            $valueType = 'key';
                        } else {
                            $choices = get_all_meta_values_cached($field['name'], $product_ids);
                        }
                        asort($choices);
                        break;
                }
            } else if ($get_options) {

                if ($field['name'] === 'product_compound') {
                    $choices = get_acf_taxonomy_options('compound_certification');
                    $valueType = 'key';
                } elseif ($field['name'] === 'product_type') {
                    $choices = $field['choices'];
                    $valueType = 'key';
                } elseif ($field['type'] === 'post_object') {
                    $choices = get_acf_post_options($field['post_type']);
                    $valueType = 'key';
                } elseif (!empty($field['choices'])) {
                    $choices = get_all_meta_values_cached($field['name']);
                    //$valueType = 'key';
                } else {
                    $choices = get_all_meta_values_cached($field['name']);
                }
                asort($choices);
            }

            $name = empty($field['name']) ? $key : $field['name'];
            $fieldList[$name] = array(
                'label' => $label,
                'multiple' => $field['multiple'] ?? 0,
                'type' => empty($field['multiple']) ? 'radio' : 'checkbox',
                'field_type' => $field['type'],
                'choices' => $choices,
                'valueType' => $valueType,
                'value' => $value
            );
        }
    }

    //set_transient($transient_name, $fieldList, 24 * HOUR_IN_SECONDS);
    //}
    return $fieldList;
}

add_action('acf/save_post', 'clear_filter_lists_cache', 20);
function clear_filter_lists_cache($post_id)
{
    if (get_post_type($post_id) === 'acf-field-group') {
        $field_group_key = get_field('key', $post_id);

        $target_field_group_key = 'group_655f1001b4d9e';

        if ($field_group_key === $target_field_group_key) {
            $transient_name = 'filter_lists_transient';

            delete_transient($transient_name);
        }
    }
}

function get_all_meta_values_cached($meta_key, array $post_ids = [])
{
    $acf_field = acf_get_field($meta_key);
    //$transient_name = 'all_meta_values_' . md5($meta_key);

    //$values = get_transient($transient_name);
    //if ($values === false) {
    global $wpdb;

    $sql = "SELECT DISTINCT meta_value
            FROM {$wpdb->postmeta}
            WHERE meta_key = %s";

    if (!empty($post_ids)) {
        $placeholders = implode(', ', array_fill(0, count($post_ids), '%s'));
        $sql .= " AND post_id IN ($placeholders) ";
    }

    $sql .= " ORDER BY meta_value";

    $values = $wpdb->get_col(
        $wpdb->prepare(
            $sql,
            $meta_key,
            ...$post_ids
        )
    );
    //set_transient($transient_name, $values, 24 * HOUR_IN_SECONDS);
    //}

    if (!empty($values) && $acf_field['type'] === 'checkbox') {
        $new_values = array();
        foreach ($values as $value) {
            $unserializedData = unserialize($value);

            if ($unserializedData === false || empty($value)) {
                continue;
            } else {
                foreach ($unserializedData as $val) {
                    if (in_array($val, $new_values)) {
                        continue;
                    }
                    $new_values[] = $val;
                }
            }
        }
        $values = $new_values;
    }

    /* if ($meta_key === 'product_complance') {
         var_dump($values);
     }*/
    sort($values);

    return $values;
}

function get_acf_post_options($post_types = [], $product_ids = [])
{
    $args = [
        'post_type' => $post_types,
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ];
    if (!empty($product_ids)) {
        $args['include'] = $product_ids;
    }
    $posts = get_posts($args);
    $list = [];
    if ($posts) {
        foreach ($posts as $post) {
            $list[$post->ID] = $post->post_title;
        }
    }

    return $list;
}

function get_acf_taxonomy_options($taxonomy = ''): array
{
    $args = array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        'parent' => 0,
        'orderby' => 'term_order'

    );
    if (!empty($_REQUEST['certification-compound'])) {
        $args['slug'] = $_REQUEST['certification-compound'];
    }
    $taxonomies = get_terms($args);
    $list = [];
    if ($taxonomies) {
        foreach ($taxonomies as $post) {
            $list[$post->term_id] = $post->name;
        }
    }

    return $list;
}

function get_term_ids_by_attributes(array $product_ids, string $taxonomy = 'product_group'): array
{

    //$product_ids = search_product_by_meta($metas);

    if (empty($product_ids)) {
        return [];
    }

    $terms = get_terms_by_product($taxonomy, $product_ids);
    /*if ($taxonomy == 'compound_certification') {
        var_dump($product_ids);
    }*/
    return $terms;
}

/**
 * @param string $taxonomy
 * @param array $product_ids
 * @return array
 */
function get_terms_by_product(string $taxonomy, array $product_ids): array
{
    global $wpdb;

    $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));

    $sql = $wpdb->prepare("
        SELECT DISTINCT tt.term_id
        FROM {$wpdb->term_taxonomy} AS tt
        LEFT JOIN {$wpdb->term_relationships} AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE tt.taxonomy = %s AND tr.object_id IN ({$placeholders})
    ", $taxonomy, ...$product_ids);

    $results = $wpdb->get_results($sql);
    /*if ($taxonomy == 'compound_certification') {
        var_dump($sql);
    }*/
    if ($wpdb->last_error) {
        error_log('Database error: ' . $wpdb->last_error);
        return [];
    }

    return $results ? wp_list_pluck($results, 'term_id') : [];
}


function search_product_by_meta($metas): array
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'AND',
        )
    );

    foreach ($metas as $meta_key => $meta_values) {
        if ($meta_key === 'compound_certification') {
            continue;
        }
        if (empty($meta_values)) {
            continue;
        }
        if (is_array($meta_values) && sizeof($meta_values)) {
            $meta_array = array(
                'relation' => 'OR',
            );
            if ($meta_key === 'product_compound') {
                $meta_array[] = array(
                    'key' => $meta_key,
                    'value' => $meta_values,
                    'compare' => 'IN',
                );
            } else {
                foreach ($meta_values as $value) {
                    $meta_array[] = array(
                        'key' => $meta_key,
                        'value' => $value,
                        'compare' => 'LIKE'
                    );
                }
            }

            $args['meta_query'][] = $meta_array;
        } else {
            $args['meta_query'][] = array(
                'key' => $meta_key,
                'value' => $meta_values,
                'compare' => '=',
            );
        }
    }
    $posts = get_posts($args);
    return $posts;
}

function redirect_on_product_type()
{
    if (is_tax('product_custom_type')) {
        global $wp_query;

        if (empty($_REQUEST['_wpnonce']) && $wp_query->found_posts === 1) {
            $products = $wp_query->get_posts();
            $productLink = get_the_permalink($products[0]);
            wp_redirect($productLink);
            exit();
        }
    }
}

add_action('template_redirect', 'redirect_on_product_type');

function cabling_change_product_query($query)
{
    if ((is_tax('product_cat') || is_tax('compound_cat') || is_tax('product_custom_type'))) {
        $paged = $query->get('paged');
        if (isset($_REQUEST['data-filter'])) {
            $data = json_decode(base64_decode($_REQUEST['data-filter']), true);
            $attributes = $data['attributes'] ?? [];
        } elseif (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'product-category-filter') && !empty($_POST['attributes'])) {
            $attributes = $_POST['attributes'];
        } else {
            return $query;
        }

        $paged = $_POST['paged'] ?? $paged;

        $custom_filter = $attributes;
        $old_meta_query = $query->get('meta_query');
        if (!empty($attributes['product_compound'])) {
            $attributes['product_compound'] = get_compound_product($attributes['product_compound']);
        }
        unset($attributes['group-type']);
        $meta_query = get_meta_query_from_attributes($attributes);

        $query->set('meta_query', array_merge($old_meta_query, $meta_query));
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'product_dash_number');
        $query->set('order', 'ASC');
        $query->set('paged', $paged);
        $query->set('custom_filter', $custom_filter);
    }
    return $query;
}

add_action('woocommerce_product_query', 'cabling_change_product_query');

/**
 * @param $attributes
 * @return array
 */
function get_meta_query_from_attributes($attributes): array
{
    $meta_query['relation'] = 'AND';

    foreach ($attributes as $meta_key => $meta_values) {
        if (empty($meta_values)) {
            continue;
        }
        if ($meta_key === 'product_compound') {
            //$choices = get_acf_taxonomy_options('compound_certification');
            //var_dump($meta_values, $choices);
            continue;
        }
        if ($meta_key === 'compound_certification') {
            continue;
        }
        if (is_array($meta_values) && sizeof($meta_values)) {
            $meta_array = array();
            foreach ($meta_values as $value) {
                if (empty($value)) {
                    continue;
                }
                $query = array(
                    'key' => $meta_key,
                    'value' => $value,
                    'compare' => '='
                );
                if ($meta_key === 'product_contact_media' || $meta_key === 'product_complance' || $meta_key === 'product_colour') {
                    $query['value'] = serialize(strval($value));
                    $query['compare'] = 'LIKE';
                }
                $meta_array[] = $query;
            }

            if (count($meta_array) === 1) {
                $meta_query[] = $meta_array[0];
                continue;
            }

            if (count($meta_array) > 1) {
                $meta_query[] = array(
                    'relation' => 'OR',
                    $meta_array
                );
            }
        } else {
            $meta_query[] = array(
                'key' => $meta_key,
                'value' => $meta_values,
                'compare' => '='
            );
        }
    }
    return $meta_query;
}

function selected_filter($name, $value): bool
{
    $metas = $_REQUEST['attributes'];
    if (isset($metas[$name])) {
        if (is_array($metas[$name]) && in_array($value, $metas[$name])) {
            return true;
        } else if ($metas[$name] == $value) {
            return true;
        }
    }
    return false;
}

function show_product_filter_input_name($slug, $attribute): string
{
    if ($slug === 'product_type') {
        $name = 'name="product_type"';
    } else {
        $name = 'name="attributes[' . $slug . '][]"';
    }
    return $name;
}

function order_woocommerce_countries($countries)
{
    $firstItems = [];
    if (isset($countries['US'])) {
        $firstItems['US'] = $countries['US'];
        unset($countries['US']);
    }
    if (isset($countries['CA'])) {
        $firstItems['CA'] = $countries['CA'];
        unset($countries['CA']);
    }
    return array_merge($firstItems, $countries);
}

add_filter('woocommerce_countries', 'order_woocommerce_countries');

add_filter('woocommerce_sort_countries', '__return_false');

add_action('wp_enqueue_scripts', 'remove_woocommerce_gallery_scripts', 99);
function remove_woocommerce_gallery_scripts()
{
    if (function_exists('is_product') && is_product()) {
        wp_dequeue_style('photoswipe');
        wp_dequeue_style('wc-photoswipe');
        wp_dequeue_style('photoswipe-default-skin');
        wp_dequeue_script('photoswipe');
        wp_dequeue_script('wc-gallery');
        wp_dequeue_script('photoswipe-ui-default');
    }
}

/**
 * @param mixed $taxonomy
 * @return string
 */
function getTaxonomyThumbnail(mixed $taxonomy, string $class = ''): string
{
    $thumbnail_id = get_field('taxonomy_image', $taxonomy);
    $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
    $thumbnail = wp_get_attachment_image($thumbnail_id, 'full', false, ['class' => $class]);
    return $thumbnail;
}

function woocommerce_no_products_quote()
{
    cabling_add_quote_button();
}

add_action('woocommerce_no_products_found', 'woocommerce_no_products_quote', 99);

/**
 * @param array $data
 * @param array $termFilters
 * @return array|null
 */
function get_available_attributes(array $product_ids): ?array
{
    try {
        if (empty($product_ids)) {
            return null;
        }
        global $wpdb;

        $post_ids_placeholder = implode(',', array_fill(0, count($product_ids), '%d'));
        //get only values for product acf fields
        $meta_keys = array(
            'inches_id',
            'inches_width',
            'inches_od',
            'milimeters_id',
            'milimeters_od',
            'milimeters_width',
            'product_contact_media',
            'product_operating_temp',
            'product_dash_number',
            'product_colour',
            'product_compound',
            'product_complance',
            'product_material',
            'product_hardness'
        );

        $meta_key_placeholders = implode(',', array_fill(0, count($meta_keys), '%s'));

        $query = $wpdb->prepare(
            "SELECT meta_key, meta_value
                        FROM $wpdb->postmeta
                        WHERE post_id IN ($post_ids_placeholder)
                        AND meta_key IN ($meta_key_placeholders)
                        ORDER BY meta_value ASC",
            array_merge($product_ids, $meta_keys)
        );

        $meta_values = $wpdb->get_results($query, ARRAY_A);

        $resultMetas = array();

        foreach ($meta_values as $meta) {
            if (empty($meta['meta_value']) || $meta['meta_value'] == 'null') {
                continue;
            }

            if (!isset($resultMetas[$meta['meta_key']])) {
                $resultMetas[$meta['meta_key']] = array();
            }

            if (in_array($meta['meta_value'], $resultMetas[$meta['meta_key']])) {
                continue;
            }

            $unserializedData = unserialize($meta['meta_value']);

            if ($unserializedData === false) {
                $resultMetas[$meta['meta_key']][] = $meta['meta_value'];
            } else {
                foreach ($unserializedData as $val) {
                    if (in_array($val, $resultMetas[$meta['meta_key']])) {
                        continue;
                    }
                    $resultMetas[$meta['meta_key']][] = $val;
                }
            }

        }
        //we must get the certifications of compound
        //$resultMetas['product_compound'] = $data['compound_certification'];

        return $resultMetas;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return null;
    }
    return null;
}

function company_name_field()
{
    $departments = CRMConstant::FUNCTION_FIELD;

    asort($departments);

    if (isset($_REQUEST['company-sector'])) {
        $company = $_REQUEST['company-sector'];
    } elseif (is_user_logged_in()) {
        $company = esc_attr(get_user_meta(get_current_user_id(), 'company-sector', true));
    } else {
        $company = '';
    }
    return show_product_field('company-sector', array(
        'options' => $departments,
        'label' => __('Company Sector', 'woocommerce'),
        'default' => $company,
        'class' => ' form-group has-focus mt-4 ',
        'required' => true
    ));
}

function get_name_title($value = null)
{
    $titles = CRMConstant::TITLE;
    if (!empty($value)) {
        return array_search($value, $titles);
    }
    return $titles;
}

function get_product_of_interests($value = null)
{
    $product_of_interests = CRMConstant::PRODUCT;
    if (!empty($value)) {
        $id = array_search($value, $product_of_interests);

        return (string)$id ?? '';
    }
    return $product_of_interests;
}

function get_desired_applications($value = null)
{
    $desired_applications = CRMConstant::COMPOUND;
    if (!empty($value)) {
        return in_array($value, $desired_applications) ? $value : '';
    }
    return $desired_applications;
}

function product_of_interest_field($value = '')
{
    $product_of_interests = get_product_of_interests();

    $field = '';
    $options = '<option value="">' . __('Choose an option', 'woocommerce') . '</option>';
    foreach ($product_of_interests as $option_text) {
        $options .= '<option value="' . esc_attr($option_text) . '" ' . selected($value, $option_text, false) . '>' . esc_html($option_text) . '</option>';
    }

    $field .= '<select name="product-of-interest" id="product-of-interest" class="select form-select" required>' . $options . '</select>';

    echo '<p class="form-row w-100"><label for="product-of-interest">' . __('Product Of Interest', 'woocommerce') . '<span class="required">*</span></label>' . $field . '</p>';
}

function product_desired_application_field($value = '')
{
    echo show_product_field('o_ring[desired-application]', array(
        'options' => CRMConstant::COMPOUND,
        'label' => __('Desired Application', 'woocommerce'),
        'default' => $value
    ));
}

function product_material_field($value = '')
{
    echo show_product_field('o_ring[material]', array(
        'options' => CRMConstant::MATERIAL,
        'label' => __('Material', 'woocommerce'),
        'default' => $value
    ));
}

function product_harness_field($value = '')
{
    echo show_product_field('o_ring[hardness]', array(
        'options' => CRMConstant::HARDNESS,
        'label' => __('Hardness', 'woocommerce'),
        'default' => $value
    ));
}

function show_product_field($name, $options = array()): string
{
    $default = $options['default'] ?? '';
    $required = empty($options['required']) ? '' : 'required';
    $requiredLabel = empty($options['required']) ? '' : '<span class="required">*</span>';
    $option = '<option value="">' . __('Choose an option', 'woocommerce') . '</option>';
    foreach ($options['options'] as $key => $option_text) {
        $selectKey = empty($options['key']) ? $option_text : $key;
        $option .= '<option value="' . esc_attr($selectKey) . '" ' . selected($default, $selectKey, false) . '>' . esc_html($option_text) . '</option>';
    }

    $field = '<select name="' . $name . '" id="' . $name . '" class="select form-select" ' . $required . '>' . $option . '</select>';

    return '<div class="w-100 form-group has-focus' . ($options['class'] ?? '') . '">' . $field . '<label for="' . $name . '">' . $options['label'] . $requiredLabel . '</label></div>';
}

function debug_log($subject, $body)
{
    wp_mail('daisy.nguyen0806@gmail.com,jose.martins@infolabix.com', $subject, $body);
}

function show_product_filter_input_value($attribute, $value)
{
    if ($attribute === 'product_dash_number') {
        return str_replace('AS', '', $value);
    }
    return $value;
}

function get_product_filter_link($isBack = false): string
{
    if (isset($_POST['attributes'])) {
        $attributes = array(
            'attributes' => $_POST['attributes'],
            'paged' => $_POST['paged'] ?? 1,
        );
        $data = base64_encode(json_encode($attributes));
    } elseif (isset($_GET['data-filter'])) {
        $data = $_GET['data-filter'];
    } else {
        $data = '';
    }
    if ($isBack){
        $link = home_url('/products-and-services');
        $history = $data;
    } else {
        $previous_link = add_query_arg('data-filter', $data, get_term_link(get_queried_object()));
        $link = get_the_permalink();
        $history = base64_encode($previous_link);
    }
    return add_query_arg('data-history', $history, $link);
}

add_filter('woocommerce_add_error', 'woocommerce_add_error_callback');
function  woocommerce_add_error_callback($message)
{
    if ($message === 'Invalid username or email.'){
        $message = __( 'Invalid email. Please try again!', 'woocommerce' );
    }
    return $message;
}
