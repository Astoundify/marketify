<?php
/**
 * Base Importer
 *
 * @since 1.0.0
 */
class Astoundify_Importer {

	/**
	 * The file to import.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $file;

	/**
	 * The type of data being imported.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $type;

	/**
	 * The data for the current file to import.
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $data;

	/**
	 * All items that have been previously imported.
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $history;

	/**
	 * Start things up.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		if ( method_exists( $this, 'setup_actions' ) ) {
			$this->setup_actions();
		}
	}

	/**
	 * Get the data type we are importing.
	 *
	 * @since 1.0.0
	 *
	 * @return string $type
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Get the file we are importing.
	 *
	 * @since 1.0.0
	 *
	 * @return string $path
	 */
	public function get_file() {
		return $this->file;
	}

	/**
	 * Get the contents of the JSON file and decode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path
	 * @return object $data
	 */
	public function get_data() {
		// return if we have gotten it already
		if ( isset( $this->data ) && ! empty( $this->data ) ) {
			return $this->data;
		}

		if ( ! file_exists( $this->get_file() ) ) {
			return false;
		}

		$file = file_get_contents( $this->get_file() );

		if ( '' == $file ) {
			return false;
		}

		$this->data = json_decode( $file, true );

		return $this->data;
	}

	/**
	 * Process the items.
	 *
	 * Currently two process actions exist: process, reset
	 *
	 * Each action will iterate through the .json data and call the respective action
	 * on the data item. Actions will also fire before general processing, before item processing,
	 * after item processing, and after general processing.
	 *
	 * @since 1.0.0
	 *
	 * @param string $process_action The action to perform with the data.
	 * @return void
	 */
	public function process_data( $process_action = 'process' ) {
		if ( ! $this->get_data() || empty( $this->get_data() ) ) {
			return false;
		}

		// fire some actions before processing
		$this->process_actions( 'before', $process_action, array( 
			'item_type' => $this->type ,
			'import_data' => $this->get_data()
		) );

		foreach ( $this->get_data() as $item_id => $item ) {
			// context for actions
			$args = array( 
				'item_id' => $item_id,
				'item_data' => $item, 
				'item_type' => $this->type
			);

			// fire some actions before item processing
			$this->process_item_actions( 'before', $process_action, $args );

			// process
			$processed = $this->$process_action( $args );

			// add the processed item to context
			$args = array_merge( array( 
				'processed_item' => $processed,
			), $args );

			// add item to history
			Astoundify_Importer_History::add( $item_id, $args );

			// fire some actions after item processing
			$this->process_item_actions( 'after', $process_action, $args );
		}

		// fire some actions after processing
		$this->process_actions( 'after', $process_action, array(
			'item_type' => $this->type ,
			'import_data' => $this->get_data()
		) );
	}

	/**
	 * Process an individual item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $process_args Import item context.
	 * @return mixed
	 */
	public function process( $process_args ) {}

	/**
	 * Reset an individual item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $process_args Import item context.
	 * @return mixed
	 */
	public function reset( $process_args ) {}

	/**
	 * Hooks for a processing instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string $when Context for before/after
	 * @param array $args
	 * @return void
	 */
	private function process_actions( $when, $what, $args ) {
		// general
		do_action( "astoundify_import_content_{$when}_{$what}", $args );

		// type
		do_action( "astoundify_import_content_{$when}_{$what}_type_{$args[ 'item_type' ]}", $args );
	}

	/**
	 * Hooks for a single item process.
	 *
	 * @since 1.0.0
	 *
	 * @param string $when Context for before/after.
	 * @param string $what Context for the action being taken.
	 * @param array $args
	 * @return void
	 */
	private function process_item_actions( $when, $what, $args ) {
		// general
		do_action( "astoundify_import_content_{$when}_{$what}_item", $args );

		// type
		do_action( "astoundify_import_content_{$when}_{$what}_item_type_{$args[ 'item_type' ]}", $args );

		// item
		do_action( "astoundify_import_content_{$when}_{$what}_item_{$args[ 'item_id' ]}", $args );
	}

	/**
	 * Handle media upload.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file URL to an asset to upload.
	 * @param int $post_id The post ID to attach the media to.
	 * @return (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
	 */
	public function upload_media( $file, $post_id ) {
		// jic
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Set variables for storage, fix file filename for query strings.
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );

		$file_array = array();
		$file_array['name'] = basename( $matches[0] );

		// Download file to temp location.
		$file_array['tmp_name'] = download_url( $file );

		// If error storing temporarily, return the error.
		if ( is_wp_error( $file_array['tmp_name'] ) ) {
			return $file_array['tmp_name'];
		}

		// Do the validation and storage stuff.
		$id = media_handle_sideload( $file_array, $post_id, $desc = false );

		// If error storing permanently, unlink.
		if ( is_wp_error( $id ) ) {
			@unlink( $file_array['tmp_name'] );
			return $id;
		}

		return $id;
	}

}