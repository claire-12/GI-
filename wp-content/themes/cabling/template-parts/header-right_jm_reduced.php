<div class="header-right">
    <div class="top-navigation_jm d-flex text-uppercase mb-3">
        <?php if (is_user_logged_in()): ?>
		<!--
			<div class="my-account-item mx-3">
				<a class="nav-link" href="#" role="button">
                    <span class="icon"><img src="<?php echo get_template_directory_uri() . '/images/myquotes.png' ?>"
                                            width="25" height="23" alt=""></span>
                    <?php _e('My Quotes', 'cabling') ?>
                </a>
			</div>
-->
            <div class="my-account-item mx-3">
                <a class="nav-link dropdown-toggle" href="#" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="icon"><img src="<?php echo get_template_directory_uri() . '/images/signin.png' ?>"
                                             height="23" alt="" style="height:23px;"></span>
                    <?php _e('My account', 'cabling') ?>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item"
                           href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')) ?>">
                            <?php _e('Account Settings', 'cabling') ?>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?php echo esc_url(wc_logout_url()) ?>">
                            <?php _e('Logout', 'cabling') ?>
                        </a>
                    </li>
                </ul>
            </div>
        <?php else: ?>
            <div class="sign-in-item me-4">
                <span class="icon"><img src="<?php echo get_template_directory_uri() . '/images/signin.png' ?>"
                                        width="19" height="31" alt=""></span>
                <span><a style="color: inherit" href="<?php echo wc_get_account_endpoint_url('') ?>"><?php _e('My account | Sign in', 'cabling') ?><?php //_e('Sign in', 'cabling') ?></a></span>
            </div>
        <?php endif; ?>
        <div class="search-item">
            <span class="icon me-2"><img src="<?php echo get_template_directory_uri() . '/images/search.png' ?>"
                                          alt="" style="height:23px;"></span>
            <span><?php _e('Search', 'cabling') ?></span>
        </div>
    </div>
    <div class="primary-navigation d-flex" style="margin-right:10px;">
        <nav id="site-navigation" class="main-navigation_jm">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'menu-1',
                'menu_id' => 'primary-menu',
            ));
            ?>
        </nav><!-- #site-navigation -->
        <div class="menu-toggle d-flex align-items-center ms-5 top-navigation_jm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <img src="<?php echo get_template_directory_uri() . '/images/menu.png' ?>" width="17"  alt="">
            <span class="ms-2"><?php _e('MENU', 'cabling') ?></span>
        </div>
    </div>
</div>
