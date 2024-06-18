<div class="informed-header">
    <h2 class="text-center"><?php _e('Keep Me Informed', 'cabling'); ?></h2>
    <h4 class="text-center"><?php _e('Please keep me informed about the following', 'cabling'); ?></h4>
</div>
<?php
$current_user = wp_get_current_user();
$user_email = @$current_user->user_email ? $current_user->user_email : '';
$kmi_marketing_agreed = get_option($user_email . '_kmi_marketing_agreed');
?>
<form id="keep-informed-form" class="keep-informed-account" method="post">
    <div class="informed-categories">
        <?php if (false) { ?>
        <?php if ($product_category): ?>
            <div class="informed-product mb-3">
                <h5><?php _e('Product Categories', 'cabling'); ?></h5>
                <div class="product-category-wrapper">
                    <?php foreach ($product_category as $category): ?>
                        <?php $checked = in_array($category->term_id, $category_informed) ? ' checked="checked" ' : '' ?>
                        <div class="form-check">
                            <input class="form-check-input" name="category[]" type="checkbox"
                                <?php echo $checked; ?>
                                   id="cat-<?php echo $category->term_id ?>" value="<?php echo $category->term_id ?>">
                            <label class="form-check-label"
                                   for="cat-<?php echo $category->term_id ?>"><?php echo $category->name ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($news_category): ?>
            <div class="informed-news mb-3">
                <h5><?php _e('News', 'cabling'); ?></h5>
                <div class="product-category-wrapper">
                    <?php foreach ($news_category as $cat): ?>
                        <?php $news_checked = in_array($cat->term_id, $category_informed) ? ' checked="checked" ' : '' ?>
                        <div class="form-check">
                            <input class="form-check-input" name="category[]" type="checkbox"
                                <?php echo $news_checked; ?>
                                   id="cat-<?php echo $cat->term_id ?>" value="<?php echo $cat->term_id ?>">
                            <label class="form-check-label"
                                   for="cat-<?php echo $cat->term_id ?>"><?php echo $cat->name ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($blog_category): ?>
            <div class="informed-blog mb-3">
                <h5><?php _e('Blog', 'cabling'); ?></h5>
                <div class="product-category-wrapper">
                    <?php foreach ($blog_category as $blog_cat): ?>
                        <?php $blog_checked = in_array($blog_cat->term_id, $category_informed) ? ' checked="checked" ' : '' ?>
                        <div class="form-check">
                            <input class="form-check-input" name="category[]" type="checkbox"
                                <?php echo $blog_checked; ?>
                                   id="cat-<?php echo $blog_cat->term_id ?>" value="<?php echo $blog_cat->term_id ?>">
                            <label class="form-check-label"
                                   for="cat-<?php echo $blog_cat->term_id ?>"><?php echo $blog_cat->name ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php } ?>
    <!--<h4 class="informed-heading-contact text-center pre-heading heading-center"><?php _e('CONTACT PREFERENCES', 'cabling'); ?></h4>-->
    <div class="informed-contact informed-categories">
        <!--        <h5><?php _e('CONTACT PREFERENCES', 'cabling'); ?></h5>-->
        <div class="form-check">
            <input class="form-check-input" name="informed_channel[email]" type="checkbox" checked="checked"
                <?php echo 'style="display:none"' ?>
                   id="cat-email" value="email">
            <label class="form-check-label" for="cat-email">Email</label>
            <?php if (!is_user_logged_in()) { ?>
                <div class="channel-email form-group form-check">
                    <input type="email" class="form-control" id="channel-email" name="channel-email" required
                           placeholder="Enter your email*" value="<?php //echo $channel['email'] ?? '' ?>">
                </div>

            <?php } else {
                $current_user = wp_get_current_user();
                ?>
                <div class="channel-email form-group form-check w-100">
                    <input type="email" class="form-control" id="channel-email" name="channel-email" required
                           placeholder="Enter your email*" value="<?php
                    echo $current_user->user_email;
                    // $channel['email'] ?? '' ?>"
                           readonly>
                </div>


            <?php } ?>
        </div>
        <!--<div class="form-check">
            <input class="form-check-input" name="informed_channel[whatsapp]" type="checkbox"
                <?php /*echo empty($channel['whatsapp']) ? '' : ' checked="checked" ' */ ?>
                   id="cat-whatsapp" value="whatsapp">
            <label class="form-check-label" for="cat-whatsapp">Whatsapp</label>
            <?php /*if (!is_user_logged_in()): */ ?>
                <div class="channel-whatsapp form-group form-check">
                    <input type="tel" class="form-control" id="mobile-phone-informed"
                           value="<?php /*echo $channel['whatsapp'] ?? '' */ ?>"
                           placeholder="<?php /*_e('Whatsapp Number', 'cabling') */ ?>">
                    <span id="mobile-phone-validate" class="hidden input-error"></span>
                    <input type="hidden" class="phone_number" name="whatsapp_number">
                    <input type="hidden" class="phone_code" name="whatsapp_number_code">
                </div>
            <?php /*endif; */ ?>
        </div>
        <div class="form-check">
            <input class="form-check-input" name="informed_channel[sms]" type="checkbox"
                <?php /*echo empty($channel['sms']) ? '' : ' checked="checked" ' */ ?>
                   id="cat-sms" value="sms">
            <label class="form-check-label" for="cat-sms">SMS</label>
            <?php /*if (!is_user_logged_in()): */ ?>
                <div class="channel-sms form-group form-check">
                    <input type="tel" class="form-control" id="sms-phone-informed"
                           value="<?php /*echo $channel['sms'] ?? '' */ ?>"
                           placeholder="<?php /*_e('SMS Number', 'cabling') */ ?>">
                    <span id="mobile-phone-validate" class="hidden input-error"></span>
                    <input type="hidden" class="phone_number" name="sms_number">
                    <input type="hidden" class="phone_code" name="sms_number_code">
                </div>
            <?php /*endif; */ ?>
        </div>-->
        <?php //if (!is_user_logged_in()): ?>
            <div class="mb-3 d-flex" style="text-align: left; font-size: 12px">
                <input type="hidden" name="kmi_marketing_agreed" value="no">
                <input type="checkbox" id="agree-term-condition" name="kmi_marketing_agreed" value="yes"
                <?= $kmi_marketing_agreed == 'yes' || $kmi_marketing_agreed == 1 ? 'checked' : '';?>
                >
                <label class="ps-2" for="agree-term-condition">
                    <?php
                    printf(__('Please tick this box if you would like to receive electronic newsletters from Datwyler. You can change your preference at any time in your account settings or by contacting Datwyler at %s . Datwyler shall process your personal data in accordance with its privacy notice, which can be found %s.', 'cabling'),
                        '<a href="mailto:suso.ont.sales@datwyler.com">suso.ont.sales@datwyler.com</a>',
                        '<a target="_blank" href="' . home_url("/privacy") . '">' . __("here", "cabling") . '</a>',
                    )
                    ?>
                </label>
            </div>
        <?php //endif; ?>
        <div class="mb-3">
            <div id="informed-recaptcha" class="g-recaptcha"
                 data-sitekey="<?php echo get_field('gcapcha_sitekey_v2', 'option'); ?>"></div>
        </div>
    </div>

    <div class="mb-3 informed-submit">
        <?php wp_nonce_field('setting-account-action') ?>
        <div class="btn-submit wp-block-button block-button-black">
            <button type="submit" class="wp-element-button"><?php _e('Confirm', 'cabling'); ?></button>
        </div><!--

        <button type="button" class="btn btn-secondary btn-closed" data-bs-dismiss="modal">Close</button>-->
    </div>
    <div class="woo-notice" role="alert" style="display: none;"></div>
</form>
