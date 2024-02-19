<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<div class="col-12 col-md-6 col-lg-3">
    <div class="bbp-forum-box">
        <a class="bbp-forum-title" href="<?php bbp_topic_permalink(); ?>">
            <h4><?php bbp_forum_title(); ?></h4>
        </a>
        <div class="bbp-forum-count">
            <i class="fa-light fa-messages me-2"></i>
            <?php printf(__('%s Conversations', 'cabling'), bbp_forum_topic_count()); ?>
        </div>
    </div>
</div>
