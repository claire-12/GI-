<!-- Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"
                    id="resetPasswordModalLabel"><?php echo __('Reset Password', 'cabling') ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="reset-account-password" class="reset-account-password">
                    <div class="form-group">
                        <input type="password" id="old-password" name="old-password" required>
                        <label for="old-password"><?php echo __('Old Password:', 'cabling') ?></label>
                    </div>
                    <div class="form-group">
                        <input type="password" id="new-password" name="new-password" required>
                        <label for="new-password"><?php echo __('New Password:', 'cabling') ?></label>
                        <span class="password-help">
                            <?php _e('Required to update password - mix of characters, symbols and numbers, min 8 digits','cabling') ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirm-password" name="confirm-password" value="" required>
                        <label for="confirm-password"><?php echo __('Confirm Password:', 'cabling') ?></label>
                    </div>
                    <div class="modal-footer text-center">
                        <button type="submit" class="block-button"><?php echo __('Update', 'cabling') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
