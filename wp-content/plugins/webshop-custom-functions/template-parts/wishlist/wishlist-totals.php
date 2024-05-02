<?php
if ( empty($wishlist_products) ) return;
?>
<div class="cart-totals woocommerce-cart">
    <h2 class="hidden">Cart totals</h2>
    <table cellspacing="0" class="shop_table shop_table_responsive">
        <tbody>
        <tr class="cart-subtotal">
            <th>Subtotal</th>
            <td data-title="Subtotal"><?php echo wishlist_totals_subtotal_html($wishlist_products); ?></td>
        </tr>
        <tr class="order-total">
            <th>Total</th>
            <td data-title="Total">
                <?php echo wishlist_totals_subtotal_html($wishlist_products); ?>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="wc-proceed-to-checkout"><a href="#" class="checkout-button wishlist-to-cart btn btn-primary alt wc-forward">Checkout</a></div>
</div>
