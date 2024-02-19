<div class="header-right">
    <div class="top-navigation d-flex text-uppercase mb-3">
        <?php if (is_user_logged_in()): ?>
            <div class="my-account-item mx-3">
                <a class="nav-link dropdown-toggle" href="#" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="icon"><img src="<?php echo get_template_directory_uri() . '/images/signin.png' ?>"
                                            width="19" height="31" alt=""></span>
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
            <div class="sign-in-item">
                <span class="icon"><img src="<?php echo get_template_directory_uri() . '/images/signin.png' ?>"
                                        width="19" height="31" alt=""></span>
                <span><a style="color: inherit" href="<?php echo wc_get_account_endpoint_url('') ?>"><?php _e('My account | Sign in', 'cabling') ?></a></span>
				<?php //_e('My account | Sign in', 'cabling') ?>
            </div>
        <?php endif; ?>
        <div class="search-item">
            <span class="icon me-2"><img src="<?php echo get_template_directory_uri() . '/images/search.png' ?>"
                                         width="19" height="22" alt=""></span>
            <span><?php _e('Search', 'cabling') ?></span>
        </div>
    </div>
    <div class="primary-navigation d-flex">
        <nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'menu-1',
                'menu_id' => 'primary-menu',
            ));
            ?>
        </nav><!-- #site-navigation -->
        <div class="menu-toggle d-flex align-items-center ms-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <img src="<?php echo get_template_directory_uri() . '/images/menu.png' ?>" width="26" height="17" alt="">
            <span class="ms-2"><?php _e('MENU', 'cabling') ?></span>
        </div>
    </div>
</div>
