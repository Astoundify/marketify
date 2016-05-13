<?php
/**
 * Steps for the setup guide.
 *
 * @since 2.7.0
 */

/** Create the steps */
$steps = array();

$steps[ 'import-content' ] = array(
	'title' => __( 'Import Content', 'marketify' ),
	'completed' => false
);

$steps[ 'child-theme' ] = array(
	'title' => __( 'Enable Child Theme', 'marketify' ),
	'completed' => wp_get_theme()->parent()
);

$steps[ 'theme-updater' ] = array(
	'title' => __( 'Enable Automatic Updates', 'marketify' ),
	'completed' => get_option( 'marketify_themeforest_updater_token', null )
);

$steps[ 'install-plugins' ] = array(
	'title' => __( 'Install Plugins', 'marketify' ),
	'completed' => class_exists( 'Easy_Digital_Downloads' )
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
