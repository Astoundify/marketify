<?php
/**
 * Import a theme mod
 *
 * @uses Astoundify_AbstractItemImport
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
class Astoundify_ItemImport_ThemeMod extends Astoundify_AbstractItemImport implements Astoundify_ItemImportInterface {

	public function __construct( $item ) {
		parent::__construct( $item );
	}

	/**
	 * Get the theme mod key
	 *
	 * @since 1.0.0
	 * @return false|string The key string if it exists. False if it does not.
	 */
	private function get_key() {
		return $this->get_id();
	}

	/**
	 * Get the theme mod value
	 *
	 * @since 1.0.0
	 * @return false|string The value string if it exists. False if it does not.
	 */
	private function get_value() {
		if ( isset( $this->item[ 'data' ] ) ) {
			return $this->item[ 'data' ];
		}

		return false;
	}

	/**
	 * Import a single item
	 *
	 * set_theme_mod does not return a value so we have to manually ensure it was added.
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

		set_theme_mod( $key, $value );

		if ( '' == ( $result = get_theme_mod( $key ) ) ) {
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
		$mod = $this->get_previous_import();

		if ( ! $mod ) {
			return $this->get_not_found_error();
		}

		$key = $this->get_key();
		$value = $this->get_value();

		if ( ! $key || ! $value ) {
			return $this->get_default_error();
		}

		$result = $this->get_default_error();

		remove_theme_mod( $key, $value );

		if ( ! get_theme_mod( $key ) ) {
			$result = true;
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
		$mod = get_theme_mod( $this->get_key() );

		if ( '' == $mod ) {
			return false;
		}

		return $mod;
	}
}
