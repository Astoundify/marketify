<?php

class Jobify_Customizer_Controls_CTA extends Jobify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'cta';
		$this->priority = new Jobify_Customizer_Priority(0, 10);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'cta-display' => array(
				'label' => __( 'Display the "Call to Action"', 'jobify' ),
				'type'    => 'checkbox'
			),
			'cta-text' => array(
				'label' => __( 'Description', 'jobify' ),
				'type'    => 'Jobify_Customize_Textarea_Control'
			),
			'cta-text-color' => array(
				'label' => __( 'Text Color', 'jobify' ),
				'type'    => 'WP_Customize_Color_Control'
			),
			'cta-background-color' => array(
				'label' => __( 'Background Color', 'jobify' ),
				'type'    => 'WP_Customize_Color_Control'
			),
		);

		return $this->controls;
	}
	
}

new Jobify_Customizer_Controls_CTA();
