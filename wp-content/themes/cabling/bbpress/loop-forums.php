<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

$number = (int)get_option( '_bbp_forums_per_page', 50 );
$posts = wp_count_posts(bbp_get_forum_post_type())->publish;
$showLoadMore = $posts > $number ? '' : 'style="display:none"';

do_action('bbp_template_before_forums_loop'); ?>
    <div id="forums-result" class="row">
        <?php while (bbp_forums()) : bbp_the_forum(); ?>

            <?php bbp_get_template_part('loop', 'single-forum'); ?>

        <?php endwhile; ?>
    </div>
    <!--<div class="news-innovation text-center" <?php /*echo $showLoadMore */?>>
        <button type="button" id="load-more-forums" class="block-button">
            <span><?php /*echo __('... LOAD MORE', 'cabling') */?></span>
        </button>
    </div>-->
<?php do_action('bbp_template_after_forums_loop');
