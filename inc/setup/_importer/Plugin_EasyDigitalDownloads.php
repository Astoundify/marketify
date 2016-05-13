<?php
/**
 * Extra procsesing for Easy Digital Downloads
 *
 * @since 1.0.0
 */
class Astoundify_Plugin_EasyDigitalDownloads implements Astoundify_PluginInterface {

	/**
	 * Initialize the plugin processing
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		self::setup_actions();
	}

	/**
	 * Add any pre/post actions to processing.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function setup_actions() {
		add_action( 
			'astoundify_import_content_after_impot_item_type_download',
			array( __CLASS__, 'set_download_pricing' ) 
		);
		
		$pages = array( 'purchase', 'success', 'failure', 'purchase_history' );

		foreach ( $pages as $page ) {
			add_action( 
				'astoundify_import_content_after_import_item_' . $page, 
				array( __CLASS__, 'add_page_option' ) 
			);

			add_action( 
				'astoundify_import_content_after_reset_item_' . $page, 
				array( __CLASS__, 'delete_page_option' ) 
			);
		}
	}

	/**
	 * Add prices to the download.
	 *
	 * If `price` is defined a single price is used. If `prices` is defined
	 * variabel prices are enabled and added.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public function add_pricing( $ItemImport ) {
		$error = new WP_Error( 
			'set-pricing', 
			sprintf( 'Pricing for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $ItemImport->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$download_id = $object->ID;
		$item_data = $ItemImport->item[ 'data' ];

		$prices = isset( $item_data[ 'prices' ] ) ? $item_data[ 'prices' ] : false;

		// single price
		if ( isset( $item_data[ 'price' ] ) ) {
			$price = $item_data[ 'price' ];

			update_post_meta( $download_id, 'edd_price', $price );
			update_post_meta( $download_id, '_variable_pricing', 0 );
		}

		if ( isset( $item_data[ 'prices' ] ) ) {
			$prices = $item_data[ 'prices' ];
			$_prices = array();

			foreach ( $prices as $name => $amount ) {
				$_prices[] = array( 'name' => $name, 'amount' => $amount );
			}

			update_post_meta( $download_id, 'edd_price', '0.00' );
			update_post_meta( $download_id, '_variable_pricing', 1 );
			update_post_meta( $download_id, 'edd_variable_prices', array_values( $_prices ) );
		}

		// needs better error checking
		return true;
	}

	/**
	 * Assign the relevant setting.
	 *
	 * @since 1.0.0
	 * @param array $args Import item context.
	 * @return void
	 */
	public static function add_page_option( $ItemImport ) {
		edd_update_option( "{$ItemImport->get_id()}_page", $ItemImport->get_processed_item()->ID );
	}

	/**
	 * Delete the relevant setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public static function delete_page_option( $ItemImport ) {
		edd_delete_option( "{$ItemImport->get_id()}_page" );
	}

}

Astoundify_Plugin_EasyDigitalDownloads::init();