<div class="form-stockist-wrapper my-5">
    <h4><?php echo __('Products Available', 'cabling'); ?></h4>
    <div class="table-responsive">
        <table class="table table-bordered product-variation-table">
            <thead>
            <tr>
                <?php foreach ($attributes as $key => $attribute): ?>
                    <th class="has-text-align-center" data-align="center"><?php echo $attribute ?></th>
                <?php endforeach ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($variations as $variation): ?>
                <tr>
                    <?php foreach ($attributes as $key => $attribute): ?>
                        <?php $value = get_post_meta($variation->ID, $key, true); ?>
                        <td class="has-text-align-center"
                            data-filter="<?php echo sanitize_title($key . $value) ?>"
                            data-align="center"><?php echo get_post_meta($variation->ID, $key, true) ?></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
