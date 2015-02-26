<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendors {

	public function __construct() {
		add_filter( 'fes_vendor_dashboard_menu', array( $this, 'dashboard_menu_icons' ) );
		add_filter( 'marketify_header_outer_image', array( $this, 'profile_cover_image' ), 1 );
	}

	public function dashboard_menu_icons( $menu ) {
		if ( EDD_FES()->integrations->is_commissions_active() ) {
			$menu[ 'earnings' ][ 'icon' ] = 'graph';
		}

		$menu[ 'home' ][ 'icon' ] = 'house';
		$menu[ 'orders' ][ 'icon' ] = 'ticket';
		$menu[ 'logout' ][ 'icon' ] = 'logout';

		return $menu;
	}

	public function marketify_header_outer_image_fes( $background ) {
		global $wp_query;

		if ( ! is_page_template( 'page-templates/vendor.php' ) ) {
			return $background;
		}

		$vendor = isset( $wp_query->query_vars[ 'vendor' ] ) ? $wp_query->query_vars[ 'vendor' ] : null;

		if ( ! $vendor ) {
			return $background;
		}

		$vendor = new WP_User( $vendor );

		$image = get_user_meta( $vendor->ID, 'cover_image', true );
		$image = wp_get_attachment_image_src( $image, 'fullsize' );

		if ( is_array( $image ) ) {
			add_filter( 'marketify_needs_a_background', '__true' );

			return $image;
		}

		return $background;
	}

}
