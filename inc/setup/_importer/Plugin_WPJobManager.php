<?php
/**
 * Extra procsesing for WP Job Manager
 *
 * @since 1.0.0
 */
class Astoundify_Plugin_WPJobManager implements Astoundify_PluginInterface {

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
			array( __CLASS__, 'set_location' ) 
		);
	}

	/**
	 * Add a location to a listing.
	 *
	 * If `location` is a string the geolocation data will automatically
	 * be generated. Otherise all data can be supplied.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public static function set_location( $ItemImport ) {
		$item_data = $ItemImport->item[ 'data' ];

		// do nothing if this is not relevant to the current object type
		if ( 'job_listing' != $item_data[ 'post_type' ] ) {
			return false;
		}

		$error = new WP_Error( 
			'set-location', 
			sprintf( 'Location for %s was not set', $ItemImport->get_id() )
		);

		// only work with a valid processed object
		$object = $ItemImport->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$listing_id = $object->ID;

		if ( isset( $item_data[ 'location' ] ) ) {
			$location = $item_data[ 'location' ];

			if ( ! is_array( $location ) ) {
				if ( class_exists( 'WP_Job_Manager_Geocode' ) ) {
					WP_Job_Manager_Geocode::generate_location_data( $listing_id, sanitize_text_field( $location ) );
				}
				
				// fake for the test
				update_post_meta( $listing_id, 'geolocated', 1 );
			} else {
				update_post_meta( $listing_id, 'geolocated', 1 );
				update_post_meta( $listing_id, 'geolocation_city', $location[ 'city' ] );
				update_post_meta( $listing_id, 'geolocation_country_long', $location[ 'country_long' ] );
				update_post_meta( $listing_id, 'geolocation_country_short', $location[ 'country_short' ] );
				update_post_meta( $listing_id, 'geolocation_formatted_address', $location[ 'address' ] );
				update_post_meta( $listing_id, 'geolocation_lat', $location[ 'latitude' ] );
				update_post_meta( $listing_id, 'geolocation_long', $location[ 'longitude' ] );
				update_post_meta( $listing_id, 'geolocation_state_long', $location[ 'state' ] );
				update_post_meta( $listing_id, 'geolocation_state_short', $location[ 'state_short' ] );
				update_post_meta( $listing_id, 'geolocation_street', $location[ 'street' ] );
				update_post_meta( $listing_id, 'geolocation_street_number', $location[ 'street_number' ] );
				update_post_meta( $listing_id, 'geolocation_postcode', $location[ 'postcode' ] );
			}
		}

		// needs better error checking
		return true;
	}

}

Astoundify_Plugin_WPJobManager::init();
