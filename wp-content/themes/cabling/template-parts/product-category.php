<?php
$cat = $args['category'];
$children = $args['children'];
$includes = $args['includes'] ?? null;
?>
<?php if ($children): ?>
<div class="list-categories <?php printf('product-%d', $cat->term_id) ?>">
    <h3><?php echo $cat->name; ?></h3>
        <div class="row">
            <?php foreach ($children as $child) {
                $short_description = get_field('short_description', $child);
                $thumbnail_id = get_field('taxonomy_image', $child);
                $thumbnail_id = empty($thumbnail_id) ? 1032601 : $thumbnail_id;
                $thumbnail = wp_get_attachment_image($thumbnail_id, 'medium');
                ?>
                <div class="cat-item col-sm-6 col-md-4"
                     data-category="<?php echo $child->term_id ?>">
                    <a href="javascript:void(0)"
                       title="<?php echo $child->name ?>">
                        <?php echo $thumbnail; ?>
                    </a>
                    <h5>
                        <a href="javascript:void(0)"
                           title="<?php echo $child->name ?>">
                            <?php echo $child->name ?>
                        </a>
                    </h5>
                    <div class="cat-desc mb-2">
                        <?php echo str_replace("''","'",$short_description) ?? '' ?>
                    </div>
                    <a href="javascript:void(0)" class="block-button btn-red">
                        <span><?php _e('FIND OUT MORE', 'cabling') ?></span>
                    </a>
                </div>
            <?php } ?>
        </div>
</div>
<?php endif ?>
