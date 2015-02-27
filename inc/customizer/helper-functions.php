<?php

function jobify_get_theme_mod_defaults() {
	$mods = array(
		// Colors
		'color-header-background' => '#01da90',
		'color-navigation-text' => '#ffffff',
		'color-primary' => '#01da90',
		'color-accent' => '#f08d3c',
	
		// Accounts
		'registration-default' => 'employer',
		'registration-roles' => array( 'employer', 'candidate' ),
		
		// Map Behavior
		'map-behavior-clusters' => 1,
		'map-behavior-grid-size' => 60,
		'map-behavior-autofit' => 1,
		'map-behavior-center' => '',
		'map-behavior-zoom' => 3,
		'map-behavior-max-zoom' => 17,

		// Jobs
		'job-display-sidebar' => 'top',
		'job-display-sidebar-columns' => 3,

		// Resumes 
		'resume-display-sidebar' => 'top',
		'resume-display-sidebar-columns' => 3,

		// Call to Action
		'cta-display' => true,
		'cta-text' => '<h2>Got a question?</h2>\n\nWe&#39;re here to help. Check out our FAQs, send us an email or call
		us at 1 800 555 5555',
		'cta-text-color' => '#ffffff',
		'cta-background-color' => '#3399cc',
		
		// Copyright
		'copyright' => sprintf( '&copy; %1$s %2$s &mdash; All Rights Reserved', date( 'Y' ), get_bloginfo( 'name' ) )
	);

	return apply_filters( 'jobify_theme_mod_defaults', $mods );
}

function jobify_theme_mod( $key ) {
	$mods = jobify_get_theme_mod_defaults();
	
	$default = isset( $mods[ $key ] ) ? $mods[ $key ] : '';

	return get_theme_mod( $key, $default );
}
