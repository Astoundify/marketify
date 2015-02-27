<?php

function marketify_get_theme_mod_defaults() {
	$mods = array(
		// Colors
		'color-page-header-background' => '#515a63',
		'color-primary' => '#515a63',
		'color-accent' => '#50ca8b'
	);

	return apply_filters( 'marketify_theme_mod_defaults', $mods );
}

function marketify_theme_mod( $key ) {
	$mods = marketify_get_theme_mod_defaults();
	
	$default = isset( $mods[ $key ] ) ? $mods[ $key ] : '';

	return get_theme_mod( $key, $default );
}
