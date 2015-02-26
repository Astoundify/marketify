<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.4.0
 * @author     Thomas Griffin <thomasgriffinmedia.com>
 * @author     Gary Jones <gamajo.com>
 * @copyright  Copyright (c) 2014, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/thomasgriffin/TGM-Plugin-Activation
 */

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'jobify_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function jobify_register_required_plugins() {

	$plugins = array(
		array( 
			'name'      => 'Envato WordPress Toolkit',
			'slug'      => 'envato-wordpress-toolkit',
			'source'    => 'https://github.com/envato/envato-wordpress-toolkit/archive/master.zip',
			'external_url' => 'https://github.com/envato/envato-wordpress-toolkit',
			'required'  => false
		),
		array(
			'name'      => 'Easy Digital Downloads',
			'slug'      => 'easy-digital-downloads',
			'required'  => true,
		),
		array(
			'name'      => 'Jetpack',
			'slug'      => 'jetpack',
			'required'  => false,
		),
		array(
			'name'      => 'Nav Menu Roles',
			'slug'      => 'nav-menu-roles',
			'required'  => false,
		),
		array(
			'name'      => 'WordPress Importer',
			'slug'      => 'wordpress-importer',
			'required'  => false 
		),
		array(
			'name'      => 'Widget Importer & Exporter',
			'slug'      => 'widget-importer-exporter',
			'required'  => false,
		)
	);

	$config = array(
		'id'          => 'tgmpa-marketify-' . get_option( 'marketify_version', '2.0.0' ),
		'has_notices' => false
	);

	tgmpa( $plugins, $config );
}
