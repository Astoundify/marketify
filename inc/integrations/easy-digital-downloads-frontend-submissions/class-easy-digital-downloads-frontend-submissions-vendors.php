<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendors {

	public function __construct() {
		add_filter( 'fes_vendor_dashboard_menu', array( $this, 'dashboard_menu_icons' ) );
		add_filter( 'marketify_header_outer_image', array( $this, 'profile_cover_image' ), 1 );

		add_action( 'marketify_download_entry_meta', array( $this, 'byline' ) );
	}

	public function byline() {	
		global $post;

		printf(
			__( '<span class="byline"> by %1$s</span>', 'marketify' ),
			sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
				$this->get_url( $post->post_author ),
				esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), get_the_author() ) ),
				esc_html( get_the_author_meta( 'display_name', $post->post_author ) ),
				get_avatar( get_the_author_meta( 'ID', $post->post_author ), 50, apply_filters( 'marketify_default_avatar', null ) )
			)
		);
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
