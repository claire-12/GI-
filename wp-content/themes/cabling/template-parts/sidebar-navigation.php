<?php
$product_categories = get_field('_product_category_menu', 'options');
?>
<div id="offcanvasNavbar" class="sidebar-navigation offcanvas offcanvas-end" tabindex="-1" aria-labelledby="offcanvasNavbarLabel">
    <div class="nav-inner">
        <div class="header-nav">
            <?php echo get_template_part('/template-parts/header', 'right') ?>
        </div>
        <div class="product-services-nav woocommerce position-relative">
            <button type="button" class="btn-close position-absolute" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-xmark"></i>
            </button>
            <div class="back-main-nav" style="display: none">
                <i class="fa-light fa-arrow-left-long"></i>
                <?php echo __('Back to Main Menu', 'cabling') ?>
            </div>
            <h4 class="heading d-flex align-items-center toggle-product-sidebar">
                <?php echo wp_get_attachment_image(1034775, 'full', false, ['class' => 'me-2']) ?>
                <?php echo __('Products & Services', 'cabling') ?>
            </h4>
            <?php if ($product_categories): ?>
                <div class="product-cat-nav sidebar-nav" style="display: none">
                    <ul>
                        <?php foreach ($product_categories as $category): ?>
                            <li>
                                <?php if (!empty($category['icon'])): ?>
                                    <span><img src="<?php echo esc_url($category['icon']) ?? '' ?>" alt="icon"></span>
                                <?php endif ?>
                                <a href="<?php echo esc_url($category['link']['url']) ?? '#' ?>"><?php echo $category['link']['title'] ?? 'Title' ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif ?>
            <div class="product-nav sidebar-nav">
                <?php if ($product_nav = get_field('_product_services', 'options')): ?>
                    <ul>
                        <?php foreach ($product_nav as $item): ?>
                            <li>
                                <span><img src="<?php echo esc_url($item['icon']) ?? '' ?>" alt="icon"></span>
                                <a href="<?php echo esc_url($item['link']['url']) ?? '#' ?>"><?php echo $item['link']['title'] ?? 'Title' ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif ?>
				<?php if ($sustainable_nav = get_field('_sustainable', 'options')): ?>
                    <ul>
                        <?php foreach ($sustainable_nav as $item_sustainable): ?>
                            <li>
                                <span><img src="<?php echo esc_url($item_sustainable['icon']) ?? '' ?>" alt="icon"></span>
                                <a href="<?php echo esc_url($item_sustainable['link']['url']) ?? '#' ?>"><?php echo $item_sustainable['link']['title'] ?? 'Title' ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif ?>
                <?php if ($account_nav = get_field('_account_help', 'options')): ?>
                    <ul>
                        <?php foreach ($account_nav as $item_account): ?>
                            <li>
                                <span><img src="<?php echo esc_url($item_account['icon']) ?? '' ?>" alt="icon"></span>
                                <a href="<?php echo esc_url($item_account['link']['url']) ?? '#' ?>"><?php echo $item_account['link']['title'] ?? 'Title' ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif ?>
            </div>
            <?php echo cabling_add_quote_button(); ?>
        </div>
    </div>
</div>
