<?php

/**
 * Archive Forum Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

$args = get_filter_arg_forums();
?>

<div id="bbpress-forums" class="bbpress-wrapper">
    <?php bbp_get_template_part('form', 'search'); ?>
    <div class="bbpress-forums alignfull">
        <div class="container">
            <?php //bbp_breadcrumb(); ?>

            <?php bbp_forum_subscription_link(); ?>

            <?php do_action('bbp_template_before_forums_index'); ?>

            <?php bbp_get_template_part('loop', 'discover'); ?>
            <?php if (bbp_has_forums($args)) : ?>

                <?php bbp_get_template_part('loop', 'forums'); ?>

            <?php else : ?>

                <?php bbp_get_template_part('feedback', 'no-forums'); ?>

            <?php endif; ?>

            <?php do_action('bbp_template_after_forums_index'); ?>
        </div>
    </div>
</div>
