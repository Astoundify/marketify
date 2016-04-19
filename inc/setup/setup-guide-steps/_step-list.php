<?php
/**
 * Steps for the setup guide.
 *
 * @since 2.7.0
 */

/** Some deps */
$themeforest_api = Astoundify_Envato_Market_API::instance();

/** Create the steps */
$steps = array();

$steps[ 'import-content' ] = array(
	'title' => __( 'Demo Content Setup', 'marketify' ),
	'completed' => 
		Astoundify_Importer_Manager::has_previously_imported( 'nav_menus' ) &&
		Astoundify_Importer_Manager::has_previously_imported( 'posts_pages' ) &&
		Astoundify_Importer_Manager::has_previously_imported( 'widgets' ) &&
		Astoundify_Importer_Manager::has_previously_imported( 'plugins' )
);

$steps[ 'theme-updater' ] = array(
	'title' => __( 'Enable Automatic Updates', 'marketify' ),
	'completed' => get_option( 'marketify_themeforest_updater_token', null ) && $themeforest_api->can_make_request_with_token() ? true : false
);

$steps[ 'install-plugins' ] = array(
	'title' => __( 'Install Required &amp; Recommended Plugins', 'marketify' ),
	'completed' => class_exists( 'Easy_Digital_Downloads' )
);

if ( class_exists( 'Easy_Digital_Downloads' ) ) { 
	$steps[ 'setup-edd' ] = array(
		'title' => 'Configure Easy Digital Downloads',
		'completed' => true == get_option( 'edd_settings' )
	);
}

if ( class_exists( 'EDD_Front_End_Submissions' ) ) {
	$steps[ 'setup-fes' ] = array(
		'title' => 'Configure Frontend Submissions',
		'completed' => 0 == EDD_FES()->helper->get_option( 'fes-use-css' ),
	);
}

$steps[ 'customize-theme' ] = array(
	'title' => __( 'Customize', 'marketify' ),
	'completed' => get_option( 'theme_mods_marketify' ),
);

$steps[ 'support-us' ] = array(
	'title' => __( 'Get Involved', 'marketify' ),
	'completed' => 'na'
);

return $steps;
