<?php
/**
 * Easy Digital Downloads
 *
 * @package Marketify
 */

/**
 * Set the "Download" labels based on the customizer values.
 *
 * @since Marketify 1.0
 *
 * @param array $name
 * @return array unknown
 */
function marketify_edd_default_downloads_name( $name ) {
	return array(
		'singular' => marketify_theme_mod( 'general', 'general-downloads-label-singular' ),
		'plural'   => marketify_theme_mod( 'general', 'general-downloads-label-plural' )
	);
}
add_filter( 'edd_default_downloads_name', 'marketify_edd_default_downloads_name' );

/**
 * Cart menu item
 *
 * @since Marketify 1.0
 *
 * @param string $items
 * @param object $args
 * @return string $items
 */
function marketify_wp_nav_menu_items( $items, $args ) {
	if ( 'primary' != $args->theme_location )
		return $items;

	ob_start();

	$widget_args = array(
		'before_widget' => '',
		'after_widget'  => '',
		'before_title'  => '',
		'after_title'   => ''
	);

	$widget = the_widget( 'edd_cart_widget', array( 'title' => '' ), $widget_args );

	$widget = ob_get_clean();

	$link = sprintf( '<li class="current-cart"><a href="%s"><i class="icon-basket"></i> <span class="edd-cart-quantity">%d</span></a><ul class="sub-menu nav-menu"><li class="widget">%s</li></ul></li>', get_permalink( edd_get_option( 'purchase_page' ) ), edd_get_cart_quantity(), $widget );

	return $link . $items;
}
add_filter( 'wp_nav_menu_items', 'marketify_wp_nav_menu_items', 10, 2 );

/**
 * EDD Download wrapper class.
 *
 * When using the [downloads] shortcode, add our own class to the wrapper.
 *
 * @since Marketify 1.0
 *
 * @param string $class
 * @return string The updated class list
 */
function marketify_edd_downloads_list_wrapper_class( $class ) {
	return 'row ' . $class;
}
add_filter( 'edd_downloads_list_wrapper_class', 'marketify_edd_downloads_list_wrapper_class' );

/**
 * EDD Download Class
 *
 * When using the [downloads] shortcode, add our own class to match
 * our awesome styling.
 *
 * @since Marketify 1.0
 *
 * @param string $class
 * @param string $id
 * @param array $atts
 * @return string The updated class list
 */
function marketify_edd_download_class( $class, $id, $atts ) {
	if ( ! isset( $atts[ 'columns' ] ) )
		$atts[ 'columns' ] = 3;

	if ( 4 == $atts[ 'columns' ] )
		$cols = 3;
	elseif ( 3 == $atts[ 'columns' ] )
		$cols = 4;
	elseif ( 2 == $atts[ 'columns' ] )
		$cols = 6;
	else
		$cols = 12;

	return $class . sprintf( ' content-grid-download col-lg-%d col-md-6 col-sm-6 col-xs-12', $cols );
}
add_filter( 'edd_download_class', 'marketify_edd_download_class', 10, 3 );

/**
 * EDD Download Shortcode Attributes
 *
 * @since Marketify 1.0
 *
 * @param array $atts
 * @return array $atts
 */
function marketify_shortcode_atts_downloads( $atts ) {
	$atts[ 'excerpt' ]      = 'no';
	$atts[ 'full_content' ] = 'no';
	$atts[ 'price' ]        = 'no';
	$atts[ 'buy_button' ]   = 'no';

	return $atts;
}
add_filter( 'shortcode_atts_downloads', 'marketify_shortcode_atts_downloads' );

/**
 * Add standard comments to the Downloads post type.
 *
 * @since Marketify 1.0
 *
 * @param array $supports
 * @return array $supports
 */
function marketify_edd_product_supports( $supports ) {
	$supports[] = 'comments';

	return $supports;
}
add_filter( 'edd_download_supports', 'marketify_edd_product_supports' );

