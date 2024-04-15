<div class="product-quote-section">
    <div class="product-quote-inner">
        <h2><?php _e('Know what you need?', 'cabling'); ?></h2>
        <p><?php _e('Chat to one of our advisors today', 'cabling'); ?></p>
        <div class="wp-block-buttons">
            <div class="wp-block-button block-button-black">
                <a class="wp-element-button show-product-quote" data-action="<?php echo is_product() ? get_the_ID() : 0 ?>"
                   href="<?php echo home_url('/request-a-quote/') ?>"><?php _e('Request a quote', 'cabling'); ?></a>
            </div>
        </div>
    </div>
</div>
