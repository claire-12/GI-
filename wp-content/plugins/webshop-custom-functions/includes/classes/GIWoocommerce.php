<?php

class GIWoocommerce
{

    public function __construct()
    {
        add_action('init', array($this, 'gi_create_wishlist_page'));
        add_action('woocommerce_after_add_to_cart_quantity', array($this, 'gi_after_add_to_cart_quantity'));
        add_action('woocommerce_after_add_to_cart_button', array($this, 'gi_woocommerce_after_add_to_cart_button'));
        add_action('woocommerce_cart_is_empty', array($this, 'gi_woocommerce_woocommerce_cart_is_empty'));
        #ref GID-1044: p1
        add_action('woocommerce_before_checkout_form', array($this, 'gi_woocommerce_woocommerce_cart_is_empty'));
        add_action('wp_footer', array($this, 'gi_woocommerce_add_address_modal'));
        add_action('wp_ajax_gi_update_shipping_address', array($this, 'gi_update_shipping_address_callback'));
        add_action('wp_ajax_gi_get_modal_address_content', array($this, 'gi_get_modal_address_content_callback'));
        // add_action('woocommerce_checkout_terms_and_conditions', array($this, 'gi_woocommerce_add_wp_form_9'));
        add_action('woocommerce_checkout_update_order_review', array($this, 'gi_woocommerce_checkout_update_order_meta'));

        add_filter('woocommerce_return_to_shop_redirect', array($this, 'gi_woocommerce_return_to_shop_redirect'));
        #ref GID-1044: m1
        // add_filter('woocommerce_checkout_must_be_logged_in_message', array($this, 'gi_woocommerce_checkout_is_not_logged'));
        add_filter('woocommerce_checkout_gi_add_wp_form_9', array($this, 'gi_woocommerce_add_wp_form_9'));
    }

    public function gi_after_add_to_cart_quantity()
    {
        echo '<div class="clear py-2"></div>';
    }

    public function gi_woocommerce_after_add_to_cart_button()
    {
        global $product;

        $user_id = get_current_user_id();
        $wishlist_products = get_user_meta($user_id, 'wishlist_products', true);
        $class = '';

        if (is_array($wishlist_products) && in_array($product->get_id(), $wishlist_products)) {
            $class = 'has-wishlist';
        }

        echo '<button type="button" class="button add-to-wishlist ms-2 ' . $class . '" data-product="' . get_the_ID() . '"><i class="fa-light fa-heart me-2"></i>' . __('Add to wishlist', 'cabling') . '</button>';
    }

    public function gi_woocommerce_return_to_shop_redirect()
    {
        return home_url('/products-and-services/');
    }

    public function gi_woocommerce_woocommerce_cart_is_empty()
    {
        if (is_user_logged_in() && function_exists('wc_empty_cart_message')) {
            // wc_empty_cart_message();
        } else {
            wc_get_template('template-parts/wishlist/form-login.php', [], '', WBC_PLUGIN_DIR);
        }
    }
    public function gi_woocommerce_checkout_is_not_logged()
    {
        ob_start();
        wc_get_template('template-parts/wishlist/form-login.php', [], '', WBC_PLUGIN_DIR);
        return ob_get_clean();
    }

    public function gi_woocommerce_add_address_modal()
    {
        if (is_checkout()) {
            wc_get_template('template-parts/add-address-popup.php', [], '', WBC_PLUGIN_DIR);
        }
    }

    public function gi_create_wishlist_page()
    {
        $page_title = 'My Wishlist';

        $page_check = get_page_by_path(sanitize_title($page_title));

        if (!$page_check) {
            $page_data = array(
                'post_title' => $page_title,
                'post_content' => '[gi_wishlist]',
                'post_status' => 'publish',
                'post_type' => 'page'
            );

            $page_id = wp_insert_post($page_data);
        }
    }

