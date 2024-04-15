<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package test
 */
$taxonomy = get_queried_object();
$thumbnail = getTaxonomyThumbnail($taxonomy, 'w-100 mb-3');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
    <header class="entry-header">
        <?php echo $thumbnail; ?>
        <div class="title"><h3><?php echo $taxonomy->name ?></h3></div>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php echo $taxonomy->description ?>
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
