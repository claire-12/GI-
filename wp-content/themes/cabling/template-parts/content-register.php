<?php
$email_register = $_POST['register_email'] ?? '';

$active_step = 0;
if (isset($_GET['code'])) {
    $data = json_decode(base64_decode($_GET['code']));
    $email = urldecode($data->email);
    $verify = true;
    $active_step = 1;
}

if (isset($_GET['create-complete']) && 'true' == $_GET['create-complete'])
    $active_step = 2;

$term_link = "<a style='color: inherit' target='new' href='" . home_url('/terms-and-conditions/') . "'>" . __('Terms and Conditions', 'cabling') . "</a>";
$policy_link = "<a style='color: inherit' target='new' href='" . home_url('/privacy/') . "'>" . __('Privacy and Cookie Policy', 'cabling') . "</a>";
?>
<div id="registerStep">
    <h1 class="text-center my-5"><?php _e('Register for an account', 'cabling') ?></h1>
    <div class="confirm-notice"><?php wc_print_notices(); ?></div>
    <div style="text-align:center;margin-top:40px; display: none !important;" class="step__bar hidden">
        <div class="step active"><p><span>1</span><?php _e('Verification', 'cabling') ?></p><span
                    class="step__line"></span></div>
        <div class="step"><p><span>2</span><?php _e('Information', 'cabling') ?></p><span class="step__line"></span>
        </div>
        <div class="step"><p><span>3</span><?php _e('Complete', 'cabling') ?></p><span class="step__line"></span></div>
    </div>
    <div class="tab">
        <form method="POST" name="register-form" id="register-form">
            <div class="woocommerce-error woo-notice" role="alert"
                 style="display: none;"><?php _e('Please verify the Captcha.', 'cabling') ?></div>
            <div class="form-group">
                <label for="register_email"><?php _e('Email', 'cabling') ?></label>
                <input type="email" class="form-control" name="register_email"
                       pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                       id="register_email" required>
            </div>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo get_field('gcapcha_sitekey_v2', 'option'); ?>"></div>
            </div>

            <div class="submit-btn">
                <?php wp_nonce_field('cabling-register', 'register-nounce'); ?>
                <button type="submit" class="submit-register"><?php _e('Next', 'cabling') ?></button>
            </div>
        </form>
    </div>
    <div class="tab">
        <?php if (!empty($verify)): ?>
            <p class="hidden"><strong><?php _e('Email:', 'cabling') ?></strong> <?php echo $email; ?></p>
            <form method="POST" name="infomation-form" id="infomation-form" class="needs-validation">
                <input type="hidden" value="<?php echo $email; ?>" name="user_email">

                <div class="form-group">
                    <input type="text" class="form-control" name="first-name" id="first-name"
                           value="<?php echo $_REQUEST['first-name'] ?? '' ?>" required>
                    <label for="first-name"><?php _e('First Name', 'cabling') ?><span class="required">*</span></label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="last-name" id="last-name"
                           value="<?php echo $_REQUEST['last-name'] ?? '' ?>" required>
                    <label for="last-name"><?php _e('Last Name', 'cabling') ?><span class="required">*</span></label>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" id="password" required>
                    <label for="password"><?php _e('Password', 'cabling') ?><span class="required">*</span></label>
                </div>
                <span class="password-help">
                        <?php _e('Your password must have at least: 8 characters long with characters, numbers and symbols', 'cabling') ?>
                    </span>
                <!-- JM 20230920 changed position of Is client? and client number -->
                <div class="form-group">
                    <input type="text" class="form-control" name="company-name-responsible" id="company-responsive-name"
                           value="<?php echo $_REQUEST['company-name-responsible'] ?? '' ?>"
                           required>
                    <label for="company-responsive-name"><?php _e('Company contact Full Name', 'cabling') ?><span
                                class="required">*</span></label>
                </div>
                <div class="form-group form-phone">
                    <input type="tel" class="form-control" id="mobile-phone" required>
                    <span id="mobile-phone-validate" class="hidden input-error"></span>
                    <input type="hidden" class="phone_number" name="billing_phone">
                    <input type="hidden" class="phone_code" name="billing_phone_code">
                    <label for="mobile-phone"><?php _e('Mobile Number', 'cabling') ?><span
                                class="required">*</span></label>
                </div>
                <div class="form-group1">
                        <label for="user_title"><?php esc_html_e('Title', 'woocommerce'); ?>&nbsp;<span
                                    class="required">*</span></label>
                        <?php woocommerce_form_field(
                            'user_title',
                            array(
                                'type' => 'select',
                                'class' => array('mw-100'),
                                'options' => array('Ms.', 'Mr.'),
                                'input_class' => array('form-select')
                            ),
                        )
                        ?>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="job-title" id="job-title"
                           value="<?php echo $_REQUEST['job-title'] ?? '' ?>" required>
                    <label for="job-title"><?php _e('Job Title', 'cabling') ?><span class="required">*</span></label>
                </div>
                <div class="form-group1" style="margin-bottom: 15px">
                    <?php company_name_field() ?>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="company-name"
                           value="<?php echo $_REQUEST['company-name'] ?? '' ?>" id="company-name" required>
                    <label for="company-name"><?php _e('Company name', 'cabling') ?><span
                                class="required">*</span></label>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="billing_vat"
                           value="<?php echo $_REQUEST['billing_vat'] ?? '' ?>" id="company-vat" required>
                    <label for="company-vat"><?php _e('Income tax number', 'cabling') ?><span class="required">*</span></label>
                </div>
                <span class="password-help">
                        <?php _e('US Federal Tax or VAT number', 'cabling') ?>
                    </span>
                <div class="mb-5 hidden">
                    <div style="width:50%;float:left;">
                        <label for="existing-customer" style="position: inherit">
                            <input type="checkbox" id="existing-customer" name="existing-customer" value="yes">
                            <?php _e('Existing Customer', 'cabling') ?>
                        </label>
                    </div>
                    <div class="form-group client-number-field" style="display: none;width:50%;float:left;">
                        <input type="text" class="form-control" name="client-number" id="client-number">
                        <label for="client-number">
                            <?php _e('Client Number', 'cabling') ?>
                        </label>
                    </div>
                </div>
                <div style="clear:both;"></div>
                <div class="form-group">
                    <input type="text" class="form-control" name="company-address"
                           value="<?php echo $_REQUEST['company-address'] ?? '' ?>" id="company-address" required>
                    <label for="company-address"><?php _e('Company address', 'cabling') ?><span
                                class="required">*</span></label>
                </div>
                <div class="form-group">
                    <?php woocommerce_form_field('billing_country', array(
                        'type' => 'country',
                        'default' => 'US',
                        'class' => array('mw-100'),
                        'placeholder' => __('Country*', 'cabling'),
                        'input_class' => array('form-select')
                    ));
                    ?>
                    <label class="screen-reader-text" for="country"><?php _e('Country', 'cabling') ?><span
                                class="required">*</span></label>
                </div>
                <div class="form-group text-center agree-term-condition mb-3">
                    <p for="agree-term-condition">
                        <!--<input type="checkbox" name="agree-term-condition" id="agree-term-condition" required>-->
                        <!-- JM 20231002 changed target new -->
                        <?php printf(__('By clicking "Register Now" you accept the %s and %s', 'cabling'), $term_link, $policy_link) ?>
                    </p>
                </div>
                <div class="text-center mb-5">
                    <div id="login-recaptcha" class="g-recaptcha d-flex justify-content-center mt-3"
                         data-sitekey="<?php echo get_field('gcapcha_sitekey_v2', 'option') ?>"></div>
                    <input type="hidden" name="user-phone-code">
                    <?php wp_nonce_field('cabling-verify', 'verify-nounce'); ?>
                    <button class="block-button btn-submit  mt-3" type="submit"
                            class="submit-register"><?php _e('Register Now', 'cabling') ?></button>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <div class="tab">
        <div class="text-center">
            <div class="confirm-notice"><?php wc_print_notices(); ?></div>
            <p><?php _e('Your account has been created. You can use standard features in the webshop. Meanwhile, you will be contacted by the Datwyler to extend the experience in the webshop, in order to become a Level 2 user with full access to the webshop.', 'cabling') ?></p>
        </div>
    </div>

</div>

<script>

    let currentTab = <?php echo $active_step;?>; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form...
        const x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        //... and run a function that will display the correct step indicator:
        fixStepIndicator(n)
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        let i, x = document.getElementsByClassName("step");
        //console.log(x);
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class on the current step:
        x[n].className += " active";
    }
</script>
<style>
    .password-help {
        position: relative;
        top: -15px;
    }
</style>
