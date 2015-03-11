<?php

function marketify_get_theme_mod_defaults() {
	$mods = array(
		// Colors
		'color-page-header-background' => '#515a63',
		'color-primary' => '#515a63',
		'color-accent' => '#50ca8b',

		// Downloads
		'download-label-singular' => 'Download',
		'download-label-plural' => 'Downloads',
		'download-label-generate' => 'on',

		'download-archives-popular' => 'on',
		'download-archives-excerpt' => '',
		'download-archives-truncate-title' => '',
		'download-archives-meta' => 'auto',
		'downloads-grid-height' => 520,
		'downloads-per-page' => 12,
		'downloads-columns' => 3,

		'download-feature-area' => 'top',

		// Footer
		'footer-contact-address' => false
	);

	return apply_filters( 'marketify_theme_mod_defaults', $mods );
}

function marketify_theme_mod( $key ) {
	$mods = marketify_get_theme_mod_defaults();
	
	$default = isset( $mods[ $key ] ) ? $mods[ $key ] : '';

	return get_theme_mod( $key, $default );
}
