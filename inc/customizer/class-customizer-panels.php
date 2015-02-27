<?php

class Jobify_Customizer_Panels {

	public function __construct() {
		$this->priority = new Jobify_Customizer_Priority(0, 10);

		add_action( 'customize_register', array( $this, 'register_panels' ), 9 );
		add_action( 'customize_register', array( $this, 'organize_appearance' ), 11 );
		add_action( 'customize_register', array( $this, 'organize_general' ), 11 );
	}

	public function panel_list() {
		$jobs = array(
			'accounts' => array(
				'title' => __( 'Accounts', 'jobify' ),
				'priority' => $this->priority->next()
			),
			'jobs' => array(
				'title' => __( 'Jobs', 'jobify' ),
				'priority' => $this->priority->next()
			),
			'map-behavior' => array(
				'title' => __( 'Map Settings', 'jobify' ),
				'priority' => 99
			)
		);

		if ( jobify()->integrations->has( 'wp-job-manager-resumes' ) ) {
			$jobs[ 'resumes' ] = array(
				'title' => __( 'Resumes', 'jobify' ),
				'priority' => $this->priority->next()
			);
		}

		$this->panels = apply_filters( 'jobify_customizer_panels', array(
			'general' => array(
				'title' => __( 'General', 'jobify' ),
				'sections' => array(
				)
			),
			'appearance' => array(
				'title' => __( 'Appearance', 'jobify' ),
				'sections' => array(
					'colors' => array(
						'title' => __( 'Colors', 'jobify' ),
					),
				)
			),
			'jobs' => array(
				'title' => jobify()->integrations->has( 'wp-job-manager-resumes' ) ? __( 'Jobs & Resumes', 'jobify' ) : __( 'Jobs', 'jobify' ),
				'sections' => $jobs
			),
			'footer' => array(
				'title' => __( 'Footer', 'jobify' ),
				'sections' => array(
					'cta' => array(
						'title' => __( 'Call to Action', 'jobify' )
					),
					'copyright' => array(
						'title' => __( 'Copyright', 'jobify' )
					)
				)
			)
		) );

		return $this->panels;
	}

	public function register_panels( $wp_customize ) {
		$panels = $this->panel_list();

		foreach ( $panels as $key => $panel ) {
			$defaults = array(
				'priority' => $this->priority->next()
			);

			$panel = wp_parse_args( $defaults, $panel );

			$wp_customize->add_panel( $key, $panel );

			$sections = isset( $panel[ 'sections' ] ) ? $panel[ 'sections' ] : false;

			if ( $sections ) {
				$this->add_sections( $key, $sections, $wp_customize );
			}
		}
	}

	public function add_sections( $panel, $sections, $wp_customize ) {
		foreach ( $sections as $key => $section ) {
			$wp_customize->add_section( $key, array(
				'title' => $section[ 'title' ],
				'panel' => $panel,
				'priority' => isset( $section[ 'priority' ] ) ? $section[ 'priority' ] : $this->priority->next(),
				'description' => isset( $section[ 'description' ] ) ? $section[
				'description' ] : ''
			) );

			include_once( dirname( __FILE__ ) . '/controls/class-controls-' . $key . '.php' );
		}
	}

	public function organize_appearance( $wp_customize ) {
		$wp_customize->get_section( 'colors' )->panel = 'appearance';
		
		$wp_customize->get_section( 'header_image' )->panel = 'appearance';
		$wp_customize->get_section( 'header_image' )->title = __( 'Header & Logo', 'jobify' );

		$wp_customize->get_section( 'background_image' )->panel = 'appearance';

		return $wp_customize;
	}

	public function organize_general( $wp_customize ) {
		$wp_customize->get_section( 'title_tagline' )->panel = 'general';
		$wp_customize->get_section( 'title_tagline' )->title = __( 'Site Title', 'jobify' );

		$wp_customize->get_section( 'nav' )->panel = 'general';

		$wp_customize->get_section( 'static_front_page' )->panel = 'general';
		$wp_customize->get_section( 'static_front_page' )->title = __( 'Homepage Display', 'jobify' );

		$wp_customize->remove_control( 'blogdescription' );
		$wp_customize->get_control( 'display_header_text' )->section = 'header_image';

		return $wp_customize;
	}

}
