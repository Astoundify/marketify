<?php
/**
 * Easy Digital Downloads Plugin importer.
 *
 * @see Astoundify_Importer
 *
 * @since 1.0.0
 */
class Astoundify_Import_Plugin_Easy_Digital_Downloads extends Astoundify_Import_Plugin {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file ) {
		$this->file = $file;

		parent::__construct();
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
		$pages = array( 'purchase', 'success', 'failure', 'purchase_history' );

		foreach ( $pages as $page ) {
			add_action( "astoundify_import_content_after_process_item_{$page}", array( $this, 'add_option' ) );
			add_action( "astoundify_import_content_after_reset_item_{$page}", array( $this, 'delete_option' ) );
		}
	}

	/**
	 * Assign the relevant setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function add_option( $args ) {
		edd_update_option( "{$args[ 'item_id' ]}_page", $args[ 'processed_item' ]->ID );
	}

	/**
	 * Delete the relevant setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function delete_option( $args ) {
		edd_delete_option( "{$args[ 'item_id' ]}_page" );
	}

}
