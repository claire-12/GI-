<?php

/**
 * Single Topic Lead Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

do_action('bbp_template_before_lead_topic'); ?>

    <ul id="bbp-topic-<?php bbp_topic_id(); ?>-lead" class="bbp-lead-topic1 mb-4">
        <li class="bbp-body">

            <?php if(current_user_can('administrator')): ?>
            <div class="bbp-topic-header">

                <div class="bbp-meta">

                    <?php do_action('bbp_theme_before_topic_admin_links'); ?>

                    <?php bbp_topic_admin_links(); ?>

                    <?php do_action('bbp_theme_after_topic_admin_links'); ?>

                </div>

            </div><!-- .bbp-topic-header -->
            <?php endif; ?>

            <div class="single-topic-content">
                <h2 class="heading"><?php bbp_topic_title(); ?></h2>
                <?php do_action('bbp_theme_before_topic_content'); ?>

                <?php bbp_topic_content(); ?>

                <?php do_action('bbp_theme_after_topic_content'); ?>

            </div><!-- .bbp-topic-content -->

        </li><!-- .bbp-body -->

    </ul><!-- #bbp-topic-<?php bbp_topic_id(); ?>-lead -->

<?php do_action('bbp_template_after_lead_topic');
