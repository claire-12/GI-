<div class="login-wrapper">
    <form class="woocommerce-form woocommerce-form-login login" method="post" name="cabling_login_form">
        <h2><?php esc_html_e('Existing Users', 'woocommerce'); ?></h2>
        <p class="sub-heading login-username"
           style="    font-size: larger;"><?php esc_html_e('Sign In', 'woocommerce'); ?></p>

        <?php do_action('woocommerce_login_form_start'); ?>

        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label class="screen-reader-text"
                   for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span
                        class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="log" id="username"
                   autocomplete="username"
                   value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                   placeholder="<?php esc_html_e('Email Address*', 'woocommerce'); ?>"/><?php // @codingStandardsIgnoreLine ?>
        </p>
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label class="screen-reader-text" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>
                &nbsp;<span class="required">*</span></label>
            <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="pwd"
                   id="password" autocomplete="current-password"
                   placeholder="<?php esc_html_e('Password*', 'woocommerce'); ?>"/>
            <p class="woocommerce-LostPassword lost_password flex-row">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgotten Password', 'woocommerce'); ?></a>
            </p>
        </p>

        <?php do_action('woocommerce_login_form'); ?>

        <p class="form-row d-flex justify-content-end">
            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <button type="submit" class="block-button" name="login"
                    value="<?php esc_attr_e('Sign In', 'woocommerce'); ?>"><?php esc_html_e('Sign In', 'woocommerce'); ?></button>
        </p>

        <?php do_action('woocommerce_login_form_end'); ?>

    </form>
    <hr>
    <div class="register-block">
        <h2><?php esc_html_e('Register for an account', 'woocommerce'); ?></h2>
        <div class="d-flex justify-content-between align-items-center">
            <p class="sub-heading" style="font-size: larger;"><?php esc_html_e('Register for an account to save and retrieve quotes, plus many more benefits', 'woocommerce'); ?></p>
            <a class="block-button mt-0" href="<?php echo wc_get_account_endpoint_url('') ?>"><?php esc_html_e('Register', 'woocommerce'); ?></a>
        </div>
    </div>
    <hr>
    <div class="register-block">
        <h2>
            <?php esc_html_e('or continue as a guest', 'woocommerce'); ?>
            <input type="checkbox" checked="checked">
        </h2>
    </div>
</div>