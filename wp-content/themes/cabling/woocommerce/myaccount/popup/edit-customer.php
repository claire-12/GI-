<div class="modal-header">
    <h4>
        <?php _e('Edit User', 'cabling') ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only"><?php _e('Close', 'cabling') ?></span>
    </button>
</div>
<div class="modal-body">
    <form method="POST" name="update-customer-lv1" id="update-customer-lv1" class="needs-validation">
        <div class="woo-notice woocommerce-error" role="alert" style="display: none">
        </div>
        <div class="form-group <?php echo empty($customer_email) ? '' : 'has-focus' ?>">
            <input type="email" class="form-control" name="user_email"
                   value="<?php echo $customer_email ?>"
                   id="user_email" disabled>
            <label for="user_email"><?php _e('Email', 'cabling') ?><span class="required">*</span></label>
        </div>
        <div class="form-group <?php echo empty($data['billing_first_name']) ? '' : 'has-focus' ?>">
            <input type="text" class="form-control" name="billing_first_name"
                   value="<?php echo $data['billing_first_name'] ?>"
                   id="billing_first_name" required>
            <label for="billing_first_name"><?php _e('First Name', 'cabling') ?><span class="required">*</span></label>
        </div>
        <div class="form-group <?php echo empty($data['billing_last_name']) ? '' : 'has-focus' ?>">
            <input type="text" class="form-control" name="billing_last_name"
                   value="<?php echo $data['billing_last_name'] ?>"
                   id="billing_last_name" required>
            <label for="billing_last_name"><?php _e('Last Name', 'cabling') ?><span class="required">*</span></label>
        </div>
        <div class="form-group <?php echo empty($data['job_title']) ? '' : 'has-focus' ?>">
            <input type="text" class="form-control" name="job_title"
                   value="<?php echo $data['job_title'] ?>"
                   id="job_title" required>
            <label for="job_title"><?php _e('Job Title', 'cabling') ?><span class="required">*</span></label>
        </div>
        <div class="form-group">
            <label for="user_telephone"><?php _e('Telephone', 'cabling') ?></label>
            <input type="tel" class="form-control" id="user_telephone_edit"
                   placeholder="<?php _e('Telephone', 'cabling') ?>"
                   value="<?php echo get_user_telephone_number($customer->ID) ?>" required>
            <span id="user_telephone-validate" class="hidden input-error"></span>
            <input type="hidden" class="phone_number" name="user_telephone"
                   value="<?php echo $data['user_telephone'] ?>">
            <input type="hidden" class="phone_code" name="user_telephone_code"
                   value="<?php echo $data['user_telephone_code'] ?>">
        </div>
        <div class="form-group">
            <label for="mobile-phone"><?php _e('Mobile Number', 'cabling') ?></label>
            <input type="tel" class="form-control" id="mobile_phone_edit"
                   placeholder="<?php _e('Mobile Number', 'cabling') ?>"
                   value="<?php echo get_user_phone_number($customer->ID) ?>" required>
            <span id="mobile-phone-validate" class="hidden input-error"></span>
            <input type="hidden" class="phone_number" name="billing_phone" value="<?php echo $data['billing_phone'] ?>">
            <input type="hidden" class="phone_code" name="billing_phone_code"
                   value="<?php echo $data['billing_phone_code'] ?>">
        </div>
        <div class="form-group <?php echo empty($data['user_department']) ? '' : 'has-focus' ?>">
            <input type="text" class="form-control" id="user_department"
                   name="user_department" value="<?php echo $data['user_department'] ?>" required>
            <label for="user_department"><?php _e('Department', 'cabling') ?><span class="required">*</span></label>
        </div>
        <div class="text-center">
            <input type="hidden" name="customer_id" value="<?php echo $customer->ID ?>">
            <button class="btn btn-dark btn-update-customer btn-submit"
                    type="submit"><?php _e('Update', 'cabling') ?></button>
        </div>
    </form>
</div>
