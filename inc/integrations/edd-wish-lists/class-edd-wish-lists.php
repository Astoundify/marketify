<?php

class Marketify_Easy_Digital_Downloads_Wish_Lists extends Marketify_Integration {

	public function __construct() {
		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_action( 'init', array( $this, 'relocate' ) );
	}

	public function relocate() {
		remove_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );
		add_action( 'marketify_single_download_content_after', 'edd_wl_load_wish_list_link' );
	}

}
