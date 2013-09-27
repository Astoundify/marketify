<?php
/**
 * Marketify functions and definitions
 *
 * @package Marketify
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'marketify_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function marketify_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Marketify, use a find and replace
	 * to change 'marketify' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'marketify', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'content-grid-download', 740, 600, true );
	add_image_size( 'content-single-download', 9999, 300, true );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'marketify' ),
		'social'  => __( 'Footer Social', 'marketify' )
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'marketify_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // marketify_setup
add_action( 'after_setup_theme', 'marketify_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function marketify_widgets_init() {
	register_widget( 'Marketify_Widget_Slider' );
	register_widget( 'Marketify_Widget_Recent_Downloads' );
	register_widget( 'Marketify_Widget_Featured_Popular_Downloads' );

	register_sidebar( array(
		'name'          => __( 'Sidebar', 'marketify' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Download Archive Sidebar', 'marketify' ),
		'id'            => 'sidebar-download',
		'before_widget' => '<aside id="%1$s" class="widget download-archive-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="download-archive-widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Download Single Sidebar', 'marketify' ),
		'id'            => 'sidebar-download-single',
		'before_widget' => '<aside id="%1$s" class="widget download-single-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="download-single-widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage 1', 'marketify' ),
		'description'   => __( 'Widgets that appear on the "Homepage 1" Page Template', 'marketify' ),
		'id'            => 'home-1',
		'before_widget' => '<aside id="%1$s" class="home-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="home-widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer', 'marketify' ),
		'description'   => __( 'Widgets that appear in the page footer', 'marketify' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'marketify_widgets_init' );

function marketify_dynamic_sidebar_params( $params ) {
	if ( 'footer-1' !== $params[0][ 'id' ] )
		return $params;
	
	$the_sidebars = wp_get_sidebars_widgets();
	$count        = count( $the_sidebars[ 'footer-1' ] );
	$count        = 12 / $count;

	$params[0][ 'before_widget' ] = str_replace( '">', ' col-md-' . $count . '">', $params[0][ 'before_widget' ] );
	
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'marketify_dynamic_sidebar_params' );

/**
 * Returns the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Varela Round by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since Marketify 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function marketify_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	 * supported by Source Sans Pro, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'marketify' );

	/* Translators: If there are characters in your language that are not
	 * supported by Roboto Slab, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$roboto = _x( 'on', 'Roboto Slab font: on or off', 'marketify' );

	/* Translators: If there are characters in your language that are not
	 * supported by Montserrat, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$montserrat = _x( 'on', 'Montserrat font: on or off', 'marketify' );

	/* Translators: If there are characters in your language that are not
	 * supported by Pacifico, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$pacifico = _x( 'on', 'Pacifico font: on or off', 'marketify' );

	if ( 'off' !== $source_sans_pro || 'off' !== $roboto || 'off' !== $montserrat ) {
		$font_families = array();

		if ( 'off' !== $source_sans_pro )
			$font_families[] = 'Source Sans Pro:300,400,700,300italic,400italic,700italic';

		if ( 'off' !== $roboto )
			$font_families[] = 'Roboto Slab:300,400';

		if ( 'off' !== $montserrat )
			$font_families[] = 'Montserrat:400,800';

		if ( 'off' !== $pacifico )
			$font_families[] = 'Pacifico';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue scripts and styles
 */
function marketify_scripts() {
	wp_enqueue_style( 'entypo', get_template_directory_uri() . '/css/entypo.css' );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css' );

	wp_enqueue_style( 'marketify-fonts', marketify_fonts_url() );
	wp_enqueue_style( 'marketify-grid', get_template_directory_uri() . '/css/bootstrap.css' );
	wp_enqueue_style( 'marketify-base', get_stylesheet_uri() );
	wp_enqueue_style( 'marketify-responsive', get_template_directory_uri() . '/css/responsive.css' );

	wp_enqueue_script( 'marketify-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'marketify-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'marketify-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '20130916' );
	wp_enqueue_script( 'marketify', get_template_directory_uri() . '/js/marketify.js', array( 'jquery' ), '20130916' );
}
add_action( 'wp_enqueue_scripts', 'marketify_scripts' );

/**
 * Check if we are a standard Easy Digital Download install,
 * or a multi-vendor marketplace.
 *
 * @since Marketify 1.0
 *
 * @return boolean
 */
function marketify_is_multi_vendor() {
	if ( ! class_exists( 'Easy_Digital_Downloads' ) )
		return false;

	if ( false === ( $is_multi_vendor = get_transient( 'marketify_is_multi_vendor' ) ) ) {
		$vendors = get_users( array(
			'role' => 'shop_vendor'
		) );

		print_r( $vendors );

		$total = count( $vendors );
		$is_multi_vendor = $total > 0 ? true : false;

		set_transient( 'marketify_is_multi_vendor', $is_multi_vendor );
	}

	return $is_multi_vendor;
}

/**
 * When a user is updated, or created, clear the multi vendor
 * cache check.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function __marketify_clear_multi_vendor_cache() {
	delete_transient( 'marketify_is_multi_vendor' );
}
add_action( 'profile_update', '__marketify_clear_multi_vendor_cache' );
add_action( 'user_register',  '__marketify_clear_multi_vendor_cache' );

/**
 * 
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_features_item( $widget ) {
	if ( 'widget_woothemes_features' != $widget[ 'classname' ] )
		return $widget;

	add_filter( 'woothemes_features_item_template', 'marketify_woothemes_features_item_template', 10, 2 );
}
add_action( 'dynamic_sidebar', 'marketify_woothemes_features_item' );

/**
 * 
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_features_item_template( $template, $args ) {
	return '<div class="%%CLASS%% col-lg-4 col-sm-6 col-xs-12">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load Widgets
 */
require get_template_directory() . '/inc/class-widget.php';
require get_template_directory() . '/inc/widgets/class-widget-slider.php';
require get_template_directory() . '/inc/widgets/class-widget-downloads-recent.php';
require get_template_directory() . '/inc/widgets/class-widget-featured-popular.php';