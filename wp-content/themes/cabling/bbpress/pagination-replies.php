<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

$bbp = bbpress();
$total_int = $bbp->reply_query->found_posts;

do_action('bbp_template_before_pagination_loop'); ?>

    <div class="bbp-pagination">
        <h3 class="pre-heading"><?php printf(__('Comments (%d)','cabling'), $total_int); ?></h3>
    </div>

<?php do_action('bbp_template_after_pagination_loop');
