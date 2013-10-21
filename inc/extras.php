<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Marketify
 */

/**
 * Add extra contact fields to the user profile. This information
 * is used in the author info and byline on entries.
 *
 * @since Marketify 1.0
 *
 * @param array $methods Existing contact methods
 * @param object $user The current user that is being edited
 * @return array $methods The modified contact methods
 */
function marketify_user_contactmethods( $methods, $user ) {
	unset( $methods[ 'aim' ] );
	unset( $methods[ 'yim' ] );
	unset( $methods[ 'jabber' ] );

	$methods[ 'twitter' ]  = 'Twitter';
	$methods[ 'facebook' ] = 'Facebook';
	$methods[ 'gplus' ]    = 'Google+';

	return $methods;
}
add_filter( 'user_contactmethods', 'marketify_user_contactmethods', 10, 2 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function marketify_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'marketify_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 */
function marketify_body_classes( $classes ) {
	global $wp_query;

	// Adds a class of group-blog to blogs with more than 1 published author
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_page_template( 'page-templates/home.php' ) )
		$classes[] = 'home-1';

	if ( is_page_template( 'page-templates/minimal.php' ) )
		$classes[] = 'minimal';

	if ( get_query_var( 'author_ptype' ) )
		$classes[] = 'archive-download';

	return $classes;
}
add_filter( 'body_class', 'marketify_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 */
function marketify_post_classes( $classes ) {
	global $wp_query, $post;

	if ( is_singular( 'download' ) && edd_has_variable_prices( $post->ID ) )
		$classes[] = 'download-variable';

	return $classes;
}
add_filter( 'post_class', 'marketify_post_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function marketify_enhanced_image_navigation( $url, $id ) {
	if ( ! is_attachment() && ! wp_attachment_is_image( $id ) )
		return $url;

	$image = get_post( $id );
	if ( ! empty( $image->post_parent ) && $image->post_parent != $id )
		$url .= '#main';

	return $url;
}
add_filter( 'attachment_link', 'marketify_enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function marketify_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'marketify' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'marketify_wp_title', 10, 2 );

/**
 * Remove ellipsis from the excerpt
 */
add_filter( 'excerpt_more', '__return_false' );