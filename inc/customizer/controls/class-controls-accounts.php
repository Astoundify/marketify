<?php

class Jobify_Customizer_Controls_Accounts extends Jobify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'accounts';
		$this->priority = new Jobify_Customizer_Priority(0, 1);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
	}

	public function add_controls( $wp_customize ) {
		global $jobify_woocommerce;

		$this->controls = array(
			'registration-default' => array(
				'label'   => __( 'Default Role', 'jobify' ),
				'type'    => 'select',
				'choices' => $jobify_woocommerce->registration->get_registration_roles(),
				'description' => __( 'The default role selection when registering', 'jobify' )
			),
			'registration-roles' => array(
				'label'   => __( 'Available Registration Roles', 'jobify' ),
				'type'    => 'Jobify_Customize_Mulitcheck_Control',
				'choices' => $jobify_woocommerce->registration->get_registration_roles(),
				'description' => __( 'If no role is selected, the default in "Job Listings > Settings" will be used',
				'jobify' )
			),
		);

		return $this->controls;
	}

}

new Jobify_Customizer_Controls_Accounts();
