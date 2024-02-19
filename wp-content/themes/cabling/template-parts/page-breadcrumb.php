<?php if (!empty(get_field('hide_breadcrumb'))): ?>
    <div class="cabling-breadcrumb woocommerce-breadcrumb my-3">
        <?php cabling_woocommerce_breadcrumb() ?>
    </div>
<?php endif ?>
<?php if (is_bbpress()): ?>
    <div class="cabling-breadcrumb woocommerce-breadcrumb">
        <span class="bc-parent"><?php _e('Home / ', 'cabling') ?></span>
        <?php if (get_query_var('bbp_search')): $forums_link = bbp_get_root_slug(); ?>
            <span property="itemListElement" typeof="ListItem">
                <a property="item" typeof="WebPage"
                   title="Go to Areas."
                   href="<?php echo home_url($forums_link) ?>"
                   class="archive post-forum-archive">
                    <span property="name">Areas</span>
                </a>
                <meta property="position" content="1">
            </span>
            |
            <span property="itemListElement" typeof="ListItem">
                <span property="name" class="post post-forum current-item">Search</span>
            </span>
        <?php endif ?>
        <?php bcn_display(); ?>
    </div>
    <?php cabling_show_back_btn(); ?>
<?php endif ?>