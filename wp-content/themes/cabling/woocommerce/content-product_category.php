<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
$fieldList = cabling_get_product_table_attributes();
$filterValues = show_filter_value($fieldList, $product->get_id());

$product_link = get_product_filter_link();
$col_number = 0;

$user_id = get_current_user_id();
$wishlist_products = get_user_meta( $user_id, 'wishlist_products', true );
$class = '';

if ( is_array( $wishlist_products ) && in_array( $product->get_id(), $wishlist_products ) ) {
    $class = 'has-wishlist';
}
?>
<tr class="product-row <?php echo implode(' ', $filterValues) ?>">
    <?php foreach ($fieldList as $key => $attribute): $col_number++; ?>
        <?php $value = get_product_field($key, $product->get_id()); ?>
        <?php if ($key === '_sku'): ?>
            <td><a href="<?php echo esc_url($product_link); ?>"><?php echo $value ?? '---' ?></td>
        <?php else: ?>
            <td class="has-text-align-center"
                data-filter="<?php echo $key ?>"
                data-align="center"><?php echo $value ?? '---' ?></td>
        <?php endif ?>
    <?php endforeach ?>
</tr>
<tr class="add-cart-row">
    <?php if (empty($product->get_price())): ?>
        <td
           colspan="<?php echo $col_number ?>"
           class="has-text-align-center"
           data-align="center"
        >
            <?php cabling_add_quote_button($product->get_id()) ?>
        </td>
    <?php else: ?>
        <td
           colspan="<?php echo $col_number ?>"
           class="has-text-align-center"
           data-align="center"
        >
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>?add-to-cart=<?php echo esc_attr($product->get_id()); ?>"
               class="add-to-cart-button">
                <i class="fa-light fa-shopping-cart me-2"></i>
                <span><?php echo __('Add to cart', 'cabling'); ?></span>
            </a>
            <a href="#" class="add-to-cart-button add-to-wishlist ms-2 <?php echo $class ?>"
               data-product="<?php echo esc_attr($product->get_id()); ?>">
                <i class="fa-light fa-heart me-2"></i>
                <span><?php echo __('Add to wishlist', 'cabling'); ?></span>
            </a>
        </td>
    <?php endif ?>
</tr>
