<?php

class Jobify_Customizer_Controls_Resumes extends Jobify_Customizer_Controls {

	public function __construct() {
		$this->section = 'resumes';

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
		
		add_filter( 'customize_control_active', array( $this, 'sidebar_control_filter' ), 10, 2 );
	}

	public function sidebar_control_filter( $active, $control ) {
		if ( 'resume-display-sidebar-columns' === $control->id ) {
			$active = 'top' == jobify_theme_mod( 'resume-display-sidebar' );
		}

		return $active;
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'resume-display-sidebar' => array(
				'label' => __( 'Widgetized Area Location', 'jobify' ),
				'type' => 'select',
				'choices' => array(
					'side' => __( 'Sidebar', 'jobify' ),
					'top' => __( 'Above Resume', 'jobify' )
				)
			),
			'resume-display-sidebar-columns' => array(
				'label' => __( 'Widget Columns', 'jobify' ),
				'type' => 'select',
				'choices' => array( 1 => 1, 2 => 2, 3 => 3 )
			)
		);
		
		return $wp_customize;
	}

}

new Jobify_Customizer_Controls_Resumes();
