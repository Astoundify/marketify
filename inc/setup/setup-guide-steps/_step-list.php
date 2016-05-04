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

$steps[ 'theme-updater' ] = array(
	'title' => __( 'Enable Automatic Updates', 'marketify' ),
	'completed' => get_option( 'marketify_themeforest_updater_token', null ) && $themeforest_api->can_make_request_with_token() ? true : false
);

$steps[ 'install-plugins' ] = array(
	'title' => __( 'Install Required &amp; Recommended Plugins', 'marketify' ),
	'completed' => class_exists( 'Easy_Digital_Downloads' )
);

$steps[ 'import-content' ] = array(
	'title' => __( 'Install Demo Content', 'marketify' ),
	'completed' => 
		Astoundify_Importer_Manager::has_previously_imported( 'nav_menus' ) &&
		Astoundify_Importer_Manager::has_previously_imported( 'posts_pages' ) &&
		Astoundify_Importer_Manager::has_previously_imported( 'widgets' ) &&
		Astoundify_Importer_Manager::has_previously_imported( 'plugins' )
);

$steps[ 'customize-theme' ] = array(
	'title' => __( 'Customize', 'marketify' ),
	'completed' => get_option( 'theme_mods_marketify' ),
);

$steps[ 'support-us' ] = array(
	'title' => __( 'Get Involved', 'marketify' ),
	'completed' => 'na'
);

return $steps;
