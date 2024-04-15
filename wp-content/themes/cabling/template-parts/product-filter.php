<form method="get" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <input type="checkbox" name="product_category[]" value="" checked>
        All Categories
    </label>

    <?php
    // Get all product categories
    $categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => false));

    foreach ($categories as $category) {
        echo '<label><input type="checkbox" name="product_category[]" value="' . $category->slug . '"> ' . $category->name . '</label>';
    }
    ?>

    <?php
    // Example for attribute filtering
    $attribute = 'pa_color';
    $terms = get_terms($attribute);

    if ($terms) :
        ?>
        <label>
            <input type="checkbox" name="<?php echo esc_attr($attribute); ?>[]" value="" checked>
            All Colors
        </label>
        <?php
        foreach ($terms as $term) {
            echo '<label><input type="checkbox" name="' . esc_attr($attribute) . '[]" value="' . $term->slug . '"> ' . $term->name . '</label>';
        }
    endif;
    ?>

    <input type="submit" value="Filter">
</form>
