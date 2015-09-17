<?php

class Marketify_Customizer_Controls_Download_Archives extends Marketify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'download-archives';
		$this->priority = new Marketify_Customizer_Priority(0, 10);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'downloads-archives-per-page' => array(
				'label' => sprintf( __( '%s Per Page', 'marketify' ), edd_get_label_plural() ),
				'type' => 'number',
				'description' => __( 'Can be overwritten by passing <code>number</code> to the <code>[downloads]</code>
				shortcode', 'marketify' )
			),
			'downloads-archives-columns' => array(
				'label' => __( 'Number of Columns', 'marketify' ),
				'type' => 'select',
				'choices' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 ),
				'description' => __( 'Can be overwritten by passing <code>columns</code> to the <code>[downloads]</code> shortcode', 'marketify' )
			),
			'downloads-archives-popular' => array(
				'label' => __( 'Display "Popular Items" above results', 'marketify' ),
				'type'    => 'checkbox'
			),
			'downloads-archives-excerpt' => array(
				'label' => __( 'Display excerpt below title', 'marketify' ),
				'type'    => 'checkbox'
			),
			'downloads-archives-truncate-title' => array(
				'label' => __( 'Truncate item titles', 'marketify' ),
				'type'    => 'checkbox'
			),
			'downloads-archives-meta' => array(
				'label' => __( 'Display Titles & Meta', 'marketify' ),
				'type'    => 'radio',
				'choices' => array(
					'auto' => __( 'Auto', 'marketify' ),
					'always' => __( 'Always', 'marketify' ),
					'never' => __( 'Never', 'marketify' )
				)
			)
		);

		return $this->controls;
	}
	
}

new Marketify_Customizer_Controls_Download_Archives();