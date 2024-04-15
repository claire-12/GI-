<?php

/**
 * Single Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

?>

<div id="bbpress-forums" class="bbpress-wrapper has-sidebar">
    <div class="row">
        <div class="bbpress-forums col-12">

            <?php bbp_breadcrumb(); ?>

            <?php bbp_forum_subscription_link(); ?>

            <?php do_action('bbp_template_before_single_forum'); ?>

            <?php if (post_password_required()) : ?>

                <?php bbp_get_template_part('form', 'protected'); ?>

            <?php else : ?>

                <?php bbp_single_forum_description(); ?>

                <?php if (bbp_has_forums()) : ?>

                    <?php bbp_get_template_part('loop', 'forums'); ?>

                <?php endif; ?>

                <?php if (!bbp_is_forum_category() && bbp_has_topics()) : ?>

                    <?php bbp_get_template_part('pagination', 'topics'); ?>

                    <?php bbp_get_template_part('loop', 'topics'); ?>

                    <?php bbp_get_template_part('pagination', 'topics'); ?>

                    <?php bbp_get_template_part('form', 'topic'); ?>

                <?php elseif (!bbp_is_forum_category()) : ?>

                    <?php bbp_get_template_part('feedback', 'no-topics'); ?>

                    <?php bbp_get_template_part('form', 'topic'); ?>

                <?php endif; ?>

            <?php endif; ?>

            <?php do_action('bbp_template_after_single_forum'); ?>
        </div>
        <!--<div class="forum-sidebar col-lg-4 col-md-12">
            <?php /*if (is_active_sidebar('forum-sidebar')) dynamic_sidebar('forum-sidebar') */?>
        </div>-->

    </div>
</div>
