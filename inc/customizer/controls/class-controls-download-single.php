<?php

class Marketify_Customizer_Controls_Download_Single extends Marketify_Customizer_Controls {

	public $controls = array();

	public function __construct() {
		$this->section = 'download-single';
		$this->priority = new Marketify_Customizer_Priority(0, 10);

		parent::__construct();

		add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
		add_action( 'customize_register', array( $this, 'set_controls' ), 35 );

		add_filter( 'customize_control_active', array( $this, 'image_control_filter' ), 10, 2 );
	}

	public function image_control_filter( $active, $control ) {
		$controls = array(
			'download-standard-featured-image',
			'download-audio-featured-image',
			'download-video-featured-image'
		);

		if ( in_array( $control->id, $controls ) ) {
			$active = 'top' == marketify_theme_mod( 'download-feature-area' );
		}

		return $active;
	}

	public function add_controls( $wp_customize ) {
		$this->controls = array(
			'download-feature-area' => array(
				'label' => __( 'Feature Area Location', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					'top' => __( 'Page Header', 'marketify' ),
					'inline' => __( 'Page Content', 'marketify' )
				),
				'description' => __( 'Control where the relevant media (images, audio, video) will appear in the download', 'marketify' )
			),
			'download-standard-featured-image' => array(
				'label' => __( 'Standard Format Featured Image', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					'background' => __( 'Page Header Background', 'marketify' ),
					'inline' => __( 'Slider', 'marketify' ),
					'combined' => __( 'Combination', 'marketify' )
				),
			),
			'download-audio-featured-image' => array(
				'label' => __( 'Audio Format Featured Image', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					'background' => __( 'Page Header Background', 'marketify' ),
					'inline' => __( 'Below Audio Player', 'marketify' )
				),
			),
			'download-video-featured-image' => array(
				'label' => __( 'Video Format Featured Image', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					'background' => __( 'Page Header Background', 'marketify' ),
					'inline' => __( 'Below Video Player', 'marketify' )
				),
			),
		);

		return $this->controls;
	}
	
}

new Marketify_Customizer_Controls_Download_Single();
