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
		$this->init();
		$this->setup_actions();
	}

	private function includes() {
		if ( empty( $this->includes ) ) {
			return;
		}

		foreach ( $this->includes as $file ) {
			require_once( trailingslashit( $this->directory ) . $file );
		}
	}

	public function init() {}

	public function setup_actions() {}

}
