<?php
/**
 * Download importer.
 *
 * @see Astoundify_Importer
 *
 * @since 1.0.0
 */
class Astoundify_Import_Downloads extends Astoundify_Import_Objects {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'download';
		$this->file = $file;

		$this->setup_object_actions();

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
		add_action( "astoundify_import_content_after_process_item_type_download", array( $this, 'add_pricing' ) );
	}

	/**
	 * Add prices to the download.
	 *
	 * If `price` is defined a single price is used. If `prices` is defined
	 * variabel prices are enabled and added.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function add_pricing( $args ) {
		$download_id = isset( $args[ 'processed_item' ]->ID ) ? $args[ 'processed_item' ]->ID : false;

		if ( ! $download_id ) {
			return;
		}

		$prices = isset( $args[ 'item_data' ][ 'prices' ] ) ? $args[ 'item_data' ][ 'prices' ] : false;

		// single price
		if ( ! $prices ) {
			$price = isset( $args[ 'item_data' ][ 'price' ] ) ? $args[ 'item_data' ][ 'price' ] : false;

			if ( ! $price ) {
				return;
			}

			update_post_meta( $download_id, 'edd_price', $price );
			update_post_meta( $download_id, '_variable_pricing', 0 );
		// variable price
		} else {
			$_prices = array();

			foreach ( $prices as $name => $amount ) {
				$_prices[] = array( 'name' => $name, 'amount' => $amount );
			}

			update_post_meta( $download_id, 'edd_price', '0.00' );
			update_post_meta( $download_id, '_variable_pricing', 1 );
			update_post_meta( $download_id, 'edd_variable_prices', array_values( $_prices ) );
		}
	}

}
