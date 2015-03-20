<?php
/**
 * Plugin integrations.
 */

abstract class Marketify_Integration {
	
	public $includes = array();

	public $directory;

	public function __construct( $directory ) {
		$this->directory = $directory;

		$this->includes();
		$this->start();
		$this->internal_actions();
	}

	private function includes() {
		if ( empty( $this->includes ) ) {
			return;
		}

		foreach ( $this->includes as $file ) {
			require_once( trailingslashit( $this->directory ) . $file );
		}
	}

	public function start() {
		add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
		add_action( 'plugins_loaded', array( $this, 'setup_actions' ), 0 );
	}

	private function internal_actions() {
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	public function body_class( $classes ) {
		$classes[] = $this->get_slug();

		return $classes;
	}

	private function get_slug() {
		$pieces = explode( '/', $this->directory );
		$slug = end( $pieces );

		return $slug;
	}

}
