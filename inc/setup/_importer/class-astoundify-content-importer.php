<?php
if ( ! class_exists( 'Astoundify_Content_Importer' ) ) :
/**
 * @package Astoundify_Content_Importer
 */
class Astoundify_Content_Importer {

	/**
	 * The single class instance.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var object
 	 */
	private static $_instance = null;

	/**
	 * The strings used for any output in the drop-ins.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	public static $strings = array();

	/**
	 * Main Astoundify_Content_Importer
	 *
	 * Ensures only one instance of this class exists in memory at any one time.
	 *
	 * @see Astoundify_Content_Importer
	 *
	 * @since 1.0.0
	 * @static
	 * @return object The one true Astoundify_Content_Importer
	 * @codeCoverageIgnore
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::init();
		}

		return self::$_instance;
	}

	/**
	 * Set things up.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public static function init() {
		self::includes();
	}

	/**
	 * Set the strings to be used inside the other drop in files.
	 *
	 * @since 1.0.0
	 *
	 * @return self::$strings
	 */
	public static function set_strings( $strings = array() ) {
		$defaults = array(
		);

		$strings = wp_parse_args( $strings, $defaults );

		self::$strings = $strings;
	}

	/**
	 * Get strings.
	 *
	 * Set the defaults if none are available.
	 *
	 * @since 1.0.0
	 * @return self::$strings
	 */
	public static function get_strings() {
		if ( empty( self::$strings ) ) {
			self::set_strings();
		}

		return self::$strings;
	}

	/**
	 * Include necessary files.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public static function includes() {
		// process
		include_once( dirname( __FILE__ ) . '/class-importer-history.php' );
		include_once( dirname( __FILE__ ) . '/class-importer-manager.php' );

		// base
		include_once( dirname( __FILE__ ) . '/class-importer.php' );

		// custom post types
		include_once( dirname( __FILE__ ) . '/class-import-object.php' );
		include_once( dirname( __FILE__ ) . '/class-import-posts.php' );
		include_once( dirname( __FILE__ ) . '/class-import-pages.php' );
		include_once( dirname( __FILE__ ) . '/class-import-downloads.php' );

		// special things
		include_once( dirname( __FILE__ ) . '/class-import-nav_menus.php' );
		include_once( dirname( __FILE__ ) . '/class-import-nav_menu_items.php' );
		include_once( dirname( __FILE__ ) . '/class-import-terms.php' );
		include_once( dirname( __FILE__ ) . '/class-import-widgets.php' );
		include_once( dirname( __FILE__ ) . '/class-import-theme-mods.php' );

		// plugins
		include_once( dirname( __FILE__ ) . '/class-import-plugin.php' );
		include_once( dirname( __FILE__ ) . '/plugins/class-import-plugin-woocommerce.php' );
		include_once( dirname( __FILE__ ) . '/plugins/class-import-plugin-easy-digital-downloads.php' );
		include_once( dirname( __FILE__ ) . '/plugins/class-import-plugin-frontend-submissions.php' );
		include_once( dirname( __FILE__ ) . '/plugins/class-import-plugin-woothemes-testimonials.php' );
	}

}
endif;
