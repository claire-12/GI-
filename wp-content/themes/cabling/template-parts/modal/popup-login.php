<!-- The Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Sign In', 'cabling') ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <?php echo wp_login_form(array(
                    'label_username' => __('Email', 'cabling'),
                    'label_log_in' => __('Sign In', 'cabling'),
                    'form_id' => 'cabling_login_form',
                )); ?>

                <div class="register">
                    <p class="forgot-link"><?php printf(__('<a href="%s">Forgot Password?</a>', 'cabling'), home_url('/my-account/lost-password/')) ?></p>
                    <p class="register-link"><?php printf(__('Not Member? <a href="%s">Register Now</a>', 'cabling'), home_url('/register/')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
