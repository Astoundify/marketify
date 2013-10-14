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
	add_theme_support( 'post-formats', array( 'audio', 'video' ) );

	/** 
	 * Enable Post Formats for Downloads
	 */
	add_post_type_support( 'download', 'post-formats' );

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

function marketify_remove_post_formats() {
	 remove_post_type_support( 'post', 'post-formats' );
}
add_action( 'init', 'marketify_remove_post_formats' );

function marketify_before_shim() {
	global $post;

	if ( ! marketify_has_header_background() )
		return;

	$background = null;

	if ( has_post_thumbnail( $post->ID ) )
		$background = wp_get_attachment_image_src( get_post_thumbnail_id(), 'fullsize' );

	printf( '<div class="header-outer%2$s" style="background-image: url(%1$s);">', $background[0], $background ? ' custom-featured-image' : '' );
}
add_action( 'before', 'marketify_before_shim' );

function marketify_before_shim_css() {
	global $post;

	if ( ! marketify_has_header_background() )
		return;

	$background = wp_get_attachment_image_src( get_post_thumbnail_id() );

	if ( $background ) {
		wp_add_inline_style( 'marketify-base', '.site-header, .page-template-page-templateshome-php .page-header, .single-download .page-header { background-color: transparent; }' );
	}
}
add_action( 'wp_enqueue_scripts', 'marketify_before_shim_css', 11 );

function marketify_has_header_background() {
	global $post;

	return apply_filters( 'marketify_has_header_background', ( 
		( is_singular( 'download' ) && 'video' == get_post_format() ) || 
		is_singular( array( 'page', 'post' ) ) || 
		is_page_template( 'page-templates/home.php' )
	) );
}

function marketify_entry_page_title() {
	the_post();
?>

		<div class="entry-page-title container">
			<?php get_template_part( 'content', 'author' ); ?>

			<h1 class="entry-title"><?php the_title(); ?></h1>

			<?php
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
				if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
					$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';

				if ( is_singular( 'post' ) )
					printf( $time_string,
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_attr( get_the_modified_date( 'c' ) ),
						esc_html( get_the_modified_date() )
					);
			?>
		</div>

	</div><!-- .header-outer -->
<?php
	rewind_posts();
}
add_action( 'marketify_entry_before', 'marketify_entry_page_title' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function marketify_widgets_init() {
	register_widget( 'Marketify_Widget_Slider' );
	register_widget( 'Marketify_Widget_Recent_Downloads' );
	register_widget( 'Marketify_Widget_Featured_Popular_Downloads' );
	register_widget( 'Marketify_Widget_Download_Details' );
	register_widget( 'Marketify_Widget_Download_Share' );
	register_widget( 'Marketify_Widget_Price_Table' );
	register_widget( 'Marketify_Widget_Price_Option' );

	register_sidebar( array(
		'name'          => __( 'Sidebar', 'marketify' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title section-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	register_sidebar( array(
		'name'          => sprintf( __( '%s Archive Sidebar', 'marketify' ), edd_get_label_singular() ),
		'id'            => 'sidebar-download',
		'before_widget' => '<aside id="%1$s" class="widget download-archive-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="download-archive-widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => sprintf( __( '%s Single Sidebar', 'marketify' ), edd_get_label_singular() ),
		'id'            => 'sidebar-download-single',
		'before_widget' => '<aside id="%1$s" class="widget download-single-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="download-single-widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Homepage', 'marketify' ),
		'description'   => __( 'Widgets that appear on the "Homepage 1" Page Template', 'marketify' ),
		'id'            => 'home-1',
		'before_widget' => '<aside id="%1$s" class="home-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="home-widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	$the_sidebars = wp_get_sidebars_widgets();
	$count        = count( $the_sidebars[ 'footer-1' ] );
	$count        = floor( 12 / $count );

	register_sidebar( array(
		'name'          => __( 'Footer', 'marketify' ),
		'description'   => __( 'Widgets that appear in the page footer', 'marketify' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s col-md-' . $count . '">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-widget-title">',
		'after_title'   => '</h1>',
	) );


	$count = count( $the_sidebars[ 'widget-area-price-options' ] );
	$count = floor( 12 / $count );

	register_sidebar( array(
		'name'          => __( 'Price Table', 'marketify' ),
		'id'            => 'widget-area-price-options',
		'description'   => __( 'Drag multiple "Price Option" widgets here. Then drag the "Pricing Table" widget to the "Homepage" Widget Area.', 'marketify' ),
		'before_widget' => '<div id="%1$s" class="pricing-table-widget %2$s col-lg-' . $count . ' col-md-6">',
		'after_widget'  => '</div>'
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
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'marketify-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '20130916' );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '20130916' );
	wp_enqueue_script( 'marketify', get_template_directory_uri() . '/js/marketify.js', array( 'jquery' ), '20130916' );

	/** Misc Support */
	wp_dequeue_style( 'edd-software-specs' );
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

	$options = get_option( $widget[ 'classname' ] );
	$options = $options[ $widget[ 'params' ][0][ 'number' ] ];

	if ( 25 == $options[ 'size' ] ) 
		add_filter( 'woothemes_features_item_template', 'marketify_woothemes_features_item_template_mini', 10, 2 );
	else
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
	return '<div class="%%CLASS%% col-lg-4 col-sm-6 col-xs-12 feature-large">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
}

/**
 * 
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_features_item_template_mini( $template, $args ) {
	return '<div class="%%CLASS%% col-lg-3 col-md-4 col-sm-6 col-xs-12 feature-mini">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
}

/**
 * 
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_testimonials_item( $widget ) {
	if ( 'widget_woothemes_testimonials' != $widget[ 'classname' ] )
		return $widget;

	$options = get_option( $widget[ 'classname' ] );
	$options = $options[ $widget[ 'params' ][0][ 'number' ] ];

	if ( 1 == $options[ 'display_avatar' ] && null == $options[ 'display_author' ] ) {
		add_filter( 'woothemes_testimonials_item_template', 'marketify_woothemes_testimonials_item_template', 10, 2 );
	} else {
		add_filter( 'woothemes_testimonials_item_template', 'marketify_woothemes_testimonials_item_template_individual', 10, 2 );
	}

	return $widget;
}
add_action( 'dynamic_sidebar', 'marketify_woothemes_testimonials_item' );

/**
 * 
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_testimonials_item_template( $template, $args ) {
	return '<div class="%%CLASS%% company-testimonial">%%AVATAR%%</div>';
}

/**
 * 
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_testimonials_item_template_individual( $template, $args ) {
	return '<div id="quote-%%ID%%" class="%%CLASS%% individual-testimonial col-md-6 col-sm-12"><blockquote class="testimonials-text">%%TEXT%%</blockquote>%%AVATAR%% %%AUTHOR%%<div class="fix"></div></div>';
}

function marketify_download_author_before_zilla() {
	if ( function_exists( 'zilla_likes' ) ) 
		zilla_likes();
}
add_action( 'marketify_download_author_before', 'marketify_download_author_before_zilla' );

/**
 * EDD
 */
require get_template_directory() . '/inc/edd.php';

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
require get_template_directory() . '/inc/widgets/class-widget-download-details.php';
require get_template_directory() . '/inc/widgets/class-widget-download-share.php';
require get_template_directory() . '/inc/widgets/class-widget-price-option.php';
require get_template_directory() . '/inc/widgets/class-widget-price-table.php';