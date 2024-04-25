<?php

class GIWishlist
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts',  array($this, 'gi_wishlist_scripts'));
        add_shortcode('gi_wishlist', array($this, 'gi_wishlist_callback'));
    }

    public function gi_wishlist_scripts()
    {
        wp_enqueue_script('gi-wishlist', WBC_PLUGIN_URL . '/assets/js/wishlist.js', array(), null, true);
    }

    public function gi_wishlist_callback()
    {
        ob_start() ?>
        <h1>Wishlist</h1>
        <?php

        return ob_get_clean();
    }

}

new GIWishlist();
