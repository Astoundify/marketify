<?php

class Marketify_Template_Page_Templates {

	public function __construct() {
		add_filter( 'theme_page_templates', array( $this, 'fes' ) );
	}

	public function fes( $page_templates ) {
		if ( Marketify::get( 'easy-digital-downloads-frontend-submissions' ) ) {
			return $page_templates;
		}

		unset( $page_templates[ 'page-templates/vendor.php' ] );

		return $page_templates;
	}

}
