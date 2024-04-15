<div class="product-variable-filter woo-sidebar">
    <div class="filter-blog">
        <form method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <div class="accordion" id="accordionFilterBlog">
                <?php if (!empty($args['attributes'])): ?>
                    <?php foreach ($args['attributes'] as $slug => $attribute): ?>
                        <div class="accordion-item filter-checkbox filter-attribute">
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
                                <div class="accordion-body">
                                    <?php foreach ($attribute['value'] as $value): ?>
                                        <div class="form-check filter-category">
                                            <input class="form-check-input" type="checkbox" name="<?php echo $slug ?>[]"
                                                   value="<?php echo sanitize_title($slug . $value); ?>"
                                                   title="<?php echo $value; ?>"
                                                   id="category-<?php echo sanitize_title($slug . $value); ?>">
                                            <label class="form-check-label"
                                                   for="category-<?php echo sanitize_title($slug . $value); ?>">
                                                <?php echo $value; ?>
                                                <i class="fa-regular fa-check"></i>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif ?>
            </div>
        </form>
    </div>
</div>
