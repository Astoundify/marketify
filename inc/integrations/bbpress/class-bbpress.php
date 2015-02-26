<?php

class Marketify_bbPress extends Marketify_Integration {
	
	public function __construct() {
		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_filter( 'bbp_default_styles', array( $this, 'default_styles' ) );
		add_filter( 'bbp_before_get_breadcrumb_parse_args', 'breadcrumb_args' );
	}

	public function default_styles( $styles ) {
		$styles[ 'bbp-default' ][ 'file' ] = '';

		return $styles;
	}

	public function breadcrumb_args( $args ) {
		$args[ 'home_text' ] = __( 'Home', 'marketify' );

		return $args;
	}

}
