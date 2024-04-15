<?php
$user_id = get_current_user_id();
?>
<div class="modal fade" id="create_customer" tabindex="-1" aria-labelledby="create_customerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4><?php _e('New User', 'cabling') ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only"><?php _e('Close', 'cabling') ?></span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" name="create-user-form" id="information-form" class="needs-validation">
                    <div class="woo-notice" role="alert" style="display: none">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                               name="first_name" id="first_name" required>
                        <label for="first_name"><?php _e('First Name', 'cabling') ?><span
                                    class="required">*</span></label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                               name="last_name" id="last_name" required>
                        <label for="last_name"><?php _e('Last Name', 'cabling') ?><span
                                    class="required">*</span></label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"
                               name="job-title" id="job-title" required>
                        <label for="job-title"><?php _e('Job Title', 'cabling') ?><span
                                    class="required">*</span></label>
                    </div>
                    <div class="form-group">
                        <label for="user_email"><?php _e('Email', 'cabling') ?><span class="required">*</span></label>
                        <input type="email" class="form-control"
                               name="user_email" id="user_email"
                               pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="user_telephone"><?php _e('Telephone', 'cabling') ?></label>
                        <input type="tel" class="form-control" id="user_telephone"
                               placeholder="<?php _e('Telephone', 'cabling') ?>" required>
                        <span id="user_telephone-validate" class="hidden input-error"></span>
                        <input type="hidden" class="phone_number" name="user_telephone">
                        <input type="hidden" class="phone_code" name="user_telephone_code">
                    </div>
                    <div class="form-group">
                        <label for="mobile-phone"><?php _e('Mobile Number', 'cabling') ?></label>
                        <input type="tel" class="form-control" id="mobile-phone"
                               placeholder="<?php _e('Mobile Number', 'cabling') ?>" required>
                        <span id="mobile-phone-validate" class="hidden input-error"></span>
                        <input type="hidden" class="phone_number" name="billing_phone">
                        <input type="hidden" class="phone_code" name="billing_phone_code">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="department" name="user-department" required>
                        <label for="department"><?php _e('Department', 'cabling') ?><span
                                    class="required">*</span></label>
                    </div>
                    <div class="text-center">

                        <?php wp_nonce_field('cabling-customer', 'customer-nounce'); ?>
                        <button class="btn btn-dark btn-submit" type="submit"><?php _e('Submit', 'cabling') ?></button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
