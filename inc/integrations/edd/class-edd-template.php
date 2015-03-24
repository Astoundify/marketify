<?php
	
class Marketify_EDD_Template {

	public function __construct() {
		add_filter( 'marketify_archive_title', array( $this, 'archive_title' ) );

		$this->navigation = new Marketify_EDD_Template_Navigation();
		$this->purchase_form = new Marketify_EDD_Template_Purchase_Form();
		$this->download = new Marketify_EDD_Template_Download();
	}

	public function author_url( $user_id ) {
		$fes = marketify()->get( 'edd-fes' );

		if ( $fes ) {
			$vendor = $this->fes->vendor( $user_id );
			$url = $vendor->url();
		} else {
			$url = get_author_posts_url( $user_id );
		}

		return apply_filters( 'marketify_author_url', esc_url( $url ) );
	}

	public function archive_title( $title ) {
		if ( is_tax() ) {
			$title = single_term_title( '', false );
		} else if ( is_search() ) {
			$title = esc_attr( get_search_query() );
		}

		return $title;
	}

}
