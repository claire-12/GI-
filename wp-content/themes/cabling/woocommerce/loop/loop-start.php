<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<?php if (is_tax('product_cat') || is_tax('compound_cat') || is_tax('product_custom_type')): ?>
<?php $product_attributes = cabling_get_product_table_attributes(); ?>
<div class="table-responsive">
    <table class="table table-bordered product-variation-table">
        <thead>
        <tr>
            <?php foreach ($product_attributes as $key => $attribute): ?>
                <th class="has-text-align-center" data-align="center" data-filter="<?php echo $key ?>"><?php echo $attribute ?></th>
            <?php endforeach ?>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php else: ?>
        <ul class="products columns-<?php echo esc_attr(wc_get_loop_prop('columns')); ?>">
            <?php endif ?>