    public function gi_update_shipping_address_callback()
    {
        parse_str($_REQUEST['data'], $cart_shipping_data);

        $user_id = get_current_user_id();
        $load_address = sanitize_key('shipping');
        $country = $cart_shipping_data['shipping_country'] ?? '';
        $address = WC()->countries->get_address_fields(wc_clean(wp_unslash($country)), $load_address . '_');

        $address_data = array();
        $address_new = array();

        if (!empty($address) && is_array($address)) {
            foreach ($address as $key => $field) {
                if (!empty($cart_shipping_data) && is_array($cart_shipping_data)) {
                    $address_data[$key] = array(
                        'label' => $field['label'] ?? '',
                        'value' => $cart_shipping_data[$key] ?? '',
                        'required' => $field['required'] ?? '',
                        'type' => $field['type'] ?? '',
                        'validate' => $field['validate'] ?? ''
                    );
                    $address_new[$key] = $cart_shipping_data[$key] ?? '';
                }
            }
        }

        // Validate the form.
        $true_check = THMAF_Public_Checkout::validate_cart_shipping_addr_data($address_data, $address_new);
        if ($true_check == 'true') {
            $address_key = $cart_shipping_data['thmaf_hidden_field_shipping'];
            self::update_address_to_user($user_id, $address_new, 'shipping', $address_key);

            $message = '<div class="alert alert-success d-flex align-items-center" role="alert"><i class="fa-solid fa-circle-check me-2"></i>
                <div>'. __('Address Changed successfully.', 'woocommerce') .'</div>
            </div>';

            wp_send_json_success($message);
        } else {
            $message = '<div class="alert alert-danger d-flex align-items-center" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i>
                <div>'. $true_check .'</div>
            </div>';

            wp_send_json_error($message);
        }
    }

        /**
         * function for update address to user.
         *
         * @param integer $user_id The user id
         * @param array $address The address
         * @param string $type The billing or shipping data
         * @param string $address_key The address key info
         *
         */
        public static function update_address_to_user($user_id, $address, $type, $address_key) {
            $custom_addresses = get_user_meta($user_id, THMAF_Utils::ADDRESS_KEY, true);
            $exist_custom = $custom_addresses[$type] ?? '';
            $custom_address[$address_key] = $address;
            $exist_custom = is_array($exist_custom) ? $exist_custom :  array();
            $custom_addresses[$type] = array_merge($exist_custom, $custom_address);

            update_user_meta($user_id, THMAF_Utils::ADDRESS_KEY, $custom_addresses);

            if ($custom_addresses['default_shipping'] === $address_key){
                foreach($address as $key => $addr){
                    update_user_meta($user_id, $key, $addr);
                }

                // Trigger WooCommerce hooks to update internal data
                do_action( 'woocommerce_customer_save_address', $user_id, 'shipping' );
                do_action( 'woocommerce_saved_address', $user_id, 'shipping' );
                do_action( 'woocommerce_customer_save_address', $user_id, 'both' );
                do_action( 'woocommerce_saved_address', $user_id, 'both' );
            }
        }
    public function gi_get_modal_address_content_callback()
    {   $address_fields = $_REQUEST['address_fields'];
        $country      = get_user_meta(get_current_user_id(), 'shipping_country', true);
        $address = WC()->countries->get_address_fields($country, 'shipping_');

        if(!empty($address) && is_array($address)) {
            foreach ($address as $key => $field) {
                $address[ $key ]['value'] = $address_fields[$key] ?? '';
            }
        }
        ob_start();
        wc_get_template('template-parts/checkout/form-address.php', ['address' => $address, 'address_key' => $_REQUEST['address_key']], '', WBC_PLUGIN_DIR);
        $content = ob_get_clean();
        wp_send_json_success($content);
    }
    public function gi_woocommerce_add_wp_form_9()
    {
        $customer_level = get_customer_level(get_current_user_id());
        $customer_level = 1;
        if ($customer_level === 1) {
            wc_get_template('template-parts/checkout/form-wp9.php', [], '', WBC_PLUGIN_DIR);
        }
    }
    public function gi_woocommerce_checkout_update_order_meta()
    {
        if (!empty($_FILES['form-w9']['name'])) {
            WC()->cart->remove_taxes();
            /*$upload = wp_upload_bits($_FILES['form-w9']['name'], null, file_get_contents($_FILES['form-w9']['tmp_name']));
            if(isset($upload['error']) && $upload['error'] != 0) {
                return;
            } else {
                update_post_meta($order_id, 'user_wp9_form', $upload);
                // Remove all tax calculations
                remove_all_actions('woocommerce_calc_tax');
                remove_all_actions('woocommerce_after_order_total_tax');
            }*/
        }
    }
}

new GIWoocommerce();
