<?php if(empty($address) && !is_array($address)) {
    return;
            }
            ?>
<div id="cart_shipping_form_wrap">
    <input type="hidden" name="thmaf_hidden_field_shipping" value="<?php echo $address_key ?? '' ?>">
        <div>
                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_first_name" id="shipping_first_name"
                                       value="<?php echo $address['shipping_first_name']['value'] ?? '' ?>"
                                       required>
                                <label for="shipping_first_name" class="form-label">
                                    First name<span class="required">*</span>
                                </label>
                            </div>
                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_last_name" id="shipping_last_name"
                                       value="<?php echo $address['shipping_last_name']['value'] ?? '' ?>"
                                       required>
                                <label for="shipping_last_name" class="form-label">
                                    Last name<span class="required">*</span>
                                </label>
                            </div>
                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_company" id="shipping_company"
                                       value="<?php echo $address['shipping_company']['value'] ?? '' ?>"
                                       required>
                                <label for="shipping_company" class="form-label">
                                    Company<span class="required">*</span>
                                </label>
                            </div>
                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_address_1" id="shipping_address_1"
                                       value="<?php echo $address['shipping_address_1']['value'] ?? '' ?>"
                                       required>
                                <label for="shipping_address_1" class="form-label">Address<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_address_2" id="shipping_address_2"
                                       value="<?php echo $address['shipping_address_2']['value'] ?? '' ?>"
                                       >
                                <label for="shipping_address_2" class="form-label">Address 2</label>
                            </div>

                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_city" id="shipping_city"
                                       value="<?php echo $address['shipping_city']['value'] ?? '' ?>"
                                       required>
                                <label for="shipping_city" class="form-label">City<span
                                            class="required">*</span></label>
                            </div>
                            <div class="mb-3 form-group has-focus">
                                <input type="text" class="form-control" name="shipping_postcode"
                                       id="shipping_postcode"
                                       value="<?php echo $address['shipping_postcode']['value'] ?? '' ?>"
                                       required>
                                <label for="shipping_postcode" class="form-label">Postcode<span
                                            class="required">*</span></label>
                            </div>
                            <?php echo show_product_field('shipping_country', array(
                                'options' => CRMCountry::getCountries(),
                                'label' => __('Country', 'woocommerce'),
                                'class' => 'form-group has-focus mb-3',
                                'required' => true,
                                'key' => true,
                                'id' => 'billing_country',
                                'default' => $address['shipping_country']['value'],
                            )); ?>
                            <?php
                            echo show_product_field('shipping_state', array(
                                'options' => CRMCountry::getStatesByCountryCode($address['shipping_country']['value'] ?? ''),
                                'label' => __('State', 'woocommerce'),
                                'class' => 'form-group has-focus mb-4 mt-3',
                                'required' => true,
                                'key' => true,
                                'id' => 'billing_state',
                                'default' => $address['shipping_state']['value'] ?? '',
                            ));
                            ?>

        </div>
</div>
