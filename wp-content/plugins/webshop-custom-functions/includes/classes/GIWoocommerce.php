<?php

class GIWoocommerce
{

    public function __construct()
    {
        add_action('woocommerce_after_add_to_cart_quantity', array($this, 'gi_after_add_to_cart_quantity'));
        add_action('woocommerce_after_add_to_cart_button', array($this, 'gi_woocommerce_after_add_to_cart_button'));
        add_filter('woocommerce_return_to_shop_redirect', array($this, 'gi_woocommerce_return_to_shop_redirect'));
    }

    public function gi_after_add_to_cart_quantity()
    {
        echo '<div class="clear py-2"></div>';
    }
    public function gi_woocommerce_after_add_to_cart_button()
    {
        echo '<button type="button" class="button add-to-wishlist ms-2" data-product="'. get_the_ID() .'"><i class="fa-light fa-heart me-2"></i>'. __('Add to wishlist', 'cabling') .'</button>';
    }
    public function gi_woocommerce_return_to_shop_redirect()
    {
        return home_url('/products-and-services/');
    }
}
new GIWoocommerce();
