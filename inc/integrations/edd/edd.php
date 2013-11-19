<?php
/**
 * Easy Digital Downloads
 *
 * @package Marketify
 */

/**
 * Cart menu item
 *
 * @since Marketify 1.0
 */
function marketify_wp_nav_menu_items( $items, $args ) {
	if ( 'primary' != $args->theme_location )
		return;

	ob_start();

	$widget = the_widget( '' );

	sprintf();
}
add_filter( 'wp_nav_menu_items', 'marketify_wp_nav_menu_items', 10, 2 );

/**
 * EDD Download Class
 *
 * When using the [downloads] shortcode, add our own class to match
 * our awesome styling.
 *
 * @since Marketify 1.0
 *
 * @param string $class
 * @param string $id
 * @param array $atts
 * @return string The updated class list
 */
function marketify_edd_download_class( $class, $id, $atts ) {
	return $class . ' content-grid-download';
}
add_filter( 'edd_download_class', 'marketify_edd_download_class', 10, 3 );

/**
 * EDD Download Shortcode Attributes
 *
 * @since Marketify 1.0
 *
 * @param array $atts
 * @return array $atts
 */
function marketify_shortcode_atts_downloads( $atts ) {
	$atts[ 'excerpt' ]      = 'no';
	$atts[ 'full_content' ] = 'no';
	$atts[ 'price' ]        = 'no';
	$atts[ 'buy_button' ]   = 'no';

	return $atts;
}
add_filter( 'shortcode_atts_downloads', 'marketify_shortcode_atts_downloads' );

/**
 * Add standard comments to the Downloads post type.
 *
 * @since Marketify 1.0
 *
 * @param array $supports
 * @return array $supports
 */
function marketify_edd_product_supports( $supports ) {
	$supports[] = 'comments';

	return $supports;	
}
add_filter( 'edd_download_supports', 'marketify_edd_product_supports' );

/**
 * Add an extra class to the purchase form if the download has
 * variable pricing. There is no filter for the class, so we have to hunt.
 *
 * @since Marketify 1.0
 *
 * @param string $purchase_form
 * @param array $args
 * @return string $purchase_form
 */
function marketify_edd_purchase_download_form( $purchase_form, $args ) {
	$download_id = $args[ 'download_id' ];

	if ( ! $download_id )
		return $purchase_form;

	if ( ! is_singular( 'download' ) || ! edd_has_variable_prices( $download_id ) )
		return $purchase_form;

	$purchase_form = str_replace( 'class="edd_download_purchase_form"', 'class="edd_download_purchase_form download-variable"', $purchase_form );

	return $purchase_form;
}
add_filter( 'edd_purchase_download_form', 'marketify_edd_purchase_download_form', 10, 2 );

/**
 * Make sure the only available color is the one we want (inherit)
 *
 * @since Marketify 1.0
 *
 * @param array $colors
 * @return array $colors
 */
function marketify_edd_button_colors( $colors ) {
	$unset = array( 'white', 'blue', 'gray', 'red', 'green', 'yellow', 'orange', 'dark-gray' );

	foreach ( $unset as $color ) {
		unset( $colors[ $color ] );
	}

	return $colors;
}
add_filter( 'edd_button_colors', 'marketify_edd_button_colors' );