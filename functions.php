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

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'marketify' ),
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
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'marketify' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Downloads Sidebar', 'marketify' ),
		'id'            => 'sidebar-download',
		'before_widget' => '<aside id="%1$s" class="download-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="download-widget-title">',
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
}
add_action( 'widgets_init', 'marketify_widgets_init' );

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
			$font_families[] = 'Roboto Slab:300';

		if ( 'off' !== $montserrat )
			$font_families[] = 'Montserrat:400';

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
	wp_enqueue_style( 'marketify-fonts', marketify_fonts_url() );
	wp_enqueue_style( 'marketify-style', get_stylesheet_uri() );

	wp_enqueue_script( 'marketify-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'marketify-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'marketify-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
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
			'role' => 'shop_vendor',
			'count_total' => true
		) );

		$total = count( $vendors );
		$is_multi_vendor = $total > 0 ? true : false;

		if ( $vendors > 0 )
			set_transient( 'marketify_is_multi_vendor', $is_multi_vendor );
	}

	if ( $vendors > 0 )
		return true;
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
