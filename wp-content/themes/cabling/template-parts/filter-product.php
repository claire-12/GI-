<?php
$product_cats = $args['categories'] ?? [];
$product_cat = $args['product_cat'] ? $args['product_cat']->term_id : 0;
$attributes = get_filter_lists();
$inches_id_choices = get_all_meta_values_cached('inches_id');
$inches_width_choices = get_all_meta_values_cached('inches_width');
$inches_od_choices = get_all_meta_values_cached('inches_od');
$milimeters_id_choices = get_all_meta_values_cached('milimeters_id');
$milimeters_od_choices = get_all_meta_values_cached('milimeters_od');
$milimeters_width_choices = get_all_meta_values_cached('milimeters_width');
?>
<div class="filter-blog">

    <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <?php wp_nonce_field('product-category-filter') ?>
        <?php if (!empty($_REQUEST['material'])): ?>
            <input type="hidden" class="pre_filter" data-action="product_material"
                   value="<?php echo $_REQUEST['material']; ?>">
        <?php endif ?>
        <div class="accordion" id="accordionFilterBlog">
            <?php if ($product_cats): ?>
                <div class="accordion-item filter-checkbox">
                    <h2 class="accordion-header" id="panelsStayOpen-headingCat">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#panelsStayOpen-collapseCat" aria-expanded="true"
                                aria-controls="panelsStayOpen-collapseCat">
                            <?php _e('Product', 'cabling') ?>
                        </button>
                    </h2>
                    <div id="panelsStayOpen-collapseCat" class="accordion-collapse collapse show"
                         aria-labelledby="panelsStayOpen-headingCat">
                        <div class="accordion-body">
                            <?php foreach ($product_cats as $category): ?>
                                <div class="form-check filter-category" data-meta-key-none="group-type"
                                     data-value="<?php echo $category->term_id; ?>">
                                    <input class="form-check-input" type="checkbox" name="group-type[]"
                                           value="<?php echo $category->term_id; ?>"
                                           title="<?php echo $category->name; ?>"
                                        <?php echo ($category->term_id == $product_cat) ? 'checked=checked' : ''; ?>
                                           id="category-<?php echo $category->slug; ?>">
                                    <label class="form-check-label" for="category-<?php echo $category->slug; ?>">
                                        <?php echo $category->name; ?>
                                        <i class="fa-regular fa-check"></i>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <div class="accordion-item filter-checkbox filter-attribute filter-size">
                <h2 class="accordion-header" id="panelsStayOpen-heading-sizeFT">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapse-sizeFT"
                            aria-expanded="true"
                            aria-controls="panelsStayOpen-collapse-sizeFT">
                        <?php echo __('Size', 'cabling') ?>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapse-sizeFT"
                     class="accordion-collapse collapse show filter-size"
                     aria-labelledby="panelsStayOpen-heading-sizeFT">
                    <div class="accordion-body">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item filter-inch">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseOne" aria-expanded="false"
                                            aria-controls="flush-collapseOne">
                                        <?php echo __('Inches', 'cabling') ?>
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse filter-size-inner"
                                     aria-labelledby="flush-headingOne"
                                     data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="accordion-body">
                                            <div class="accordion-item filter-checkbox filter-attribute">
                                                <h2 class="accordion-header" id="panelsStayOpen-heading-InchesID">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapse-InchesID"
                                                            aria-expanded="false"
                                                            aria-controls="panelsStayOpen-collapse-InchesID">
                                                        <?php echo __('ID', 'cabling') ?>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapse-InchesID"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="panelsStayOpen-heading-InchesID">
                                                    <div class="accordion-body <?php echo count($inches_id_choices) > 4 ? 'filter-scroll' : '' ?>">
                                                        <?php foreach ($inches_id_choices as $inches_id): if (empty($inches_id)) continue; ?>
                                                            <div class="form-check filter-category"
                                                                 data-meta-key="inches_id"
                                                                 data-value="<?php echo $inches_id; ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="attributes[inches_id][]"
                                                                       value="<?php echo $inches_id; ?>"
                                                                       title="<?php echo $inches_id; ?>"
                                                                       id="category-<?php echo sanitize_title('inches_id' . $inches_id); ?>">
                                                                <label class="form-check-label"
                                                                       for="category-<?php echo sanitize_title('inches_id' . $inches_id); ?>">
                                                                    <?php echo $inches_id; ?>
                                                                    <i class="fa-regular fa-check"></i>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item filter-checkbox filter-attribute">
                                                <h2 class="accordion-header" id="panelsStayOpen-heading-inches_od">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapse-inches_od"
                                                            aria-expanded="false"
                                                            aria-controls="panelsStayOpen-collapse-inches_od">
                                                        <?php echo __('OD', 'cabling') ?>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapse-inches_od"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="panelsStayOpen-heading-inches_od">
                                                    <div class="accordion-body <?php echo count($inches_od_choices) > 4 ? 'filter-scroll' : '' ?>">
                                                        <?php foreach ($inches_od_choices as $inches_od): if (empty($inches_od)) continue; ?>
                                                            <div class="form-check filter-category"
                                                                 data-meta-key="inches_od"
                                                                 data-value="<?php echo $inches_od; ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="attributes[inches_od][]"
                                                                       value="<?php echo $inches_od; ?>"
                                                                       title="<?php echo $inches_od; ?>"
                                                                       id="category-<?php echo sanitize_title('inches_od' . $inches_od); ?>">
                                                                <label class="form-check-label"
                                                                       for="category-<?php echo sanitize_title('inches_od' . $inches_od); ?>">
                                                                    <?php echo $inches_od; ?>
                                                                    <i class="fa-regular fa-check"></i>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item filter-checkbox filter-attribute">
                                                <h2 class="accordion-header" id="panelsStayOpen-heading-InchesWidth">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapse-InchesWidth"
                                                            aria-expanded="false"
                                                            aria-controls="panelsStayOpen-collapse-InchesWidth">
                                                        <?php echo __('Width', 'cabling') ?>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapse-InchesWidth"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="panelsStayOpen-heading-InchesWidth">
                                                    <div class="accordion-body <?php echo count($inches_width_choices) > 4 ? 'filter-scroll' : '' ?>">
                                                        <?php foreach ($inches_width_choices as $inches_width): if (empty($inches_width)) continue; ?>
                                                            <div class="form-check filter-category"
                                                                 data-meta-key="inches_width"
                                                                 data-value="<?php echo $inches_width; ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="attributes[inches_width][]"
                                                                       value="<?php echo $inches_width; ?>"
                                                                       title="<?php echo $inches_width; ?>"
                                                                       id="category-<?php echo sanitize_title('inches_width' . $inches_width); ?>">
                                                                <label class="form-check-label"
                                                                       for="category-<?php echo sanitize_title('inches_width' . $inches_width); ?>">
                                                                    <?php echo $inches_width; ?>
                                                                    <i class="fa-regular fa-check"></i>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item filter-millimeter">
                                <h2 class="accordion-header" id="flush-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapseTwo" aria-expanded="false"
                                            aria-controls="flush-collapseTwo">
                                        <?php echo __('Millimeters', 'cabling') ?>
                                    </button>
                                </h2>
                                <div id="flush-collapseTwo" class="accordion-collapse collapse filter-size-inner"
                                     aria-labelledby="flush-headingTwo"
                                     data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="accordion-body">
                                            <div class="accordion-item filter-checkbox filter-attribute">
                                                <h2 class="accordion-header"
                                                    id="panelsStayOpen-heading-MillimetersID">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapse-MillimetersID"
                                                            aria-expanded="true"
                                                            aria-controls="panelsStayOpen-collapse-MillimetersID">
                                                        <?php echo __('ID', 'cabling') ?>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapse-MillimetersID"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="panelsStayOpen-heading-MillimetersID">
                                                    <div class="accordion-body <?php echo count($milimeters_id_choices) > 4 ? 'filter-scroll' : '' ?>">
                                                        <?php foreach ($milimeters_id_choices as $milimeters_id): if (empty($milimeters_id)) continue; ?>
                                                            <div class="form-check filter-category"
                                                                 data-meta-key="milimeters_id"
                                                                 data-value="<?php echo $milimeters_id; ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="attributes[milimeters_id][]"
                                                                       value="<?php echo $milimeters_id; ?>"
                                                                       title="<?php echo $milimeters_id; ?>"
                                                                       id="category-<?php echo sanitize_title('milimeters_id' . $milimeters_id); ?>">
                                                                <label class="form-check-label"
                                                                       for="category-<?php echo sanitize_title('milimeters_id' . $milimeters_id); ?>">
                                                                    <?php echo $milimeters_id; ?>
                                                                    <i class="fa-regular fa-check"></i>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item filter-checkbox filter-attribute">
                                                <h2 class="accordion-header"
                                                    id="panelsStayOpen-heading-MillimetersOD">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapse-MillimetersOD"
                                                            aria-expanded="false"
                                                            aria-controls="panelsStayOpen-collapse-MillimetersOD">
                                                        <?php echo __('OD', 'cabling') ?>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapse-MillimetersOD"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="panelsStayOpen-heading-MillimetersOD">
                                                    <div class="accordion-body <?php echo count($milimeters_od_choices) > 4 ? 'filter-scroll' : '' ?>">
                                                        <?php foreach ($milimeters_od_choices as $milimeters_od): if (empty($milimeters_od)) continue; ?>
                                                            <div class="form-check filter-category"
                                                                 data-meta-key="milimeters_od"
                                                                 data-value="<?php echo $milimeters_od; ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="attributes[milimeters_od][]"
                                                                       value="<?php echo $milimeters_od; ?>"
                                                                       title="<?php echo $milimeters_od; ?>"
                                                                       id="category-<?php echo sanitize_title('milimeters_od' . $milimeters_od); ?>">
                                                                <label class="form-check-label"
                                                                       for="category-<?php echo sanitize_title('milimeters_od' . $milimeters_od); ?>">
                                                                    <?php echo $milimeters_od; ?>
                                                                    <i class="fa-regular fa-check"></i>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item filter-checkbox filter-attribute">
                                                <h2 class="accordion-header"
                                                    id="panelsStayOpen-heading-MillimetersWidth">
                                                    <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#panelsStayOpen-collapse-MillimetersWidth"
                                                            aria-expanded="true"
                                                            aria-controls="panelsStayOpen-collapse-MillimetersWidth">
                                                        <?php echo __('Width', 'cabling') ?>
                                                    </button>
                                                </h2>
                                                <div id="panelsStayOpen-collapse-MillimetersWidth"
                                                     class="accordion-collapse collapse"
                                                     aria-labelledby="panelsStayOpen-heading-MillimetersWidth">
                                                    <div class="accordion-body <?php echo count($milimeters_width_choices) > 4 ? 'filter-scroll' : '' ?>">
                                                        <?php foreach ($milimeters_width_choices as $milimeters_width): if (empty($milimeters_width)) continue; ?>
                                                            <div class="form-check filter-category"
                                                                 data-meta-key="milimeters_width"
                                                                 data-value="<?php echo $milimeters_width; ?>">
                                                                <input class="form-check-input" type="checkbox"
                                                                       name="attributes[milimeters_width][]"
                                                                       value="<?php echo $milimeters_width; ?>"
                                                                       title="<?php echo $milimeters_width; ?>"
                                                                       id="category-<?php echo sanitize_title('milimeters_width' . $milimeters_width); ?>">
                                                                <label class="form-check-label"
                                                                       for="category-<?php echo sanitize_title('milimeters_width' . $milimeters_width); ?>">
                                                                    <?php echo $milimeters_width; ?>
                                                                    <i class="fa-regular fa-check"></i>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-size-quote">
                            <div>Need a custom size? Just send us a quote request.</div>
                            <?php cabling_add_quote_button() ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($attributes)): ?>
                <?php foreach ($attributes as $slug => $attribute): ?>
                    <?php if ($slug === 'product_complance' || $slug === 'product_type') {
                        continue;
                    } ?>
                    <?php if ($attribute['field_type'] === 'message'): ?>
                        <h3 class="filter-heading"><?php echo $attribute['label'] ?></h3>
                    <?php else: ?>
                        <div class="accordion-item filter-checkbox filter-attribute <?php echo 'filter-' . $slug ?>">
                            <h2 class="accordion-header" id="panelsStayOpen-heading<?php echo $slug ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapse<?php echo $slug ?>"
                                        aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapse<?php echo $slug ?>">
                                    <?php echo $attribute['label'] ?? '---' ?>
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapse<?php echo $slug ?>"
                                 class="accordion-collapse collapse show"
                                 aria-labelledby="panelsStayOpen-heading<?php echo $slug ?>">
                                <div class="accordion-body <?php echo count($attribute['choices']) > 4 ? 'filter-scroll' : '' ?>">
                                    <?php foreach ($attribute['choices'] as $key => $value): if (empty($value)) continue; ?>
                                        <div class="form-check filter-category" data-meta-key="<?php echo $slug ?>"
                                             data-value="<?php echo $attribute['valueType'] === 'key' ? $key : $value; ?>">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                <?php echo show_product_filter_input_name($slug, $attribute) ?>
                                                   value="<?php echo $attribute['valueType'] === 'key' ? $key : $value; ?>"
                                                   title="<?php echo $value; ?>"
                                                   id="category-<?php echo sanitize_title($slug . $value); ?>">
                                            <label class="form-check-label"
                                                   for="category-<?php echo sanitize_title($slug . $value); ?>">
                                                <?php echo show_product_filter_input_value($slug, $value); ?>
                                                <i class="fa-regular fa-check"></i>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach; ?>
            <?php endif ?>
            <div class="custom-size-quote">
                <div>Need a hardness / durometer measurement not listed here? Just send us a quote request.</div>
                <?php cabling_add_quote_button() ?>
            </div>
        </div>
    </form>
</div>
