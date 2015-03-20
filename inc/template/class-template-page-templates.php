<?php

class Marketify_Template_Page_Templates {

	public function __construct() {
		add_filter( 'theme_page_templates', array( $this, 'fes' ) );
		add_filter( 'theme_page_templates', array( $this, 'love_it' ) );
	}

	public function fes( $page_templates ) {
		if ( marketify()->get( 'easy-digital-downloads-frontend-submissions' ) ) {
			return $page_templates;
		}

		unset( $page_templates[ 'page-templates/vendor.php' ] );

		return $page_templates;
	}

	public function love_it( $page_templates ) {
		if ( marketify()->get( 'love-it' ) ) {
			return $page_templates;
		}

		unset( $page_templates[ 'page-templates/wishlist.php' ] );

		return $page_templates;
	}

}
