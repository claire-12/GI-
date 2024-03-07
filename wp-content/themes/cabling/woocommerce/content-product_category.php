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
?>
<tr class="<?php echo implode(' ', $filterValues) ?>">
	<td><?php cabling_add_quote_button($product->get_id()) ?></td>
    <?php foreach ($fieldList as $key => $attribute): ?>
         <?php $value = get_product_field($key, $product->get_id()); ?>
        <?php if ($key === '_sku'): ?>
            <td><a href="<?php the_permalink(); ?>"><?php echo $value ?? '---' ?></td>
        <?php else: ?>
            <td class="has-text-align-center"
                data-filter="<?php echo $key ?>"
                data-align="center"><?php echo $value ?? '---' ?></td>
        <?php endif ?>
    <?php endforeach ?>
<!--    <td><?php cabling_add_quote_button($product->get_id()) ?></td> -->
</tr>
