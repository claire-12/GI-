<?php

// Add custom select field to variation options
function custom_add_variation_field($loop, $variation_data, $variation)
{
    // Get all product attributes
    $product_attributes = cabling_get_product_attributes($variation->post_parent);

    $count = 0;
    foreach ($product_attributes as $key => $attribute_label) {
        if ($key === '_sku') continue;
        // Get terms for the current attribute
        $terms = get_terms(array('taxonomy' => $key, 'hide_empty' => false));

        $field_value = get_post_meta($variation->ID, $key, true);
        $select_options = [
            '' => 'Select an option',
        ];
        foreach ($terms as $term) {
            $select_options[$term->name] = $term->name;
        }
        ?>
        <div class="form-row form-row-<?php echo ($count % 2) === 0 ? 'first' : 'last'; ?>">
            <?php
            woocommerce_wp_select(
                array(
                    'id' => "{$key}[{$loop}]",
                    'name' => "{$key}[{$loop}]",
                    'label' => ucwords($attribute_label),
                    'value' => esc_attr($field_value),
                    'options' => $select_options,
                )
            );
            ?>
        </div>
        <?php
        ++$count;
    }
}

add_action('woocommerce_variation_options_pricing', 'custom_add_variation_field', 10, 3);

// Save custom field value
function custom_save_variation_field($variation_id, $i)
{
    // Get all product attributes
    $product_attributes = wc_get_attribute_taxonomies();

    foreach ($product_attributes as $attribute) {
        $attribute_name = $attribute->attribute_name;

        $field_key = 'pa_' . $attribute_name;
        $custom_field_name = $_POST[$field_key][$i];
        if (isset($custom_field_name)) {
            update_post_meta($variation_id, $field_key, esc_attr($custom_field_name));
        }
    }
}

add_action('woocommerce_save_product_variation', 'custom_save_variation_field', 10, 2);
