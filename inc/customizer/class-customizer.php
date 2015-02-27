<?php
/**
 * Customize
 *
 * @package Jobify
 * @since Jobify 2.1.0
 */

class Jobify_Customizer {

	public function __construct() {
		$files = array(
			'helper-functions.php',
			'class-customizer-priority.php',
			'class-customizer-panels.php',
			'class-customizer-controls.php',
			'class-customizer-css.php',
		);

		foreach ( $files as $file ) {
			include_once( trailingslashit( dirname( __FILE__) ) . $file );
		}
		
		add_action( 'customize_register', array( $this, 'custom_controls' ) );
		add_action( 'customize_register', array( $this, 'setup_panels' ) );

		$output = array(
			'class-customizer-output-colors.php',
		);

		foreach ( $output as $file ) {
			include_once( trailingslashit( dirname( __FILE__) ) . 'output/' . $file );
		}
	}

	public function custom_controls() {
		include_once( dirname( __FILE__) . '/control/class-control-textarea.php' );
		include_once( dirname( __FILE__) . '/control/class-control-multicheck.php' );
	}

	public function setup_panels() {
		$this->panels = new Jobify_Customizer_Panels();
	}

}

new Jobify_Customizer();
