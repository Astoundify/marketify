<?php
/**
 * bbPress
 *
 * @package Marketify
 */

/**
 * Check if we are using bbPress
 *
 * @since Marketify 1.0
 *
 * @return boolean
 */
function marketify_is_bbpress() {
	if ( ! function_exists( 'is_bbpress' ) )
		return false; 

	return is_bbpress();
}