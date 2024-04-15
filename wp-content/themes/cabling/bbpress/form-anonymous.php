<?php

/**
 * Anonymous User
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (bbp_current_user_can_access_anonymous_user_form()) : ?>

    <?php do_action('bbp_theme_before_anonymous_form'); ?>

    <fieldset class="bbp-form-1">

        <?php do_action('bbp_theme_anonymous_form_extras_top'); ?>
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <input type="text" id="bbp_anonymous_author" class="form-control" value="<?php bbp_author_display_name(); ?>" size="40" maxlength="100"
                           name="bbp_anonymous_name" autocomplete="off"/>
                    <label for="bbp_anonymous_author"><?php esc_html_e('Name', 'bbpress'); ?><span class="required">*</span></label>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <input type="text" id="bbp_anonymous_email" class="form-control"
                           value="<?php bbp_author_email(); ?>" size="40" maxlength="100" name="bbp_anonymous_email"/>
                    <label for="bbp_anonymous_email"><?php esc_html_e('Email Address', 'bbpress'); ?><span class="required">*</span></label>
                </div>
            </div>
        </div>

        <!--<p>
			<label for="bbp_anonymous_website screen-reader-text"><?php /*esc_html_e( 'Website:', 'bbpress' ); */ ?></label><br />
			<input type="text" id="bbp_anonymous_website" value="<?php /*bbp_author_url(); */ ?>" size="40" maxlength="200" name="bbp_anonymous_website" />
		</p>-->

        <?php do_action('bbp_theme_anonymous_form_extras_bottom'); ?>

    </fieldset>

    <?php do_action('bbp_theme_after_anonymous_form'); ?>

<?php endif;
