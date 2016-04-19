<?php
/**
 * Store previously imported and processed items for later reference.
 *
 * Most actions that are needed only need to reference the previous item 
 * imported. This cane be done easily as all relevant information is automatically
 * exposed to the callback. However if the callback needs to manipulate something
 * based on an arbitrary import item this allows it to be referenced.
 *
 * @since 1.0.0
 */
class Astoundify_Importer_History {

	/**
	 * The single class instance.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var object
 	 */
	private static $instance;

	/**
	 * Imported items.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
 	 */
	public static $imported = array();

	/**
	 * Main Astoundify_Importer_History
	 *
	 * Ensures only one instance of this class exists in memory at any one time.
	 *
	 * @see Astoundify_Importer_History
	 *
	 * @since 1.0.0
	 *
	 * @return object The one true Astoundify_Importer_History
	 * @codeCoverageIgnore
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::setup_actions();
		}

		return self::$instance;
	}

	/**
	 * Add any pre/post actions to processing.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public function setup_actions() {
		add_action( 'astoundify_import_content_after_process', array( __CLASS__, 'save' ) );
	}

	/**
	 * Add an item to the history.
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_id The reference to the item to be added.
	 * @param array $data
	 * @return void
	 */
	public static function process( $item_id, $data ) {
		$type = $data[ 'item_type' ];

		if ( ! isset( self::$imported[ $type ] ) ) {
			self::$imported[ $type ] = array();
		}	

		if ( ! isset( self::$imported[ $type ][ $item_id ] ) ) {
			self::$imported[ $type ][ $item_id ] = $data;
		}
	}

	/**
	 * Remove an item from the history.
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_id The reference to the item to get.
	 * @param array $args
	 * @return object
	 */
	public static function reset( $item_id, $args = array() ) {
		$defaults = array(
			'item_type' => 'post'
		);

		$args = wp_parse_args( $args, $defaults );

		if ( ! isset( self::$imported[ $args[ 'item_type' ] ] ) ) {
			return false;
		}

		$type = self::$imported[ $args[ 'item_type' ] ];

		if ( isset( self::$imported[ $args[ 'item_type' ] ][ $item_id ] ) ) {
			unset( self::$imported[ $args[ 'item_type' ] ][ $item_id ] );

			return true;
		}

		return false;
	}

	/**
	 * Get an item from the history
	 *
	 * @since 1.0.0
	 *
	 * @param string $item_id The reference to the item to get.
	 * @param array $args
	 * @return object
	 */
	public static function get( $item_id, $args = array() ) {
		$defaults = array(
			'item_type' => 'post',
			'field' => 'processed_item'
		);

		$args = wp_parse_args( $args, $defaults );

		$type = $args[ 'item_type' ];

		// see if any of the type have been imported
		if ( ! isset( self::$imported[ $type ] ) ) {
			return false;
		}

		// see if the item has been imported
		if ( ! isset( self::$imported[ $type ][ $item_id ] ) ) {
			return false;
		}

		// get the single item
		$item = self::$imported[ $type ][ $item_id ];

		if ( $args[ 'field' ] ) {
			return $item[ $args[ 'field' ] ];
		}

		return $item;
	}

	/**
	 * Persist the import history. Processed items will be added to the list and
	 * reset items will be removed. This can be used to quickly reference if an item
	 * was imported at any time in the past. If so, it will be skipped and not 
	 * attempted to import again.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function save() {
		update_option( 'astoundify_import_history', self::$imported );
	}

	/**
	 * Clear the history.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function clear() {
		self::$imported = array();
	}

}
