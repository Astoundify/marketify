<?php
/**
 * Import a setting
 *
 * @uses Astoundify_AbstractItemImport
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
class Astoundify_ItemImport_Setting extends Astoundify_AbstractItemImport implements Astoundify_ItemImportInterface {

	public function __construct( $item ) {
		parent::__construct( $item );
	}

	/**
	 * Get the setting key
	 *
	 * @since 1.0.0
	 * @return false|string The key string if it exists. False if it does not.
	 */
	private function get_key() {
		return $this->get_id();
	}

	/**
	 * Get the setting array key for settings saved in a single value.
	 *
	 * @since 1.0.0
	 * @return false|string The value string if it exists. False if it does not.
	 */
	private function get_option_index() {
		if ( ! isset( $this->item[ 'data' ] ) ) {
			return false;
		}

		$value = $this->item[ 'data' ];

		if ( ! is_array( $value ) ) {
			return false;
		}

		return key( $value );
	}

	/**
	 * Get the theme mod value
	 *
	 * @since 1.0.0
	 * @return false|string The value string if it exists. False if it does not.
	 */
	private function get_value() {
		if ( ! isset( $this->item[ 'data' ] ) ) {
			return false;
		}

		$value = $this->item[ 'data' ];

		// handle settings saved in an array
		// get existing options array and add our new value
		if ( false != ( $index = $this->get_option_index() ) ) {
			$options = get_option( $this->get_key() );
			$options[ $index ] = current( $value );

			$value = $options;
		}

		return $value;
	}

	/**
	 * Import a single item
	 *
	 * @since 1.0.0
	 * @return bool True on success
	 */
	public function import() {
		if ( $this->get_previous_import() ) {
			return $this->get_previously_imported_error();
		}

		$key = $this->get_key();
		$value = $this->get_value();

		if ( ! $key || ! $value ) {
			return $this->get_default_error();
		}

		$result = update_option( $key, $value );

		if ( ! $result ) {
			return $this->get_default_error();
		}

		return $result;
	}

	/**
	 * Reset a single item
	 *
	 * @since 1.0.0
	 * @return bool True on success
	 */
	public function reset() {
		$option = $this->get_previous_import();

		if ( ! $option ) {
			return $this->get_not_found_error();
		}

		$key = $this->get_key();
		$value = $this->get_value();

		if ( ! $key || ! $value ) {
			return $this->get_default_error();
		}

		if ( false != ( $index = $this->get_option_index() ) ) {
			$options = get_option( $this->get_key() );
			unset( $options[ $index ] );

			$value = $options;

			$result = update_option( $key, $value );
		} else {
			$result = delete_option( $key );
		}

		if ( ! $result ) {
			return $this->get_default_error();
		}

		return $result;
	}

	/**
	 * Retrieve a previously imported item
	 *
	 * @since 1.0.0
	 * @uses $wpdb
	 * @return Theme mod if true, or false
	 */
	public function get_previous_import() {
		$option = get_option( $this->get_key() );

		if ( false != ( $index = $this->get_option_index() ) ) {
			$option = isset( $option[ $index ] ) ? $option[ $index ] : false;
		}

		if ( ! $option || '' == $option ) {
			return false;
		}

		return $option;
	}

}
