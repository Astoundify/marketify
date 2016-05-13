<?php
/**
 * Import an navigation menu
 *
 * @uses Astoundify_AbstractItemImport
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
class Astoundify_ItemImport_NavMenu extends Astoundify_AbstractItemImport implements Astoundify_ItemImportInterface {

	public function __construct( $item ) {
		parent::__construct( $item );
	}

	/**
	 * Get the name of the menu to deal with
	 *
	 * @since 1.0.0
	 * @return bool|string The menu name if set, or false.
	 */
	private function get_menu_name() {
		if ( isset( $this->item[ 'data' ][ 'menu_name' ] ) ) {
			return esc_attr( $this->item[ 'data' ][ 'menu_name' ] );
		}

		return false;
	}

	/**
	 * Import a single item
	 *
	 * @since 1.0.0
	 * @return WP_Term|WP_Error WP_Term menu object on success, WP_Error on failure.
	 */
	public function import() {
		if ( false == ( $menu_name = $this->get_menu_name() ) ) {
			return $this->get_default_error();
		}

		$result = wp_create_nav_menu( $menu_name );

		if ( ! is_wp_error( $result ) ) {
			$result = wp_get_nav_menu_object( $result );

			if ( ! $result ) {
				return $this->get_default_error();
			}
		}

		return $result;
	}
	
	/**
	 * Reset a single object
	 *
	 * @since 1.0.0
	 *
	 * @return int|WP_Error Menu ID on success, WP_Error on failure
	 */
	public function reset() {
		$result = wp_delete_nav_menu( $this->get_menu_name() );

		// wp_delete_nav_menu() can return false instead of WP_Error
		if ( ! $result ) {
			return $this->get_default_error();
		}

		return $result;
	}

}
