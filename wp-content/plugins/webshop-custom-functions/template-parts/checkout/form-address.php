<div id="cart_shipping_form_wrap">
    <input type="hidden" name="thmaf_hidden_field_shipping" value="<?php echo $address_key ?? '' ?>">
        <div>
            <?php if(!empty($address) && is_array($address)) {
                foreach ($address as $key => $field) {
                    woocommerce_form_field($key, $field, wc_get_post_data_by_key($key, $field['value']));
                }
            }
            do_action("woocommerce_after_edit_address_form_shipping");?>
        </div>
</div>
