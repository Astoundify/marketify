<?php
/**
 * Easy Digital Downloads - Frontend Form Submission
 *
 * @package Marketify
 */

function marketify_edd_fes_author_url( $author = null ) {
	if ( ! $author ) {
		$author = wp_get_current_user();
	} else {
		$author = new WP_User( $author );
	}

	global $wp_rewrite;

	if ( $wp_rewrite->permalink_structure == '' ) {
		$vendor_url = add_query_arg( 'vendor', $author->user_nicename, get_permalink( EDD_FES()->helper->get_option( 'fes-vendor-page', false ) ) );
	} else {
		$vendor_url = trailingslashit( get_permalink( EDD_FES()->helper->get_option( 'fes-vendor-page', false ) ) ) . trailingslashit( $author->user_nicename );
	}

	$vendor_url = apply_filters( 'fes_vendor_archive_url', $vendor_url, $author );

	return $vendor_url;
}

/**
 * Change the reCAPTCHA color scheme.
 *
 * @since Marketify 1.2.0
 */
function marketify_edd_fes_recaptcha() {
?>
	<script type="text/javascript">
		var RecaptchaOptions = {
			theme : 'clean',
		};
	</script>
<?php
}
add_action( 'wp_head', 'marketify_edd_fes_recaptcha' );

/**
 * Remove FES Styles
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_edd_fes_enqueue_scripts() {
	wp_dequeue_style( 'fes-css' );
}
add_action( 'wp_enqueue_scripts', 'marketify_edd_fes_enqueue_scripts' );

/**
 * FES Vendor Dashboard Menu
 *
 * @since Marketify 1.0
 *
 * @param array $menu
 * @return array $menu
 */
function marketify_edd_fes_vendor_dashboard_menu( $menu ) {
	if ( EDD_FES()->integrations->is_commissions_active() ) {
		$menu[ 'earnings' ][ 'icon' ] = 'chart-line';
	}

	$menu[ 'orders' ][ 'icon' ] = 'ticket';
	$menu[ 'logout' ][ 'icon' ] = 'logout';

	return $menu;
}
add_filter( 'fes_vendor_dashboard_menu', 'marketify_edd_fes_vendor_dashboard_menu' );

function marketify_count_user_downloads( $userid, $post_type = 'download' ) {
	global $wpdb;

	$where = get_posts_by_author_sql( $post_type, true, $userid );

	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );

  	return apply_filters( 'get_usernumposts', $count, $userid );
}