/**
 * Add an extra class to the purchase form if the download has
 * variable pricing. There is no filter for the class, so we have to hunt.
 *
 * @since Marketify 1.0
 *
 * @param string $purchase_form
 * @param array $args
 * @return string $purchase_form
 */
function marketify_edd_purchase_download_form( $purchase_form, $args ) {
	$download_id = $args[ 'download_id' ];

	if ( ! $download_id )
		return $purchase_form;

	if ( ! edd_has_variable_prices( $download_id ) )
		return $purchase_form;

	$purchase_form = str_replace( 'class="edd_download_purchase_form"', 'class="edd_download_purchase_form download-variable"', $purchase_form );

	return $purchase_form;
}
add_filter( 'edd_purchase_download_form', 'marketify_edd_purchase_download_form', 10, 2 );

/**
 * Make sure the only available color is the one we want (inherit)
 *
 * @since Marketify 1.0
 *
 * @param array $colors
 * @return array $colors
 */
function marketify_edd_button_colors( $colors ) {
	$unset = array( 'white', 'blue', 'gray', 'red', 'green', 'yellow', 'orange', 'dark-gray' );

	foreach ( $unset as $color ) {
		unset( $colors[ $color ] );
	}

	return $colors;
}
add_filter( 'edd_button_colors', 'marketify_edd_button_colors' );

/**
 * Login redirect
 */
function marketify_shortcode_atts_edd_login( $atts ) {
	$atts[ 'redirect' ] = apply_filters( 'marketify_edd_force_login_redirect', site_url() );

	return $atts;
}
add_filter( 'shortcode_atts_edd_login', 'marketify_shortcode_atts_edd_login' );

/**
 * Extra metaboxes if FES is not active.
 */
class Marketify_EDD_Metabox {

	/**
     * @var $instance
     */
	public static $instance;

	/*
	 * Init so we can attach to an action
	 */
	public static function init() {
		// If we are using FES, assume the field is added through that
		if ( class_exists( 'EDD_Front_End_Submissions' ) )
			return;

		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Setup actions
	 */
	public function __construct() {
		add_action( 'add_meta_boxes',          array( $this, 'add_meta_boxes' ) );
		add_action( 'edd_metabox_fields_save', array( $this, 'save_post' ) );

		add_filter( 'marketify_video_field',   array( $this, 'video_field' ) );
		add_filter( 'marketify_demo_field',    array( $this, 'demo_field' ) );
	}

	/**
	 * Add meta box
	 */
	public function add_meta_boxes() {
		add_meta_box( 'edd-marketify-video', sprintf( __( '%s Video URL', 'marketify' ), edd_get_label_singular() ), array( $this, 'meta_box_video' ), 'download', 'normal', 'high' );

		add_meta_box( 'edd-marketify-demo', sprintf( __( '%s Demo URL', 'marketify' ), edd_get_label_singular() ), array( $this, 'meta_box_demo' ), 'download', 'normal', 'high' );
	}

	/**
	 * Output video meta box
	 */
	public function meta_box_video() {
		global $post;

		echo EDD()->html->text( array(
			'name'  => 'edd_video',
			'value' => esc_url( $post->edd_video ),
			'class' => 'large-text'
		) );
	}

	/**
	 * Output demo meta box
	 */
	public function meta_box_demo() {
		global $post;

		echo EDD()->html->text( array(
			'name'  => 'edd_demo',
			'value' => esc_url( $post->edd_demo ),
			'class' => 'large-text'
		) );
	}

	/**
	 * Save meta box
	 */
	public function save_post( $fields ) {
		$fields[] = 'edd_video';
		$fields[] = 'edd_demo';

		return $fields;
	}

	/**
	 * Filter the video field that is searched for the URL.
	 */
	public function video_field() {
		return 'edd_video';
	}

	/**
	 * Filter the demo field that is searched for the URL.
	 */
	public function demo_field() {
		return 'edd_demo';
	}

}
add_action( 'init', array( 'Marketify_EDD_Metabox', 'init' ) );