<h2><?php _e('Account Setting / Keep me Informed', 'cabling'); ?></h2>
<form method="post">
    <?php if ($product_category): ?>
        <div class="mb-3">
            <h5><?php _e('Product Categories', 'cabling'); ?></h5>
            <div class="product-category-wrapper">
            <?php foreach ($product_category as $category): ?>
                <?php if (isset($user_informed['product_cat'])) $checked = in_array($category->term_id, $user_informed['product_cat']) ? ' checked="checked" ' : '' ?>
                <div class="form-check">
                    <input class="form-check-input" name="category['product_cat'][]" type="checkbox"
                           <?php echo $checked ?? ''; ?>
                           id="cat-<?php echo $category->term_id ?>" value="<?php echo $category->term_id ?>">
                    <label class="form-check-label"
                           for="cat-<?php echo $category->term_id ?>"><?php echo $category->name ?></label>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($news_category): ?>
        <div class="mb-3">
            <h5><?php _e('News', 'cabling'); ?></h5>
            <div class="product-category-wrapper">
            <?php foreach ($news_category as $cat): ?>
                <?php if (isset($user_informed['news-category'])) $news_checked = in_array($cat->term_id, $user_informed['news-category']) ? ' checked="checked" ' : '' ?>
                <div class="form-check">
                    <input class="form-check-input" name="category['news-category'][]" type="checkbox"
                           <?php echo $news_checked ?? ''; ?>
                           id="cat-<?php echo $cat->term_id ?>" value="<?php echo $cat->term_id ?>">
                    <label class="form-check-label"
                           for="cat-<?php echo $cat->term_id ?>"><?php echo $cat->name ?></label>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($blog_category): ?>
        <div class="mb-3">
            <h5><?php _e('Blog', 'cabling'); ?></h5>
            <div class="product-category-wrapper">
            <?php foreach ($blog_category as $blog_cat): ?>
                <?php if (isset($user_informed['category'])) $blog_checked = in_array($blog_cat->term_id, $user_informed['category']) ? ' checked="checked" ' : '' ?>
                <div class="form-check">
                    <input class="form-check-input" name="category[category][]" type="checkbox"
                           <?php echo $blog_checked ?? ''; ?>
                           id="cat-<?php echo $blog_cat->term_id ?>" value="<?php echo $blog_cat->term_id ?>">
                    <label class="form-check-label"
                           for="cat-<?php echo $blog_cat->term_id ?>"><?php echo $blog_cat->name ?></label>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <h5><?php _e('CONTACT PREFERENCES', 'cabling'); ?></h5>
        <div class="form-check">
            <input class="form-check-input" name="informed_channel[email]" type="checkbox"
                   <?php if (isset($user_informed['informed_channel'])) echo in_array('email', $user_informed['informed_channel']) ? ' checked="checked" ' : '' ?>
                   id="cat-email" value="email">
            <label class="form-check-label" for="cat-email">Email</label>
        </div>
        <div class="mb-3 channel-email">
            <label for="channel-email" class="form-label">Your Email</label>
            <input type="email" class="form-control" id="channel-email" name="channel-email" placeholder="Enter your email">
        </div>
        <div class="form-check">
            <input class="form-check-input" name="informed_channel[whatsapp]" type="checkbox"
                   <?php if (isset($user_informed['informed_channel'])) echo in_array('whatsapp', $user_informed['informed_channel']) ? ' checked="checked" ' : '' ?>
                   id="cat-whatsapp" value="whatsapp">
            <label class="form-check-label" for="cat-whatsapp">Whatsapp</label>
        </div>
        <div class="mb-3 channel-whatsapp">
            <label for="channel-whatsapp" class="form-label">Your Whatsapp</label>
            <input type="text" class="form-control" id="channel-whatsapp" name="channel-whatsapp" placeholder="Enter your Whatsapp">
        </div>
        <div class="form-check">
            <input class="form-check-input" name="informed_channel[sms]" type="checkbox"
                   <?php if (isset($user_informed['informed_channel'])) echo in_array('sms', $user_informed['informed_channel']) ? ' checked="checked" ' : '' ?>
                   id="cat-sms" value="sms">
            <label class="form-check-label" for="cat-sms">SMS</label>
        </div>
        <div class="mb-3 channel-sms">
            <label for="channel-sms" class="form-label">Your SMS</label>
            <input type="text" class="form-control" id="channel-sms" name="channel-sms" placeholder="Enter your sms">
        </div>
    </div>

    <div class="mb-3">
        <?php wp_nonce_field('setting-account-action') ?>
        <button type="submit" class="btn btn-primary"><?php _e('Save Settings', 'cabling'); ?></button>
    </div>
</form>
