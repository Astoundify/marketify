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
		}

		return self::$instance;
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
	public static function add( $item_id, $data ) {
		$type = $data[ 'item_type' ];

		if ( ! isset( self::$imported[ $type ] ) ) {
			self::$imported[ $type ] = array();
		}	

		self::$imported[ $type ][ $item_id ] = $data;
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

		$type = self::$imported[ $args[ 'item_type' ] ];

		if ( $args[ 'field' ] ) {
			return $type[ $item_id ][ $args[ 'field' ] ];
		}

		return $type[ $item_id ];
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
