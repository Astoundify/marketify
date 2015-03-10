<?php

class Marketify_Customizer_Controls_Download_Single extends Marketify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'download-single';
		$this->priority = new Marketify_Customizer_Priority(0, 10);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'download-feature-area' => array(
				'label' => __( 'Feature Area', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					'top' => __( 'Page Header', 'marketify' ),
					'inline' => __( 'Page Content', 'marketify' )
				)
			),
		);

		return $this->controls;
	}
	
}

new Marketify_Customizer_Controls_Download_Single();
