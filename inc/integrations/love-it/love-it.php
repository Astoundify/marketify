<?php
/**
 * Love It
 *
 * @package Marketify
 */

/**
 * Don't output the Love It link automatically anywhere
 */
function marketify_li_display_love_links_on( $types ) {
	return array( '__marketify__' );
}
add_filter( 'li_display_love_links_on', 'marketify_li_display_love_links_on' );

/**
 * Manually output the link where we want it
 */
function marketify_li_love_link() {
	global $post;

	if ( ! is_object( $post ) )
		return;

	if ( class_exists( 'Love_It_Pro' ) ) {
		echo lip_love_it_link( $post->ID, '', '' );
	} else {
		echo li_love_link();
	}
}
add_action( 'marketify_download_author_before', 'marketify_li_love_link' );
add_action( 'marketify_download_content_image_overlay_before', 'marketify_li_love_link' );

/**
 * Love It Archives
 *
 * @since Marketify 1.2
 */
class Marketify_Love_It_Archives {



	/**
	 * Redirect the wishlist page template to the current user's loves.
	 */
	public function redirect() {
		if ( ! is_page_template( 'page-templates/wishlist.php' ) )
			return;

		exit();
	}
}
add_action( 'init', array( 'Marketify_Love_It_Archives', 'init' ), 100 );