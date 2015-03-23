<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendor {

	public $obj;

	public function __construct( $author = false ) {
		if ( ! $author ) { 
			$author = $this->find();
		} elseif ( is_numeric( $author ) ) {
			$author = new WP_User( $author );
		}
		
		$this->obj = $author;
	}

	private function find() {
		if ( function_exists( 'fes_get_vendor' ) ) {
			$author = fes_get_vendor();
		} else {
			$author = get_query_var( 'vendor' );
			$author = get_user_by( 'slug', $author );
		}

		if ( ! $author ) {
			$author = wp_get_current_user();
		}

		return $author;
	}

	public function url() {
		return FES_Vendors::get_vendor_store_url( $this->obj->ID );
	}

	public function display_name() {
		$display_name = esc_attr( $this->obj->display_name );
		
		if ( '' == $display_name ) {
			$display_name = esc_attr( $this->obj->user_login );
		}
		
		return $display_name;
	}

	public function date_registered() {
		return date_i18n( 'Y', strtotime( $this->obj->user_registered ) );
	}

	public function downloads_count( $userid, $post_type = 'download' ) {
		if ( false === ( $count = get_transient( $userid . $post_type ) ) ) {
			global $wpdb;

			$where = get_posts_by_author_sql( $post_type, true, $userid );
			$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

			set_transient( $userid . $post_type, $count, 12 * HOUR_IN_SECONDS );
		}

		return apply_filters( 'get_usernumposts_' . $post_type, $count, $userid );
	}

}