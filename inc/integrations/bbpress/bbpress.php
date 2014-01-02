<?php
/**
 * bbPress
 *
 * @package Marketify
 */

/**
 * Override bbPress breadcrumb home text.
 *
 * Since our static homepage most likely has a long title,
 * lets reset that to a standard home text.
 *
 * @since Marketify 1.0
 *
 * @param array $args
 * @return array $args
 */
function marketify_bbp_before_get_breadcrumb_parse_args( $args ) {
	$args[ 'home_text' ] = __( 'Home', 'marketify' );

	return $args;
}
add_filter( 'bbp_before_get_breadcrumb_parse_args', 'marketify_bbp_before_get_breadcrumb_parse_args' );