<?php

class Marketify_FacetWP extends Marketify_Integration {

	public function __construct() {
		parent::__construct( dirname( __FILE__) );
	}

	public function setup_actions() {
		add_filter( 'downloads_shortcode', array( $this, 'facetwp_template' ), 20, 2 );
	}

	public function facetwp_template( $output ) {
		$output = str_replace( 'class="edd_downloads_list', 'class="edd_downloads_list facetwp-template', $output );

		return $output;
	}

}
