<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cabling
 */

if (!function_exists('cabling_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function cabling_posted_on()
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
        /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'cabling'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

    }
endif;

if (!function_exists('cabling_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function cabling_posted_by()
    {
        $byline = sprintf(
        /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'cabling'),
            '<span class="author vcard">' . esc_html(get_the_author()) . '</span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

    }
endif;

if (!function_exists('cabling_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function cabling_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'cabling'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<div class="cat-links">' . esc_html__('Posted in %1$s', 'cabling') . '</div>', $categories_list); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'cabling'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<div class="tags-links">' . esc_html__('Tagged %1$s', 'cabling') . '</div>', $tags_list); // WPCS: XSS OK.
            }
        }
    }
endif;

if (!function_exists('cabling_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function cabling_post_thumbnail()
    {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail('full', array(
                    'alt' => the_title_attribute(array(
                        'echo' => false,
                    )),
                ));
                ?>
            </a>

        <?php
        endif; // End is_singular().
    }
endif;


/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function cabling_entry_footer_custom($taxonomy = 'category')
{
    /* translators: used between list items, there is a space after the comma */
    $term_list = get_the_term_list(get_the_ID(), $taxonomy, '', ', ');
    if ($term_list) {
        /* translators: 1: list of categories. */
        printf('<div class="cat-links">' . esc_html__('Posted in %1$s', 'cabling') . '</div>', $term_list); // WPCS: XSS OK.
    }
}
