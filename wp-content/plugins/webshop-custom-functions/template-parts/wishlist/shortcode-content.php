<?php
if ( empty($wishlist_products) ) return;

$total_data = array();
?>
<div class="wishlist-products">
    <form action="">
        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="all-wishlist">
                    <div class="row">
                        <?php foreach ($wishlist_products as $product_id): ?>
                            <?php $product = wc_get_product( $product_id );  ?>
                            <?php $total_data[$product->get_id()] = 1;  ?>
                            <div class="col-12 col-lg-4 col-md-4">
                                <div class="wishlist_item">
                                    <div class="product-image mb-2">
                                        <a href="<?php echo $product->get_permalink() ?>">
                                            <?php echo $product->get_image() ?>
                                        </a>
                                    </div>
                                    <h4 class="product-name">
                                        <a href="<?php echo $product->get_permalink() ?>"><?php echo $product->get_name() ?></a>
                                    </h4>
                                    <p class="product-stock"><?php echo $product->is_in_stock() ? 'In Stock' : 'Out of Stock'; ?></p>
                                    <div class="form-group mb-1">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity[<?php echo esc_attr($product->get_id()); ?>]" value="1">
                                    </div>
                                    <div class="mb-3">
                                        <a class="quantity-update" href="#"><?php echo __('recalculate', 'cabling'); ?></a>
                                    </div>
                                    <div class="product-price amount mb-2" style="min-height: 36px">
                                        <?php echo $product->get_price_html() ?: '--' ?>
                                    </div>
                                    <div class="d-flex action justify-content-between">
                                        <?php if ('' !== $product->get_price()): ?>
                                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>?add-to-cart=<?php echo esc_attr($product->get_id()); ?>"
                                               class="add-to-cart-button">
                                                <i class="fa-light fa-shopping-cart me-2"></i>
                                                <span><?php echo __('Add to cart', 'cabling'); ?></span>
                                            </a>
                                        <?php endif ?>
                                        <div data-action="<?php echo esc_attr($product->get_id()); ?>" class="product-request-button show-product-quote mb-0">
                                            <a class="btn btn-primary" href="#"><?php echo __('Request a quote', 'cabling'); ?></a>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between share-action my-3">
                                        <a href="#" class="remove-wishlist-product w-action" data-product="<?php echo esc_attr($product->get_id()); ?>">
                                            <i class="fa-light fa-solid fa-x me-1"></i>
                                            <span><?php echo __('Remove', 'cabling'); ?></span>
                                        </a>
                                        <a href="#" class="share-wishlist-product w-action" data-product="<?php echo esc_attr($product->get_id()); ?>">
                                            <i class="fa-regular fa-arrow-up-from-bracket me-1"></i>
                                            <span><?php echo __('Share', 'cabling'); ?></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="wishlist-total woocommerce">
                    <?php wc_get_template('template-parts/wishlist/wishlist-totals.php', ['wishlist_products' => $total_data], '', WBC_PLUGIN_DIR); ?>
                </div>
            </div>
        </div>
    </form>
</div>
