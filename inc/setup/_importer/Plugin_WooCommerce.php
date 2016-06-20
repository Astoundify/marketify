<?php
/**
 * Extra procsesing for WooCommerce
 *
 * @since 1.0.0
 */
class Astoundify_Plugin_WooCommerce implements Astoundify_PluginInterface {

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
			'astoundify_import_content_after_import_item_type_object',
			array( __CLASS__, 'set_price' ) 
		);

		add_action( 
			'astoundify_import_content_after_import_item_type_object',
			array( __CLASS__, 'set_product_type' ) 
		);

		// this needs to happen after images have had a chance to be attached
		add_action( 
			'astoundify_import_content_after_import_item_type_object',
			array( __CLASS__, 'set_product_gallery' ),
			20
		);
	}

	/**
	 * Add a price to the product
	 *
	 * If `price` is defined a single price is used.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public static function set_price( $ItemImport ) {
		$item_data = $ItemImport->item[ 'data' ];

		// do nothing if this is not relevant to the current object type
		if ( 'product' != $item_data[ 'post_type' ] ) {
			return false;
		}

		$error = new WP_Error( 
			'set-pricing', 
			sprintf( 'Pricing for %s was not set', $ItemImport->get_id() )
		);

		// only work with a valid processed object
		$object = $ItemImport->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$product_id = $object->ID;

		// single price
		if ( isset( $item_data[ 'price' ] ) ) {
			$price = $item_data[ 'price' ];

			update_post_meta( $product_id, '_price', $price );
		}

		// needs better error checking
		return true;
	}

	/**
	 * Set the product type
	 *
	 * If `type` is defined the product type taxonomy will be set
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public static function set_product_type( $ItemImport ) {
		$item_data = $ItemImport->item[ 'data' ];

		// do nothing if this is not relevant to the current object type
		if ( 'product' != $item_data[ 'post_type' ] ) {
			return false;
		}

		$error = new WP_Error( 
			'set-type', 
			sprintf( 'Product type for %s was not set', $ItemImport->get_id() )
		);

		// only work with a valid processed object
		$object = $ItemImport->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$product_id = $object->ID;

		// single price
		if ( isset( $item_data[ 'type' ] ) ) {
			$type = $item_data[ 'type' ];

			wp_set_object_terms( $product_id, $type, 'product_type' );
		}

		// needs better error checking
		return true;
	}

	/**
	 * Set the product gallery
	 *
	 * If `media` is defined the uploaded images will be set as the gallery
	 * images for the current product.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public static function set_product_gallery( $ItemImport ) {
		$item_data = $ItemImport->item[ 'data' ];

		// do nothing if this is not relevant to the current object type
		if ( 'product' != $item_data[ 'post_type' ] ) {
			return false;
		}

		$error = new WP_Error( 
			'set-gallery', 
			sprintf( 'Product gallery for %s was not set', $ItemImport->get_id() )
		);

		// only work with a valid processed object
		$object = $ItemImport->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$product_id = $object->ID;

		// single price
		if ( isset( $item_data[ 'media' ] ) ) {
			$images = get_attached_media( 'image', $product_id );

			if ( ! empty( $images ) ) {
				$image_ids = implode( ',', wp_list_pluck( $images, 'ID' ) );

				update_post_meta( $product_id, '_product_image_gallery', $image_ids );
			}
		}

		// needs better error checking
		return true;
	}

}

Astoundify_Plugin_WooCommerce::init();
