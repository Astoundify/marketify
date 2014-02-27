<?php
/**
 * Easy Digital Downloads - Frontend Form Submission
 *
 * @package Marketify
 */

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
 * Remove the FES vendor/author archive URL redirection.
 */
add_filter( 'edd_fes_vendor_archive_switch', '__return_false' );

/**
 * Set our own vendor archive URL
 *
 * @since Marketify 1.0
 *
 * @param string $url
 * @param object $user
 * @return string uknown
 */
function marketify_edd_fes_vendor_archive_url( $url, $user ) {
	return Marketify_Author::url( 'downloads', $user->ID );
}
add_filter( 'edd_fes_vendor_archive_url', 'marketify_edd_fes_vendor_archive_url', 10, 2 );

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
	if ( EDD_FES()->vendors->is_commissions_active() ) {
		$menu[ 'earnings' ][ 'icon' ] = 'chart-line';
	}

	$menu[ 'logout' ][ 'icon' ] = 'logout';

	return $menu;
}
add_filter( 'edd_fes_vendor_dashboard_menu', 'marketify_edd_fes_vendor_dashboard_menu' );