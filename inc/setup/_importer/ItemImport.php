<?php
/**
 * Single item import
 *
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
abstract class Astoundify_AbstractItemImport {

	/**
	 * The item data that is being acted upon.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $item;

	/**
	 * The action that is being taken on the item data.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $action;

	/**
	 * The processed item.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var WP_Error|mixed
	 */
	public $processed_item;

	/**
	 * Set the current item data.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct( $item ) {
		$this->set_item( $item );

		$this->setup_actions();
	}

	/**
	 * Set the current item data.
	 *
	 * @since 1.0.0
	 * @param array $item Item data.
	 * @return array Item data.
	 */
	public function set_item( $item = false ) {
		if ( ! $item ) {
			return false;
		}

		$this->item = $item;

		return $this->get_item();
	}

	/**
	 * Get the current item data.
	 *
	 * @since 1.0.0
	 * @return array Item data.
	 */
	public function get_item() {
		return $this->item;
	}

	/**
	 * Set the current action.
	 *
	 * @since 1.0.0
	 * @param string $action The action to take on the item.
	 * @return string The action to take on the item.
	 */
	public function set_action( $action ) {
		$this->action = $action;

		return $this->get_action();
	}

	/**
	 * Get the current action.
	 *
	 * @since 1.0.0
	 * @return string The action to take on the item.
	 */
	public function get_action() {
		return $this->action ? $this->action : 'import';
	}

	/**
	 * Set the processed item.
	 *
	 * @since 1.0.0
	 * @param mixed $item The procesesed item.
	 * @return mixed The processed item.
	 */
	public function set_processed_item( $item ) {
		$this->processed_item = $item;

		return $this->get_processed_item();
	}

	/**
	 * Get the processed item.
	 *
	 * @since 1.0.0
	 * @return mixed The processed item.
	 */
	public function get_processed_item() {
		return $this->processed_item;
	}

	/**
	 * Get the ID of the current item.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_id() {
		if( isset( $this->item[ 'id' ] ) ) {
			return esc_attr( $this->item[ 'id' ] );
		}

		return false;
	}

	/**
	 * Get the type of the current item.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_type() {
		return isset( $this->item[ 'type' ] ) ? esc_attr( $this->item[ 'type' ] ) : false;
	}

	/**
	 * Get the type label of the current item.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_type_label() {
		$strings = Astoundify_ContentImporter::get_strings();
		$labels = $strings[ 'type_labels' ];

		return esc_attr( $labels[ $this->get_type() ] );
	}

	/**
	 * Generate a WP_Error instance for the current item
	 *
	 * @since 1.0.0
	 * @return WP_Error
	 */
	public function get_default_error() {
		return new WP_Error(
			sprintf( '%s-%s-failed', $this->get_type(), $this->get_action() ),
			sprintf( '<strong>%1$s</strong> %2$s was unable to %3$s.', $this->get_type_label(), $this->get_id(), $this->get_action() )
		);
	}

	/**
	 * Add any pre/post actions to processing.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function setup_actions() {}

	/**
	 * Act on a specific item
	 *
	 * @since 1.0.0
	 * @param string $action The action to take
	 * @return mixed
	 */
	public function iterate( $action ) {
		// allow things to happen before
		$this->iterate_actions( 'before' );

		// process
		$this->process();

		// allow things to happen after
		$this->iterate_actions( 'after' );

		return $this;
	}

	/**
	 * Process a specific item.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function process() {
		$action = $this->get_action();

		$result = $this->$action();

		$this->set_processed_item( $result );

		return $result;
	}

	/**
	 * Hooks for a single item process.
	 *
	 * @since 1.0.0
	 * @param string $when Context for before/after.
	 * @param string $what Context for the action being taken.
	 * @param array $args
	 * @return void
	 */
	private function iterate_actions( $when ) {
		// general
		do_action( "astoundify_import_content_{$when}_{$this->get_action()}_item", $this );

		// type
		do_action( "astoundify_import_content_{$when}_{$this->get_action()}_item_type_{$this->get_type()}", $this );

		// object type
		if ( isset( $this->item[ 'data' ][ 'post_type' ] ) ) {
			$object_type = $this->item[ 'data' ][ 'post_type' ];

			do_action( "astoundify_import_content_{$when}_{$this->get_action()}_item_type_{$object_type}", $this );
		}

		// item
		do_action( "astoundify_import_content_{$when}_{$this->get_action()}_item_{$this->get_id()}", $this );
	}

}