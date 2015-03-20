<?php

class Marketify_Integrations {

	private $supported_integrations;
	public $integrations;
	
	public function __construct() {
		$this->supported_integrations = array(
			'bbpress' => array(
				class_exists( 'bbPress' ),
				'Marketify_bbPress'
			),
			'easy-digital-downloads' => array(
				class_exists( 'Easy_Digital_Downloads' ),
				'Marketify_Easy_Digital_Downloads'
			),
			'easy-digital-downloads-frontend-submissions' => array(

				class_exists( 'EDD_Front_End_Submissions' ),
				'Marketify_Easy_Digital_Downloads_Frontend_Submissions'
			),
			'easy-digital-downloads-product-reviews' => array(
				class_exists( 'EDD_Reviews' ),
				'Marketify_Easy_Digital_Downloads_Product_Reviews'
			),
			'easy-digital-downloads-recommended-products' => array(
				function_exists( 'edd_rp_get_suggestions' ),
				'Marketify_Easy_Digital_Downloads_Recommended_Products'
			),
			'easy-digital-downloads-wish-lists' => array(
				class_exists( 'EDD_Wish_Lists' ),
				'Marketify_Easy_Digital_Downloads_Wish_Lists'
			),
			'easy-digital-downloads-cross-sell-upsell' => array(
				defined( 'edd_csau_version' ),
				'Marketify_Easy_Digital_Downloads_Cross_Sell_UpSell'
			),
			'jetpack' => array(
				class_exists( 'Jetpack' ),
				'Marketify_Jetpack'
			),
			'love-it' => array(
				defined( 'LI_BASE_DIR' ),
				'Marketify_Love_It'
			),
			'multiple-post-thumbnails' => array(
				class_exists( 'MultiPostThumbnails' ),
				'Marketify_Multiple_Post_Thumbnails'
			),
			'woothemes-features' => array(
				class_exists( 'WooThemes_Features' ),
				'Marketify_WooThemes_Features'
			),
			'woothemes-projects' => array(
				class_exists( 'WooThemes_Projects' ),
				'Marketify_WooThemes_Projects'
			),
			'woothemes-testimonials' => array(
				class_exists( 'WooThemes_Testimonials' ),
				'Marketify_WooThemes_Testimonials'
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
				require_once( trailingslashit( dirname( __FILE__ ) ) . trailingslashit( $key ) . 'class-' . $key . '.php' );

				$class = new $integration[1];

				$this->add( $key, $class );
			}
		}
	}

}
