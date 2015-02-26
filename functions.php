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

	public static $strings;
	public static $integrations;
	public static $assets;
	public static $navigation;

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
			'class-plugins.php',
			'class-strings.php',
			/* 'class-activation.php', */
			/* 'class-setup.php', */
			'class-integrations.php',
			'class-integration.php',
			'class-assets.php',
			/* 'class-widgets.php', */
			/* 'class-widgetized-pages.php', */	
			'class-widget.php',
			'class-navigation.php',
			/* 'class-pagination.php', */
			/* 'class-comments.php', */
			/* 'custom-header.php' */
		);

		foreach ( $this->files as $file ) {
			require_once( get_template_directory() . '/inc/' . $file );
		}
	}

	private function setup() {
		self::$strings = new Marketify_Strings();
		/* $this->activation = new Marketify_Activation(); */
		/* $this->setup = new Jobify_Setup(); */
		self::$integrations = new Marketify_Integrations();
		self::$assets = new Marketify_Assets();
		self::$navigation = new Marketify_Navigation();
		/* $this->pagination = new Jobify_Pagination(); */
		/* $this->comments = new Jobify_Comments(); */
		/* $this->widgets = new Jobify_Widgets(); */
		/* $this->widgetized_pages = new Jobify_Widgetized_Pages(); */

		add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
	}
	
	public function setup_theme() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'marketify' );
		load_textdomain( 'marketify', WP_LANG_DIR . "/marketify-$locale.mo" );
		load_theme_textdomain( 'marketify', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );

		add_editor_style( 'css/editor-style.css' );

		add_theme_support( 'custom-background', apply_filters( 'marketify_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );
	}

}

new Marketify();
