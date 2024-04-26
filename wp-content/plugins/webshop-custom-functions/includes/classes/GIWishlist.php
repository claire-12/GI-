<?php

class GIWishlist
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts',  array($this, 'gi_wishlist_scripts'));
        add_action('wp_ajax_gi_add_to_wishlist',  array($this, 'gi_add_to_wishlist_callback'));

        add_shortcode('gi_wishlist', array($this, 'gi_wishlist_callback'));
    }

    public function gi_wishlist_scripts(): void
    {
        wp_enqueue_script('gi-wishlist', WBC_PLUGIN_URL . '/assets/js/wishlist.js', array(), null, true);
        wp_localize_script( 'gi-wishlist', 'wishlist_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function gi_wishlist_callback(): bool|string
    {
        ob_start();
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $wishlist_products = get_user_meta( $user_id, 'wishlist_products', true );
            if ( $wishlist_products ) {
                wc_get_template('template-parts/wishlist/shortcode-content.php', ['wishlist_products' => $wishlist_products], '', WBC_PLUGIN_DIR);
            } else {
                echo 'Your wishlist is empty.';
            }
        }
        return ob_get_clean();
    }

    public function gi_add_to_wishlist_callback(){
        if ( isset( $_REQUEST['product_id'] ) && is_user_logged_in() ) {
            $user_id = get_current_user_id();
            $product_id = $_REQUEST['product_id'];
            $wishlist_products = get_user_meta( $user_id, 'wishlist_products', true );
            if ( ! $wishlist_products ) {
                $wishlist_products = array();
            }

            $action = 'remove_wishlist';
            if ( in_array( $product_id, $wishlist_products ) ) {
                $index = array_search( $product_id, $wishlist_products );
                if ( $index !== false ) {
                    unset( $wishlist_products[$index] );
                }
            } else {
                $wishlist_products[] = $product_id;
                $action = 'add_wishlist';
            }

            update_user_meta( $user_id, 'wishlist_products', $wishlist_products );

            wp_send_json_success($action);
        } else {
            wp_send_json_error( 'Error adding product to wishlist.' );
        }
        wp_die();
    }

}

new GIWishlist();
