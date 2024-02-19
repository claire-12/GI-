<?php if (!empty($key_benefits)): ?>
    <div class="product-simple-wrapper product-key_benefits">
        <h4><?php echo __('Key Benefits', 'cabling') ?></h4>
        <div class="content-wrapper">
            <?php echo $key_benefits ?>
        </div>
    </div>
<?php endif ?>

<?php if (!empty($use_with) || !empty($do_not_use_with)): ?>
    <div class="product-simple-wrapper product-chemical-resistance mt-5">
        <h4><?php echo __('Chemical Resistance', 'cabling') ?></h4>
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 col-lg-6 ">
                    <div class="use-with">
                        <h5><?php echo __('USE WITH', 'cabling') ?></h5>
                        <?php echo $use_with ?>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="not-use-with">
                        <h5><?php echo __('DO NOT USE WITH', 'cabling') ?></h5>
                        <?php echo $do_not_use_with ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if (!empty($typical_values_for_compound)): ?>
    <div class="product-simple-wrapper product-typical-values-for-compound mt-5">
        <h4><?php echo __('Typical Values for Compound', 'cabling') ?></h4>
        <div class="content-wrapper">
            <?php echo $typical_values_for_compound ?>
        </div>
    </div>
<?php endif ?>
