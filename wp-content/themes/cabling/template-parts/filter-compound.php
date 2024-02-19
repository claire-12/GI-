<?php $compoundLists = get_product_category('compound_certification'); ?>
<div class="filter-blog">
    <h2 class="filter-heading"><?php _e('Compounds', 'cabling') ?></h2>
    <div class="compound-list">
        <?php foreach ($compoundLists as $compound): ?>
            <a href="<?php echo get_term_link($compound)?>"><?php echo $compound->name ?></a>
        <?php endforeach; ?>
    </div>
    <?php cabling_add_quote_button(); ?>
</div>
