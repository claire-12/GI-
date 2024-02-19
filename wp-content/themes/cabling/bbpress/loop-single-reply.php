<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<div <?php bbp_reply_class(); ?>>
	<div class="bbp-reply-author">

		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

		<?php bbp_reply_author_link( ); ?>

		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>

	</div><!-- .bbp-reply-author -->

	<div class="bbp-reply-content">
        <div class="bbp-meta">
            <span class="bbp-reply-author-name"><?php bbp_reply_author_display_name(bbp_get_reply_id()); ?></span>
            <span class="bbp-reply-post-date"><?php bbp_reply_post_date(bbp_get_reply_id(), true); ?></span>

            <?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

            <?php bbp_reply_admin_links(); ?>

            <?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>

        </div><!-- .bbp-meta -->

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->
</div><!-- .reply -->
