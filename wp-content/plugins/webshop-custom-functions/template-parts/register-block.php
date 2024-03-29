<div class="login-wrapper">
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="register-block ">
                <h3>
                    <?php esc_html_e('First time quote?', 'woocommerce'); ?>
                </h3>
                <button type="button" class="block-button continue-as-a-guest">
                    <?php esc_html_e('CONTINUE AS A GUEST', 'woocommerce'); ?>
                </button>
            </div>
            <hr>
            <div class="register-block">
                <h3><?php esc_html_e('Register for My Account', 'woocommerce'); ?></h3>

                <form method="POST" name="register-form" id="register-form">
                    <p class="sub-heading mb-0 pe-3"
                       style="font-size: larger;"><?php esc_html_e('Register today to make future quote requests even easier – and easier account management after you’ve made a purchase.', 'woocommerce'); ?></p>

                    <div class="form-group">
                        <label class="screen-reader-text"
                               for="register_email"><?php _e('Email', 'cabling') ?></label>
                        <input type="email" class="form-control"
                               placeholder="<?php _e('Email Address*', 'cabling') ?>"
                               name="register_email"
                               value="<?php echo $_POST['register_email'] ?? '' ?>"
                               pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                               id="register_email" required>
                    </div>
                    <div class="submit-btn">
                        <?php wp_nonce_field('cabling-register', 'register-nounce'); ?>
                        <button class="block-button" type="submit"
                                class="submit-register"><?php _e('Register', 'cabling') ?></button>
                    </div>

                </form>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <form class="woocommerce-form woocommerce-form-login" method="post" name="cabling_login_form">
                <h3><?php esc_html_e('Existing My Account Users', 'woocommerce'); ?></h3>
                <p class="sub-heading login-username"
                   style="    font-size: larger;"><?php esc_html_e('Sign In', 'woocommerce'); ?></p>

                <?php do_action('woocommerce_login_form_start'); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label class="screen-reader-text"
                           for="username"><?php esc_html_e('Username or email address', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="log"
                           id="username"
                           autocomplete="username"
                           required
                           value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>"
                           placeholder="<?php esc_html_e('Email Address*', 'woocommerce'); ?>"/><?php // @codingStandardsIgnoreLine ?>
                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label class="screen-reader-text" for="password"><?php esc_html_e('Password', 'woocommerce'); ?>
                        &nbsp;<span class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="pwd"
                           id="password" autocomplete="current-password"
                           required
                           placeholder="<?php esc_html_e('Password*', 'woocommerce'); ?>"/>
                <p class="woocommerce-LostPassword lost_password flex-row">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Forgotten Password', 'woocommerce'); ?></a>
                </p>
                </p>

                <p class="form-row d-flex justify-content-end">
                    <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                    <input type="hidden" name="redirect" value="<?php echo get_the_permalink() ?>">
                    <input type="hidden" name="is_reload" value="true">
                    <button type="submit" class="block-button" name="login"
                            value="<?php esc_attr_e('Sign In', 'woocommerce'); ?>"><?php esc_html_e('Sign In', 'woocommerce'); ?></button>
                </p>

                <?php do_action('woocommerce_login_form_end'); ?>

            </form>
        </div>
        <div class="col-12">
            <form action="" id="quote_form-recapcha">
                <div class="form-group">
                    <div class="g-recaptcha d-flex justify-content-center mt-2" id="quote-recaptcha"
                         data-sitekey="<?php echo get_field('gcapcha_sitekey_v2', 'option'); ?>"></div>
                </div>
            </form>
        </div>
    </div>
</div>
