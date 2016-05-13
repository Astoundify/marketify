<?php
if ( ! class_exists( 'Astoundify_ContentImporter' ) ) :
/**
 * @package Astoundify_Content_Importer
 */
class Astoundify_ContentImporter {

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
			'type_labels' => array(
				'setting' => array( __( 'Setting' ), __( 'Settings' ) ),
				'theme-mod' => array( __( 'Theme Customization' ), __( 'Theme Customizations' ) ),
				'nav-menu' => array( __( 'Navigation Menu' ), __( 'Navigation Menus' ) ),
				'term' => array( __( 'Term' ), __( 'Terms' ) ),
				'object' => array( __( 'Object' ), __( 'Objects' ) ),
				'nav-menu-item' => array( __( 'Navigation Menu Item' ), __( 'Navigation Menu Items' ) ),
				'widget' => array( __( 'Widget' ), __( 'Widgets' ) )
			),
			'import' => array(
				'complete' => __( 'Import Complete!' ),
			),
			'reset' => array(
				'complete' => __( 'Reset Complete' )
			)
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
		include_once( dirname( __FILE__ ) . '/SortableInterface.php' );
		include_once( dirname( __FILE__ ) . '/Utils.php' );

		include_once( dirname( __FILE__ ) . '/ImportManager.php' );

		include_once( dirname( __FILE__ ) . '/ImporterInterface.php' );
		include_once( dirname( __FILE__ ) . '/ImporterFactory.php' );
		include_once( dirname( __FILE__ ) . '/Importer.php' );
		include_once( dirname( __FILE__ ) . '/JSONImporter.php' );

		include_once( dirname( __FILE__ ) . '/ItemImportInterface.php' );
		include_once( dirname( __FILE__ ) . '/ItemImportFactory.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_Object.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_NavMenu.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_NavMenuItem.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_Term.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_ThemeMod.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_Setting.php' );
		include_once( dirname( __FILE__ ) . '/ItemImport_Widget.php' );

		include_once( dirname( __FILE__ ) . '/PluginInterface.php' );
		include_once( dirname( __FILE__ ) . '/Plugin_WooThemesTestimonials.php' );
		include_once( dirname( __FILE__ ) . '/Plugin_EasyDigitalDownloads.php' );
		include_once( dirname( __FILE__ ) . '/Plugin_FrontendSubmissions.php' );
	}

}
endif;
