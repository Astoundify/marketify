<?php
/**
 * Easy Digital Downloads - Frontend Form Submission
 *
 * @package Marketify
 */

add_filter( 'edd_fes_vendor_archive_switch', '__return_false' );

function marketify_edd_fes_vendor_archive_url( $url, $user ) {
	return Marketify_Author::url( 'downloads', $user->ID );
}
add_filter( 'edd_fes_vendor_archive_url', 'marketify_edd_fes_vendor_archive_url', 10, 2 );