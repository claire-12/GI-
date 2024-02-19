<?php
/**
 * Template Name: Products & Services Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cabling
 */
$product_group = null;
if (isset($_REQUEST['group'])) {
    $product_group = get_term_by('slug', $_REQUEST['group'], 'product_group');
    if ($product_group) {
        $product_lines = get_product_line_category('product_line', 'group_category', [$product_group->term_id]);
    }
}

$product_cats = get_product_line_category('product_group', 'family_category', ['8626']);
if (empty($product_lines) && empty($product_group)) {
    $product_lines = get_product_category('product_line');
}

get_header();
?>
    <div id="primary" class="content-area mt-5 product-services woocommerce">
        <main id="main" class="site-main">
            <div class="container">
                <div class="row">
                    <div class="col col-xs-12 col-lg-4">
                        <div class="woo-sidebar">
                            <?php get_template_part('template-parts/filter', 'product', ['categories' => $product_cats, 'product_cat' => $product_group]) ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-8">
                        <?php get_template_part('template-parts/filter_heading', 'product') ?>
                        <div id="filtered-category-container">
                            <?php if (isset($product_lines)): ?>
                                <?php foreach ($product_lines as $line) {
                                    //$children = get_product_type_category($line->term_id);
                                    $productTypes = get_product_line_category('product_custom_type', 'product_line', [$line->term_id]);

                                    get_template_part('template-parts/product', 'category', [
                                        'category' => $line,
                                        'children' => $productTypes,
                                    ]);
                                } ?>
                            <?php endif ?>
                        </div>
                        <div id="filtered-results-container"></div>

                        <div class="woocommerce-no-products-found <?php echo empty($product_lines) ? '' : 'hidden' ?>">
                            <div class="woocommerce-info">
                                <?php echo __('No category was found matching your selection.', 'cabling') ?>
                            </div>
                        </div>
                        <div class="woocommerce-product-type-custom hidden text-center">
                            <p class="text-for-size hidden"><?php echo __('Looking for O-ring sizes beyond AS? <br> We’ve got one of the widest selection of sizes in the industry, as well as custom capabilities to best meet your requirements. <br>Drop us a line and a member of the team will reach out to you promptly.', 'cabling') ?></p>
                            <?php cabling_add_quote_button() ?>
                        </div>
                    </div><!-- .col -->
                </div><!-- .row -->
            </div>
            <div class="row">
                <div class="col-12 mt-5">
                    <?php cabling_add_quote_section(); ?>
                </div>
            </div>
        </main><!-- #main -->
    </div><!-- #primary -->

<?php
get_footer();
