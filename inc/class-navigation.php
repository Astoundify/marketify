<?php

class Marketify_Navigation {

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'register_menus' ) );
	}

	public function register_menus() {
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'marketify' ),
			'social'  => __( 'Footer Social', 'marketify' )
		) );
	}

}
