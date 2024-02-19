<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label if
 * one was passed to get_search_form() in the args array.
 */

$aria_label = ! empty( $args['label'] ) ? 'aria-label="' . esc_attr( $args['label'] ) . '"' : '';
?>
<form role="search" method="get" class="search-form"
      onsubmit="return false"
    action="<?php echo site_url( '/search-results/' ); ?>">
    <div class="container">
        <label>
            <i class="fa-sharp fa-regular fa-magnifying-glass"></i>
            <input type="text" class="search-field"
                   name="searchwp"
                   spellcheck="true"
                   placeholder="<?php echo esc_attr_x( 'Search', 'cabling' ) ?>"
                   value="<?php echo isset( $_GET['searchwp'] ) ? esc_attr( $_GET['searchwp'] ) : '' ?>"
                   title="<?php echo esc_attr_x( 'Search for:', 'cabling' ) ?>" autocomplete="off"/>
            <span class="close-search"><i class="fa-duotone fa-circle-xmark"></i></span>
        </label>

    </div>
    <!--<button type="submit"><i class="fa-sharp fa-light fa-magnifying-glass"></i></button>-->
</form>
