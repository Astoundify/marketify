<?php

class Jobify_Customizer_Controls_Copyright extends Jobify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'copyright';
		$this->priority = new Jobify_Customizer_Priority(0, 10);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'copyright' => array(
				'label' => __( 'Copyright', 'jobify' ),
				'type'    => 'Jobify_Customize_Textarea_Control'
			),
		);

		return $this->controls;
	}
	
}

new Jobify_Customizer_Controls_Copyright();
