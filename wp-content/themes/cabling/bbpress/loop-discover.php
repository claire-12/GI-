<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined('ABSPATH') || exit;
$value = $_GET['filter'] ?? '';
$category = $_GET['category'] ?? '';
$args['posts_per_page'] = -1;
?>
<div class="forums-discover">
    <form action="" method="get">
        <input type="hidden" name="filter">
        <h3 class="pre-heading heading-center"><?php echo __('Discover', 'cabling') ?></h3>
        <ul class="nav justify-content-center mb-5">
            <li class="nav-item">
                <?php if (bbp_has_forums($args)) : ?>
                    <select name="category" class="nav-link <?php echo empty($category) ? '' : 'active'; ?> forums-category">
                        <option value=""><?php echo __('All Categories', 'cabling'); ?></option>
                        <?php while (bbp_forums()) : bbp_the_forum(); ?>
                            <option value="<?php echo get_the_ID(); ?>" <?php selected($category, get_the_ID());  ?>><?php echo get_the_title() ?></option>
                        <?php endwhile; ?>
                    </select>
                <?php endif ?>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $value === 'featured' ? 'active' : ''?>" data-action="featured" href="#">Featured</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $value === 'popular' ? 'active' : ''?>" data-action="popular" href="#">Popular</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $value === 'alphabetical' ? 'active' : ''?>" data-action="alphabetical" href="#">Alphabetical</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $value === 'latest' ? 'active' : ''?>" data-action="latest" href="#">Latest Activity</a>
            </li>
        </ul>
    </form>
</div>
