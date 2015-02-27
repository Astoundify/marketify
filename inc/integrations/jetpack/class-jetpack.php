<?php

class Marketify_Jetpack extends Marketify_Integration {

	public function __construct() {
		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_action( 'after_setup_theme', array( $this, 'add_theme_support' ) );
	}

	public function add_theme_support() {
		add_theme_support( 'infinite-scroll', array(
			'container' => 'main',
			'footer'    => 'page',
		) );
	}

}
