<?php

class Marketify_Integrations {

	private $supported_integrations;
	public $integrations;
	
	public function __construct() {
		$this->supported_integrations = array(
			'easy-digital-downloads' => array(
				class_exists( 'Easy_Digital_Downloads' ),
				'Marketify_Easy_Digital_Downloads'
			),
			'easy-digital-downloads-frontend-submissions' => array(
				defined( 'EDD_Front_End_Submissions' ),
				'Marketify_Easy_Digital_Downloads_Frontend_Submission'
			),
			'easy-digital-downloads-cross-sell-upsell' => array(
				defined( 'edd_csau_version' ),
				'Marketify_Easy_Digital_Downloads_Cross_Sell_UpSell'
			),
			'bbpress' => array(
				class_exists( 'bbPress' ),
				'Marketify_bbPress'
			)
		);

		$this->load_integrations();
	}

	public function has( $key ) {
		return isset( $this->integrations[ $key ] );
	}

	public function get( $key ) {
		if ( ! $this->has( $key ) ) {
			return false;
		}

		return $this->integrations[ $key ];
	}

	public function add( $key, $class ) {
		$this->integrations[ $key ] = $class;
	}

	private function load_integrations() {
		foreach ( $this->supported_integrations as $key => $integration ) {
			if ( $integration[0] ) {
				require_once( get_template_directory() . '/inc/integrations/' . $key . '/class-' . $key . '.php' );

				$class = new $integration[1];

				$this->add( $key, $class );
			}
		}
	}

}
