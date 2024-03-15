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
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <div class="wrap-inner download-items">
        <?php if (!empty($items)): ?>
        <div class="row gx-5">
            <?php foreach($items as $item): ?>
                <div class="col-12 col-md-6 col-lg-4 mb-5">
                    <div class="download-item">
                        <h4><?php echo $item['gi_download_heading'] ?></h4>
                        <p class="date text-danger"><?php echo $item['gi_download_date'] ?></p>
                        <?php if (!empty($item['gi_download_description'])): ?>
                            <p class="description"><?php echo $item['gi_download_description'] ?></p>
                        <?php endif ?>
                        <a href="<?php echo $item['gi_download_button']['url'] ?>" class="block-button"
                           target="<?php echo $item['gi_download_button']['target'] ?>"
                            >
                            <?php echo $item['gi_download_button']['title'] ?>
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>
