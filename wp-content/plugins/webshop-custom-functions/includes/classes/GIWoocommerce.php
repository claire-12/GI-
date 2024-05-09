<?php

class GIWoocommerce
{

    public function __construct()
    {
        add_action('init', array($this, 'gi_create_wishlist_page'));
        add_action('woocommerce_after_add_to_cart_quantity', array($this, 'gi_after_add_to_cart_quantity'));
        add_action('woocommerce_after_add_to_cart_button', array($this, 'gi_woocommerce_after_add_to_cart_button'));
        add_action('woocommerce_cart_is_empty', array($this, 'gi_woocommerce_woocommerce_cart_is_empty'));
        add_action('wp_footer', array($this, 'gi_woocommerce_add_address_modal'));

        add_filter('woocommerce_return_to_shop_redirect', array($this, 'gi_woocommerce_return_to_shop_redirect'));
    }

    public function gi_after_add_to_cart_quantity()
    {
        echo '<div class="clear py-2"></div>';
    }

    public function gi_woocommerce_after_add_to_cart_button()
    {
        global $product;

        $user_id = get_current_user_id();
        $wishlist_products = get_user_meta( $user_id, 'wishlist_products', true );
        $class = '';

        if ( is_array( $wishlist_products ) && in_array( $product->get_id(), $wishlist_products ) ) {
            $class = 'has-wishlist';
        }

        echo '<button type="button" class="button add-to-wishlist ms-2 '. $class .'" data-product="' . get_the_ID() . '"><i class="fa-light fa-heart me-2"></i>' . __('Add to wishlist', 'cabling') . '</button>';
    }

    public function gi_woocommerce_return_to_shop_redirect()
    {
        return home_url('/products-and-services/');
    }
    public function gi_woocommerce_woocommerce_cart_is_empty()
    {
        if (is_user_logged_in() && function_exists('wc_empty_cart_message')) {
            wc_empty_cart_message();
        } else {
            wc_get_template('template-parts/wishlist/form-login.php', [], '', WBC_PLUGIN_DIR);
        }
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

        $page_check = get_page_by_path( sanitize_title( $page_title ));

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
}

new GIWoocommerce();
