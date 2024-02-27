<div class="row g-5">
    <?php foreach ($taxonomies as $taxonomy): ?>
        <?php
        $thumbnail = getTaxonomyThumbnail($taxonomy);
        $link = get_term_link($taxonomy);
        ?>
        <div class="col-xs-12 col-md-6 col-lg-3" style="position: relative; ">
            <span class="wp-element-caption"><?php echo $taxonomy->name; ?></span>
            <?php echo $thumbnail ?>
        </div>
    <?php endforeach; ?>
</div>
