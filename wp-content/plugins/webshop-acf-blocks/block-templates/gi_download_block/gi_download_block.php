<?php

/**
 * GI Download Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during AJAX preview.
 * @param (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'gi_download_block_' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}
// Create class attribute allowing for custom "className" and "align" values.
$className = 'gi_download_block webshop-block';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}

// Load values and assign defaults.
$items = get_field('gi_download_item');
$typeRequest = $_GET['type'] ?? '';
if (isset($_GET['filter-by'])){
    $typeRequest = $_GET['filter-by'];
}
$type = match ($typeRequest) {
    'links' => 'Links',
    'news' => 'News',
    'download' => 'Download',
    default => null,
};
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="wrap-inner download-items">
        <?php if (!empty($items)): ?>
            <div class="download-filters d-flex justify-content-end mb-5">
                <form action="<?php echo get_the_permalink() ?>" method="get">
                    <select name="filter-by" class="form-select" id="download-filter"
                            style="border-radius: 10px; width: 150px">
                        <option value="name-desc">Name A-Z</option>
                        <option value="name-asc">Name Z-A</option>
                        <option value="links">Selected Papers</option>
                        <option value="download">Download</option>
                        <option value="news">Latest Articles</option>
                    </select>
                </form>
            </div>
            <div id="download-list" class="row gx-5">
                <?php foreach ($items as $item): ?>
                    <?php if (isset($type) && $type !== $item['gi_download_type']) {
                        continue;
                    } ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-5">
                        <div class="download-item" data-order="<?php echo $item['order'] ?>" data-name="<?php echo $item['gi_download_heading'] ?>">
                            <h4><?php echo $item['gi_download_heading'] ?></h4>
                            <p class="date text-danger"><?php echo $item['gi_download_date'] ?></p>
                            <?php if (!empty($item['gi_download_description'])): ?>
                                <p class="description"><?php echo $item['gi_download_description'] ?></p>
                            <?php endif ?>
                            <?php if (!empty($item['gi_download_button'])): ?>
                                <a href="<?php echo $item['gi_download_button']['url'] ?>" class="block-button"
                                   target="<?php echo $item['gi_download_button']['target'] ?>"
                                >
                                    <?php echo $item['gi_download_button']['title'] ?>
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>
</div>
