<?php
/**
 * Customizer helper functions and template tags.
 *
 * @package Marketify
 * @category Customizer
 * @since 2.11.0
 */

/**
 * Return a single theme mod, or its default.
 *
 * @since 2.11.0
 *
 * @param string $key The mod key.
 * @return string $mod The mod.
 */
function marketify_theme_mod( $key, $default = null ) {
	return get_theme_mod( $key, $default );
}
