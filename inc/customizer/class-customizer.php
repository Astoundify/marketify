<?php

class Marketify_Customizer {

	public function __construct() {
		$files = array(
			'helper-functions.php',
			'class-customizer-priority.php',
			'class-customizer-panels.php',
			'class-customizer-controls.php',
			'class-customizer-css.php',
			'output/class-customizer-output-colors.php'
		);

		foreach ( $files as $file ) {
			include_once( trailingslashit( dirname( __FILE__) ) . $file );
		}

		$this->setup_actions();
	}

	public function setup_actions() {
		add_action( 'customize_register', array( $this, 'custom_controls' ) );

		add_action( 'customize_register', array( $this, 'init_panels' ) );

		add_action( 'plugins_loaded', array( $this, 'init_output' ) );
	}

	public function custom_controls() {
		include_once( dirname( __FILE__) . '/control/class-control-textarea.php' );
		include_once( dirname( __FILE__) . '/control/class-control-multicheck.php' );
	}

	public function init_panels() {
		$this->panels = new Marketify_Customizer_Panels();
	}

	public function init_output() {
		$this->colors = new Marketify_Customizer_Output_Colors();
	}

}
