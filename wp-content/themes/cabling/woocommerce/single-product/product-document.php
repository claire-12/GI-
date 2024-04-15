<div class="product-overview mb-3">
    <h4><?php echo __('Overview', 'cabling'); ?></h4>
    <div class="pdp-attribute mb-3">
        <div class="row">
            <?php if (!empty($custom_fields['attributes'])): ?>
                <?php foreach ($custom_fields['attributes'] as $label): ?>
                    <div class="col-12 col-lg-6">
                        <div class="overview-item">
                            <h5><?php echo $label['label'] ?></h5>
                            <p class="value"><?php echo $label['value'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif ?>
            <?php if (!empty($custom_fields['size'])): ?>
                <div class="col-12">
                    <div class="row">
                        <?php foreach ($custom_fields['size'] as $labelS): ?>
                            <div class="col-12 col-lg-6">
                                <div class="overview-item">
                                    <h5><?php echo $labelS['label'] ?></h5>
                                    <p class="value"><?php echo $labelS['value'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif ?>
            <?php if (!empty($pdfs)): ?>
                <div class="col-12">
                    <div class="pdp-document my-3" style="padding: 0 15px">
                        <h5><?php _e('Documentation', 'cabling') ?></h5>
                        <div class="row">
                            <?php foreach ($pdfs as $pdf): ?>
                                <?php
                                $file_url = wp_get_attachment_url($pdf['file']);
                                $filetype = wp_check_filetype($file_url);
                                ?>
                                <div class="col-12 col-lg-6">
                                    <div class="documentation-item mt-2 d-flex">
                                        <div class="type">
                                            <img src="<?php echo str_replace("%image%", $filetype["ext"], $icon) ?>"
                                                 alt="<?php echo $pdf['title'] ?>">
                                        </div>
                                        <div class=" ms-2 file-download">
                                            <p><?php echo $pdf['title'] ?></p>
                                            <a href="<?php echo esc_url($file_url) ?>"
                                               download=""><?php echo __('Download', 'cabling') ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if (!empty($dynamic_fields)): ?>
                <?php foreach ($dynamic_fields as $fields): ?>
                    <?php if (isset($custom_fields['attributes'][$fields['label']])) continue; ?>
                    <div class="col-12 col-lg-6">
                        <div class="overview-item">
                            <h5><?php echo $fields['label'] ?></h5>
                            <p class="value"><?php echo $fields['value'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif ?>
        </div>
    </div>
</div>
