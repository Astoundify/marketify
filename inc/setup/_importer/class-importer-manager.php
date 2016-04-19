<?php
/**
 * Manage import processes.
 *
 * Most of the time a queue will only consist of a single file. However for things
 * like importing plugins there will be multiple files split across.
 *
 * This allows combination of types that may be related: "Posts + Pages", etc.
 *
 * @since 1.0.0
 */
class Astoundify_Importer_Manager {

	/**
	 * A list of file paths to import.
	 * 
	 * @var array $queue
	 */
	public static $queue = array();

	/**
	 * A persistent storage of what has previously been procesed.
	 *
	 * @var $history
	 */
	public static $history;

	/**
	 * See if an import has previously been processed before.
	 *
	 * These types do not have to relate directly to import classes. For example
	 * an import of `posts_pages` can be tracked as being previously imported.
	 *
	 * @since 1.0.0
	 *
	 * @param string $import_key
	 * @return boolean
	 */
	public static function has_previously_imported( $import_key ){
		$history = self::get_import_history();

		if ( array_key_exists( $import_key, $history ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get previously imported items.
	 *
	 * @since 1.0.0
	 *
	 * @return array self::$history
	 */
	public static function get_import_history() {
		if ( isset( self::$history ) && ! empty( self::$history ) ) {
			return self::$history;
		}

		return self::$history = get_option( 'astoundify_content_importer_history', array() );
	}

	/**
	 * Add an item to the import history.
	 *
	 * @since 1.0.0
	 *
	 * @param string $import_key The unique key to track this imports history.
	 * @return void
	 */
	public static function add_imported_flag( $import_key ) {
		// populate if this is called directly
		self::get_import_history();

		self::$history[ $import_key ] = current_time( 'timestamp' );

		update_option( 'astoundify_content_importer_history', self::$history );
	}

	/**
	 * Remove an item from the import history
	 *
	 * @since 1.0.0
	 *
	 * @param string $import_key The unique key to track this imports history.
	 * @return void
	 */
	public static function remove_imported_flag( $import_key ) {
		// populate if this is called directly
		self::get_import_history();

		if ( isset( self::$history[ $import_key ] ) ) {
			unset( self::$history[ $import_key ] );
		}

		update_option( 'astoundify_content_importer_history', self::$history );
	}

	/**
	 * Add a file to the current queue to process. 
	 *
	 * Use the file to generate the relevant import class name and
	 * add an instance of the import class to the queue.
	 *
	 * @since 1.0.0
	 *
	 * @param (string|array) $files The path to the file to enqueue
	 * @return void
	 */
	public static function enqueue( $files, $clear = true ) {
		if ( $clear ) {
			self::$queue = array();
		}

		if ( ! is_array( $files ) ) {
			$files = array( $files );
		}

		foreach ( $files as $file ) {
			$classname = self::get_import_class( $file );

			if ( class_exists( $classname ) ) {
				self::$queue[] = new $classname( $file );
			} else {
				// default to a generic plugin
				self::$queue[] = new Astoundify_Import_Plugin( $file );
			}
		}
	}

	/**
	 * Process the files that have been added to the queue.
	 *
	 * @since 1.0.0
	 *
	 * @param string $process_action The action to perform on the dta. (process|reset)
	 * @param string $import_key The unique key to track this imports history.
	 * @return void
	 */
	public static function process( $process_action = 'process', $import_key ) {
		// cap check
		if ( ! current_user_can( 'import' ) ) {
			return false;
		}

		// only attempt to import once
		if ( 'process' == $process_action && self::has_previously_imported( $import_key ) ) {
			return false;
		}

		// check queue
		if ( empty( self::$queue ) ) {
			return false;
		}

		foreach( self::$queue as $importer ) {
			$importer->process_data( $process_action );
		}

		// update history
		if ( 'process' == $process_action ) {
			self::add_imported_flag( $import_key );
		} else { 
			self::remove_imported_flag( $import_key );
		}

		// assume everything went well....
		return true;
	}

	/**
	 * Get the import class name from a file path.
	 *
	 * Example:
	 *
	 * `importer/data/posts.json` to Astoundify_Import_Posts
	 * `importer/data/plugin-woocommerce.json` to Astoundify_Import_Plugin_WooCommerce
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the import file.
	 * @return string $classname The name of the class that will process the file.
	 */
	public static function get_import_class( $file ) {
		$file = basename( $file );
		$parts = explode( '.', $file );

		// get the type
		$import_type = $parts[0];

		$import_type = explode( '_', $import_type );
		$import_type = implode( '_', array_map( 'ucfirst', $import_type ) );

		$classname = 'Astoundify_Import_' . $import_type;

		return $classname;
	}
}
