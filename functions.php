<?php
/**
 * Marketify
 *
 * Do not modify this file. Place all modifications in a child theme.
 */

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

class Marketify {

	private static $instance;

	public static $customizer;
	public static $strings;
	public static $integrations;
	public static $assets;
	public static $navigation;
	public static $page_header;
	public static $widgets;

	public function __construct() {
		$this->base();
		$this->setup();
	}
	
	// Integration getter helper
	public function get( $integration ) {
		return self::$integrations->get( $integration );
	}

	private function base() {
		$this->files = array(
			'customizer/class-customizer.php',
			'class-plugins.php',
			'class-strings.php',
			/* 'class-activation.php', */
			/* 'class-setup.php', */
			'class-integrations.php',
			'class-integration.php',
			'class-assets.php',
			'class-widgets.php',
			'class-widget.php',
			'class-navigation.php',
			'class-page-header.php',
			/* 'class-pagination.php', */
			/* 'class-comments.php', */
			/* 'custom-header.php' */
		);

		foreach ( $this->files as $file ) {
			require_once( get_template_directory() . '/inc/' . $file );
		}
	}

	private function setup() {
		self::$customizer = new Marketify_Customizer();
		self::$strings = new Marketify_Strings();
		/* $this->activation = new Marketify_Activation(); */
		/* $this->setup = new Jobify_Setup(); */
		self::$integrations = new Marketify_Integrations();
		self::$assets = new Marketify_Assets();
		self::$navigation = new Marketify_Navigation();
		self::$page_header = new Marketify_Page_Header();
		/* $this->pagination = new Jobify_Pagination(); */
		/* $this->comments = new Jobify_Comments(); */
		self::$widgets = new Marketify_Widgets();

		add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
	}
	
	public function setup_theme() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'marketify' );
		load_textdomain( 'marketify', WP_LANG_DIR . "/marketify-$locale.mo" );
		load_theme_textdomain( 'marketify', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );

		add_editor_style( 'css/editor-style.css' );

		add_theme_support( 'custom-background', apply_filters( 'marketify_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}

}

new Marketify();
