<?php
function wishlist_totals_subtotal_html($wishlist_products)
{
    $totals = 0;
    if (!empty($wishlist_products)) {
        foreach ($wishlist_products as $wishlist => $qty) {
            $product = wc_get_product($wishlist);
            $totals += (float)$product->get_price() * intval($qty);
        }
    }

    return wc_price($totals);
}
