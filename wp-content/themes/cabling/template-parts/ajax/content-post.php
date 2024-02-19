<div class="col-12 col-lg-4">
    <div class="blog-item">
        <div class="entry-content">
            <div class="featured-image">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()): the_post_thumbnail('full'); ?>
                    <?php else: echo wp_get_attachment_image(1032601); endif; ?>
                </a>
            </div>
            <div class="info">
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <div class="meta"><?php echo get_the_date('d M, Y'); ?></div>
            </div>
        </div>
    </div>
</div>

