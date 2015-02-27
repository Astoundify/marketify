<?php

class Jobify_Customizer_Controls_Colors extends Jobify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'colors';
		$this->priority = new Jobify_Customizer_Priority(49, 1);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'color-header-background' => array(
				'label' => __( 'Header Background Color', 'jobify' ),
				'type'    => 'WP_Customize_Color_Control'
			),
			'color-navigation-text' => array(
				'label' => __( 'Navigation Link Color', 'jobify' ),
				'type'    => 'WP_Customize_Color_Control'
			),
			'color-primary' => array(
				'label' => __( 'Primary Color', 'jobify' ),
				'type'    => 'WP_Customize_Color_Control'
			),
			'color-accent' => array(
				'label' => __( 'Accent Color', 'jobify' ),
				'type'    => 'WP_Customize_Color_Control'
			),
		);

		return $this->controls;
	}
	
}

new Jobify_Customizer_Controls_Colors();
