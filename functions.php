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
	$content_width = 750; /* pixels */

if ( ! function_exists( 'marketify_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Marketify 1.0
 *
 * @return void
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

	add_image_size( 
		'content-grid-download', 
		apply_filters( 'marketify_image_content_grid_download_w', 740  ),
		apply_filters( 'marketify_image_content_grid_download_h', 600  ), 
		apply_filters( 'marketify_image_content_grid_download_c', true )
	);

	add_image_size( 
		'content-single-download', 
		apply_filters( 'marketify_image_content_single_download_w', 9999 ),
		apply_filters( 'marketify_image_content_single_download_h', 400  ), 
		apply_filters( 'marketify_image_content_single_download_c', true )
	);

	if (class_exists('MultiPostThumbnails')) {
		new MultiPostThumbnails(
			array(
				'label'     => __( 'Grid Image', 'marketify' ),
				'id'        => 'grid-image',
				'post_type' => 'download'
			)
		);
	}

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
	 * Editor Style
	 */
	add_editor_style( 'css/editor-style.css' );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'marketify_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'marketify_setup' );

/**
 * Check if EDD is active
 *
 * @since Marketify 1.0
 *
 * @return boolean
 */
function marketify_is_edd() {
	return class_exists( 'Easy_Digital_Downloads' );
}

/**
 * Check if we are using bbPress
 *
 * @since Marketify 1.0
 *
 * @return boolean
 */
function marketify_is_bbpress() {
	if ( ! function_exists( 'is_bbpress' ) )
		return false; 

	return is_bbpress();
}

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
		$vendors = get_users( apply_filters( 'marketify_is_multi_vendor_check', array() ) );

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
 * Remove Post Formats from Posts
 *
 * Since `add_theme_support( 'post-formats' )` cant specify a post type, we need
 * to remove the formats from the standard post type as we just want downloads.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_remove_post_formats() {
	 remove_post_type_support( 'post', 'post-formats' );
}
add_action( 'init', 'marketify_remove_post_formats' );

/**
 * Hip Header Start
 *
 * If the current page qualifies, add a wrapping div above everything else
 * in order to create full width header backgrounds that cover the main
 * header as well as extend below the page title.
 *
 * @since Marketify 1.0
 *
 * @return mixed
 */
function marketify_before_shim() {
	global $post;

	if ( ! $background = marketify_has_header_background() )
		return;

	printf( '<div class="header-outer%2$s" style="background-image: url(%1$s);">', $background[0], is_array( $background ) ? ' custom-featured-image' : '' );
}
add_action( 'before', 'marketify_before_shim' );

/**
 * Hip Header End
 *
 * If the current page qualifies, end the hip header.
 *
 * @since Marketify 1.0
 *
 * @return mixed
 */
function marketify_entry_header_background_end() {
	if ( ! marketify_has_header_background() )
		return;

	echo '</div><!-- .header-outer -->';
}
add_action( 'marketify_entry_before', 'marketify_entry_header_background_end', 100 );

/**
 * Hip Header CSS
 *
 * If the current page qualifies, add extra CSS so the hip header
 * background shines through.
 *
 * @since Marketify 1.0
 *
 * @return mixed
 */
function marketify_before_shim_css() {
	global $post;

	if ( ! marketify_has_header_background() )
		return;

	wp_add_inline_style( 'marketify-base', '.site-header, .page-header { background-color: transparent; }' );
}
add_action( 'wp_enqueue_scripts', 'marketify_before_shim_css', 11 );

/**
 * Hip Header Qualification
 *
 * @since Marketify 1.0
 *
 * @return mixed boolean|string False if not qualified or no header, URL to image if one exists.
 */
function marketify_has_header_background() {
	global $post;

	$_post = $post;

	$is_correct = apply_filters( 'marketify_has_header_background', ( 
		marketify_is_bbpress() || 
		( is_singular( 'download' ) && 'video' == get_post_format() ) || 
		is_singular( array( 'page', 'post' ) ) || 
		is_page_template( 'page-templates/home.php' ) ||
		is_home()
	) );

	if ( ! $is_correct )
		return false;

	if ( is_home() ) {
		$post = get_post( get_option( 'page_for_posts' ) );
	}

	$background = apply_filters( 'marketify_has_header_background_force', is_singular( array( 'post', 'page' ) ) || marketify_is_bbpress() ) ? true : false;

	if ( has_post_thumbnail( $post->ID ) )
		$background = wp_get_attachment_image_src( get_post_thumbnail_id(), 'fullsize' );

	$post = $_post;

	return $background;
}

/**
 * On posts and pages, add extra header information.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_entry_page_title() {
	if ( ! is_singular( array( 'post', 'page' ) ) && ! marketify_is_bbpress() )
		return;

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
<?php
	rewind_posts();
}
add_action( 'marketify_entry_before', 'marketify_entry_page_title' );

/**
 * Sidebars and Widgets
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_widgets_init() {
	register_widget( 'Marketify_Widget_Slider' );
	register_widget( 'Marketify_Widget_Price_Table' );
	register_widget( 'Marketify_Widget_Price_Option' );

	if ( marketify_is_edd() ) {
		register_widget( 'Marketify_Widget_Recent_Downloads' );
		register_widget( 'Marketify_Widget_Featured_Popular_Downloads' );
		register_widget( 'Marketify_Widget_Download_Details' );
		register_widget( 'Marketify_Widget_Download_Share' );

		if ( class_exists( 'EDD_Reviews' ) ) {
			register_widget( 'Marketify_Widget_Download_Review_Details' );
		}
	}

	if ( function_exists( 'soliloquy_slider' ) ) {
		register_widget( 'Marketify_Widget_Slider_Soliloquy' );
	}

	/* Custom Homepage */
	register_sidebar( array(
		'name'          => __( 'Homepage', 'marketify' ),
		'description'   => __( 'Widgets that appear on the "Homepage 1" Page Template', 'marketify' ),
		'id'            => 'home-1',
		'before_widget' => '<aside id="%1$s" class="home-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="home-widget-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	/* Standard Sidebar */
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'marketify' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title section-title"><span>',
		'after_title'   => '</span></h1>',
	) );

	if ( marketify_is_edd() ) {
		/* Download Achive (archive-download.php) */
		register_sidebar( array(
			'name'          => sprintf( __( '%s Archive Sidebar', 'marketify' ), edd_get_label_singular() ),
			'id'            => 'sidebar-download',
			'before_widget' => '<aside id="%1$s" class="widget download-archive-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-archive-widget-title">',
			'after_title'   => '</h1>',
		) );

		/* Download Single (single-download.php) */
		register_sidebar( array(
			'name'          => sprintf( __( '%s Single Sidebar', 'marketify' ), edd_get_label_singular() ),
			'id'            => 'sidebar-download-single',
			'before_widget' => '<aside id="%1$s" class="widget download-single-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-single-widget-title">',
			'after_title'   => '</h1>',
		) );

		/* Download Single Comments/Reviews (single-download.php) */
		register_sidebar( array(
			'name'          => sprintf( __( '%s Single Comments Sidebar', 'marketify' ), edd_get_label_singular() ),
			'id'            => 'sidebar-download-single-comments',
			'before_widget' => '<aside id="%1$s" class="widget download-single-widget comments %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-single-widget-title">',
			'after_title'   => '</h1>',
		) );
	}

	/*
	 * Figure out how many columns the footer has
	 */
	$the_sidebars = wp_get_sidebars_widgets();
	$footer       = isset ( $the_sidebars[ 'footer-1' ] ) ? $the_sidebars[ 'footer-1' ] : array();
	$count        = count( $footer );
	$count        = floor( 12 / ( $count == 0 ? 1 : $count ) );

	/* Footer */
	register_sidebar( array(
		'name'          => __( 'Footer', 'marketify' ),
		'description'   => __( 'Widgets that appear in the page footer', 'marketify' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s col-md-' . $count . '">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="footer-widget-title">',
		'after_title'   => '</h1>',
	) );

	/*
	 * Figure out how many columns the price table has
	 */
	$prices = isset ( $the_sidebars[ 'widget-area-price-options' ] ) ? $the_sidebars[ 'widget-area-price-options' ] : array();
	$count = count( $prices );
	$count = floor( 12 / ( $count == 0 ? 1 : $count ) );

	/* Price Table */
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
			$font_families[] = apply_filters( 'marketify_font_source_sans', 'Source Sans Pro:300,400,700,300italic,400italic,700italic' );

		if ( 'off' !== $roboto )
			$font_families[] = apply_filters( 'marketify_font_roboto', 'Roboto Slab:300,400' );

		if ( 'off' !== $montserrat )
			$font_families[] = apply_filters( 'marketify_font_montserrat', 'Montserrat:400,800' );

		if ( 'off' !== $pacifico )
			$font_families[] = apply_filters( 'marketify_font_pacifico', 'Pacifico' );

		$query_args = array(
			'family' => urlencode( implode( '|', apply_filters( 'marketify_font_families', $font_families ) ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Load fonts in TinyMCE
 *
 * @since Marketify 1.0
 *
 * @return string $css
 */
function marketify_mce_css( $css ) {
	$css .= ', ' . marketify_fonts_url();

	return $css;
}
add_filter( 'mce_css', 'marketify_mce_css' );

/**
 * Scripts and Styles
 *
 * Load Styles and Scripts depending on certain conditions. Not all assets
 * will be loaded on every page.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_scripts() {
	/*
	 * Styles
	 */

	/* Supplimentary CSS */
	wp_enqueue_style( 'entypo', get_template_directory_uri() . '/css/entypo.css' );
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css' );
	wp_enqueue_style( 'marketify-fonts', marketify_fonts_url() );
	wp_enqueue_style( 'marketify-grid', get_template_directory_uri() . '/css/bootstrap.css' );

	/* Custom CSS */
	wp_enqueue_style( 'marketify-base', get_stylesheet_uri() );
	
	/*
	 * Scripts
	 */

	/* Comments */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/* Supplimentary Scripts */
	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '20130916' );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array( 'jquery' ), '20130916' );

	/** Custom JS */
	wp_enqueue_script( 'marketify', get_template_directory_uri() . '/js/marketify.js', array( 'jquery' ), '20130916' );

	$marketify_js_settings = apply_filters( 'marketify_jsparams', array(
		'widgets' => array()
	) );

	/*
	 * Pass all widget settings to the JS so we can customize things
	 */
	global $wp_registered_widgets;

	$widgetized = wp_get_sidebars_widgets();
	$widgets    = $widgetized[ 'home-1' ];

	foreach ( $widgets as $widget ) {
		$widget_obj = $wp_registered_widgets[ $widget ];
		$prefix     = substr( $widget_obj[ 'classname' ], 0, 7 ) == 'widget_' ? '' : 'widget_';
		$settings   = get_option( $prefix . $widget_obj[ 'classname' ] );

		if ( ! $settings )
			continue;

		$params = $settings[ $widget_obj[ 'params' ][0][ 'number' ] ];

		$marketify_js_settings[ 'widgets' ][ $widget ] = array(
			'cb'       => $widget_obj[ 'classname' ],
			'settings' => $params
		);

		// Suppliment stuff. Should probably be added to a hook
		if ( 'widget_woothemes_testimonials' == $widget_obj[ 'classname' ] && isset ( $params[ 'display_author' ] ) ) {
			$marketify_js_settings[ 'widgets' ][ $widget ][ 'settings' ][ 'speed' ] = apply_filters( $widget_obj[ 'classname' ] . '_scroll', 5000 );
		}
	}

	wp_localize_script( 'marketify', 'marketifySettings', $marketify_js_settings );

	/** Misc Support */
	wp_dequeue_style( 'edd-software-specs' );
}
add_action( 'wp_enqueue_scripts', 'marketify_scripts' );

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
 * Download Authors
 *
 * Since WordPres only supports author archives of a singular (or all) post
 * types, we need to create a way to just view an author's downloads.
 *
 * This sets up a new query variable called `author_ptype` which can be used
 * to fetch a specific post type when viewing author archives.
 *
 * @since Marketify 1.0
 */
class Marketify_Author {

	/**
     * @var $instance
     */
	public static $instance;

	/*
	 * Init so we can attach to an action
	 */
	public static function init() {
		if ( ! isset ( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/*
	 * Hooks and Filters
	 */
	public function __construct() {
		add_filter( 'query_vars', array( $this, 'query_vars' ) ); 
		add_filter( 'generate_rewrite_rules', array( $this, 'rewrites' ) );

		add_action( 'pre_get_posts', array( $this, 'show_downloads' ) );
		add_action( 'template_redirect', array( $this, 'redirect' ) );
	}

	/*
	 * Create a publically accessible link
	 */
	public static function url( $where = 'downloads', $user_id = null ) {
		if ( $user_id )
			$user = new WP_User( $user_id );
		else
			$user = wp_get_current_user();

		return esc_url( get_author_posts_url( $user->ID ) . trailingslashit( $where ) ); 
	}

	/**
	 * Register the `author_ptype` query variable.
	 *
	 * @since Marketify 1.0
	 *
	 * @param array $query_vars Existing query variables
	 * @return array $query_vars Modified array of query variables
	 */
	public function query_vars( $query_vars ) {
		$query_vars[] = 'author_downloads';
		$query_vars[] = 'author_wishlist';

		return $query_vars;
	}

	/**
	 * Create the new permalink endpoints/structures.
	 *
	 * @since Marketify 1.0
	 *
	 * @return object $wp_rewrite
	 */
	public function rewrites() {
		global $wp_rewrite;

		$new_rules = array(
			'author/([^/]+)/downloads/?$' => 'index.php?author_name=' . $wp_rewrite->preg_index(1) . '&author_downloads=1',
			'author/([^/]+)/wishlist/?$' => 'index.php?author_name=' . $wp_rewrite->preg_index(1) . '&author_wishlist=1',
			'author/([^/]+)/downloads/page/?([0-9]{1,})/?$' => 'index.php?author_name=' . $wp_rewrite->preg_index(1) . '&author_downloads=1&paged=' . $wp_rewrite->preg_index( 2 ),
			'author/([^/]+)/wishlist/page/?([0-9]{1,})/?$' => 'index.php?author_name=' . $wp_rewrite->preg_index(1) . '&author_wishlist=1&paged=' . $wp_rewrite->preg_index( 2 )
		);

		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

		return $wp_rewrite->rules;
	}

	/**
	 * Show author products
	 *
	 * @since Marketify 1.0
	 *
	 * @param object $query Current WP_Query
	 * @return void
	 */
	public function show_downloads( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_author() )
			return;

		if ( ! ( get_query_var( 'author_downloads' ) || get_query_var( 'author_wishlist' ) ) )
			return;
		
		$query->is_author = true;
		$query->set( 'post_type', 'download' );

		if ( ! get_query_var( 'author_wishlist' ) )
			return;

		$author = get_user_by( 'slug', $query->query[ 'author_name' ] );
		$loves  = get_user_option( 'li_user_loves', $author->ID );

		$query->set( 'post__in', $loves );
	}

	public function redirect() {
		if ( ! is_page_template( 'page-templates/wishlist.php' ) )
			return;

		wp_safe_redirect( $this->url( 'wishlist' ) );
		exit();
	}
}
add_action( 'init', array( 'Marketify_Author', 'init' ), 100 );

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
 * Integrations
 */

// Jetpack
require get_template_directory() . '/inc/integrations/jetpack/jetpack.php';

// bbPress
if ( class_exists( 'bbPress' ) ) {
	require get_template_directory() . '/inc/integrations/bbpress/bbpress.php';
}

// Easy Digital Downloads
if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	require get_template_directory() . '/inc/integrations/edd/edd.php';

	if ( defined( 'EDD_CSAU_DIR' ) ) {
		require get_template_directory() . '/inc/integrations/edd-csau/csau.php';
	}

	if ( class_exists( 'EDD_Reviews' ) ) {
		require get_template_directory() . '/inc/integrations/edd-reviews/reviews.php';
	}

	if ( class_exists( 'EDDRecommendedDownloads' ) ) {
		require get_template_directory() . '/inc/integrations/edd-recommended/recommended.php';
	}
}

// WooThemes Features
if ( class_exists( 'Woothemes_Features' ) ) {
	require get_template_directory() . '/inc/integrations/woo-features/features.php';
}

// WooThemes Testimonials
if ( class_exists( 'Woothemes_Testimonials' ) ) {
	require get_template_directory() . '/inc/integrations/woo-testimonials/testimonials.php';
}

// Love It
if ( defined( 'LI_BASE_DIR' ) ) {
	require get_template_directory() . '/inc/integrations/love-it/love-it.php';
}

/**
 * Load Widgets
 */
require get_template_directory() . '/inc/class-widget.php';
require get_template_directory() . '/inc/widgets/class-widget-slider.php';
require get_template_directory() . '/inc/widgets/class-widget-price-option.php';
require get_template_directory() . '/inc/widgets/class-widget-price-table.php';

if ( marketify_is_edd() ) {
	require get_template_directory() . '/inc/widgets/class-widget-downloads-recent.php';
	require get_template_directory() . '/inc/widgets/class-widget-featured-popular.php';
	require get_template_directory() . '/inc/widgets/class-widget-download-details.php';
	require get_template_directory() . '/inc/widgets/class-widget-download-share.php';

	if ( class_exists( 'EDD_Reviews' ) ) {
		require get_template_directory() . '/inc/widgets/class-widget-download-review-details.php';
	}
}

if ( function_exists( 'soliloquy_slider' ) ) {
	require get_template_directory() . '/inc/widgets/class-widget-slider-soliloquy.php';
}