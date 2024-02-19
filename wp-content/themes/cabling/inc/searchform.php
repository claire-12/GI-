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
    action="<?php echo site_url( '/search-results/' ); ?>">
    <label>
        <span class="screen-reader-text">
            <?php echo _x( 'Search for:', 'label' ) ?>
        </span>
        <input type="search" class="search-field"
            name="searchwp"
            placeholder="<?php echo esc_attr_x( 'Search term or article number', 'placeholder' ) ?>"
            value="<?php echo isset( $_GET['searchwp'] ) ? esc_attr( $_GET['searchwp'] ) : '' ?>"
            title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" autocomplete="off"/>
    </label>
    <!--<input type="submit" class="search-submit"
        value="<?php /*echo esc_attr_x( 'Search', 'submit button' ) */?>" />-->
    <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
</form>
