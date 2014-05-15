<?php
/**
 * Easy Digital Downloads - Frontend Form Submission
 *
 * @package Marketify
 */

function marketify_edd_fes_admin_notice() {
?>
	<div class="updated error">
       <p>A few things have changed in Frontend Submissions 2.2. Please <a href="http://docs.astoundify.com/marketify/#upgrading-to-fes-2-2-101">review the documentation</a> and take the necessary steps for migrating your content. &nbsp;&bull;&nbsp; <a href="<?php echo esc_url( add_query_arg( 'marketify_fes_nag_hide', '0', admin_url() ) ); ?>">Hide Notice</a></p>
    </div>
<?php
}
if ( version_compare( fes_plugin_version, '2.2', '>=' ) && ! get_user_meta( get_current_user_id(), 'marketify_fes_nag_hide' ) ) {
	add_action( 'admin_notices', 'marketify_edd_fes_admin_notice' );
}

function marketify_edd_fes_admin_notice_ignore() {
	$user_id = get_current_user_id();

	if ( isset( $_GET[ 'marketify_fes_nag_hide' ] ) && '0' == $_GET[ 'marketify_fes_nag_hide' ] ) {
		add_user_meta( $user_id, 'marketify_fes_nag_hide', 'true', true );
	}
}
add_action( 'admin_init', 'marketify_edd_fes_admin_notice_ignore');

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

function marketify_header_outer_image_fes( $background ) {
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
	$image = wp_get_attachment_image_src( $image );

	if ( is_array( $image ) ) {
		return $image;
	}

	return $background;
}
add_filter( 'marketify_header_outer_image', 'marketify_header_outer_image_fes', 1 );