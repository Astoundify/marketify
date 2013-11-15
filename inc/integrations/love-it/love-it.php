<?php
/**
 * Love It
 *
 * @package Marketify
 */

function marketify_li_display_love_links_on( $types ) {
	$types[] = 'download';

	return $types;
}
add_filter( 'li_display_love_links_on', 'marketify_li_display_love_links_on' );

function marketify_li_love_link() {
	echo li_love_link();
}
add_action( 'marketify_download_author_before', 'marketify_li_love_link' );
add_action( 'marketify_download_content_image_overlay', 'marketify_li_love_link' );