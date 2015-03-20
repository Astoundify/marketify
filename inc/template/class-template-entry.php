<?php

class Marketify_Template_Entry {

	public function __construct() {
		apply_filters( 'marketify_entry_author_social', array( $this, 'author_social' ) );
	}

	function author_social( $user_id = null ) {
		global $post;

		$methods = _wp_get_user_contactmethods();
		$social  = array();

		if ( ! $user_id )
			$user_id = get_the_author_meta( 'ID' );

		foreach ( $methods as $key => $method ) {
			$field = get_the_author_meta( $key, $user_id );

			if ( ! $field )
				continue;

			$social[ $key ] = sprintf( '<a href="%1$s" target="_blank"><i class="icon-%2$s"></i></a>', $field, $key );
		}

		$social = implode( ' ', $social );

		return $social;
	}

}
