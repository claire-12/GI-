<?php
$thisLink = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$initial_message = "Hi,<br><br>
I was checking out the Datwyler Sealing site and wanted to share this: <a href='" . $thisLink . "'>" . $thisLink . "</a>.<br><br>Let me know what you think!<br><br>";
$initial_content = "<a href='" . $thisLink . "'>" . $thisLink . "</a>";
$editor_id = 'message';
$settings = array(
    'textarea_name' => 'message_content',
    'media_buttons' => false,
    'tinymce' => true,
);
?>

<div class="show-email-share show-quote-button show-product-quote" data-action="<?php echo is_product() ? get_the_ID() : 0 ?>"></div>
<div class="dropdown show-email-share">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-list"></i>
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item email-share" href="#">
                <i class="fa-solid fa-paper-plane me-1"></i>
                <?php echo __('Share this page', 'cabling'); ?>
            </a>
        </li>
        <li>
            <a class="dropdown-item show-product-quote" href="#" data-action="<?php echo is_product() ? get_the_ID() : 0 ?>">
                <i class="fa-thin fa-message-quote me-1"></i>
                <?php echo __('Request a Quote', 'cabling'); ?>
            </a>
        </li>
        <li>
            <a class="dropdown-item keep-informed-modal" href="#">
                <i class="fa-light fa-rectangle-list me-1"></i>
                <?php echo __('Keep me informed', 'cabling'); ?>
            </a>
        </li>
    </ul>
</div>

<!-- The Email Share Modal -->
<div class="modal fade" id="emailShareModal" tabindex="-1" aria-labelledby="emailShareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><?php _e('Share this page', 'cabling') ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div id="share-email-wrap">
                    <form id="share-email-form" method="post">
                        <?php wp_nonce_field('share_email_form_contact', '_share_email_nonce') ?>
                        <p class="form-field">
                            <label for="to">To:</label><br>
                            <input type="text" name="to" id="to" placeholder="email@domain.com,email2@domain.com..."
                                   required>
                        </p>
                        <p class="form-field">
                            <label for="subject">Subject:</label><br>
                            <input type="text" name="subject" id="subject" required>
                        </p>
                        <p class="form-field">
                            <label for="message">Message:</label><br>
                            <?php //wp_editor($initial_content, $editor_id, $settings); ?>
                            <?php wp_editor($initial_message, $editor_id, $settings); ?>
                        </p>
                        <p class="form-field">
                        <div class="g-recaptcha"
                             data-sitekey="<?php echo get_field('gcapcha_sitekey_v2', 'option'); ?>"></div>
                        </p>
                        <p class="text-center">
                            <button type="button" class="button button-cancel hidden" data-bs-dismiss="modal">Cancel
                            </button>
                            <input type="submit" class="button button-primary" value="Share">
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
