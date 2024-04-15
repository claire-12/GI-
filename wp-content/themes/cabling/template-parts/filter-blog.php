<div class="filter-blog">
    <h2 class="filter-heading"><?php _e('Filter by', 'cabling') ?></h2>
    <form action="" id="blog-filter">
        <input type="hidden" name="paged" value="1">
        <input type="hidden" name="post_type" value="company_news">
        <div class="accordion" id="accordionFilterBlog">
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                            aria-controls="panelsStayOpen-collapseOne">
                        <?php _e('Date', 'cabling') ?>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                     aria-labelledby="panelsStayOpen-headingOne">
                    <div class="accordion-body">
                        <div class="d-flex">
                            <input type="text" id="blog-from-date" class="me-2" name="from-date" placeholder="<?php echo __('FROM'); ?>">
                            <input type="text" id="blog-to-date" name="to-date" placeholder="<?php echo __('TO'); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                            aria-controls="panelsStayOpen-collapseTwo">
                        <?php _e('Sort by', 'cabling') ?>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show"
                     aria-labelledby="panelsStayOpen-headingTwo">
                    <div class="accordion-body">
                        <select name="order" id="blog-order" class="form-select">
                            <option value="newest"><?php echo __('Newest - Oldest'); ?></option>
                            <option value="oldest"><?php echo __('Oldest - Newest'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="accordion-item filter-checkbox">
                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                            aria-controls="panelsStayOpen-collapseThree">
                        <?php _e('Subject Area', 'cabling') ?>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show"
                     aria-labelledby="panelsStayOpen-headingThree">
                    <div class="accordion-body">
                        <?php if ($args['categories']): ?>
                            <?php foreach ($args['categories'] as $category): ?>
                                <div class="form-check filter-category">
                                    <input class="form-check-input" type="checkbox" name="news-category[]"
                                           value="<?php echo $category->term_id; ?>"
                                           id="category-<?php echo $category->slug; ?>">
                                    <label class="form-check-label" for="category-<?php echo $category->slug; ?>">
                                        <?php echo $category->name; ?>
                                        <i class="fa-regular fa-check"></i>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-headingFour">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false"
                            aria-controls="panelsStayOpen-collapseFour">
                        <?php _e('TAGS', 'cabling') ?>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show"
                     aria-labelledby="panelsStayOpen-headingFour">
                    <div class="accordion-body">
                        <?php if ($args['tags']): ?>
                            <?php foreach ($args['tags'] as $tag): ?>
                                <div class="form-check filter-tag">
                                    <input class="form-check-input" type="checkbox" name="news_tag[]"
                                           value="<?php echo $tag->term_id; ?>" id="tag-<?php echo $tag->slug; ?>">
                                    <label class="form-check-label" for="tag-<?php echo $tag->slug; ?>">
                                        <?php echo $tag->name; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
