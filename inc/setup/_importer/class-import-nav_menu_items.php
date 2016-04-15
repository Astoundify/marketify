<?php
/**
 * Nav Menu Item importer.
 *
 * @see Astoundify_Importer
 * @see `tests/data/nav_menus_items.json`
 *
 * @since 1.0.0
 */
class Astoundify_Import_Nav_Menu_Items extends Astoundify_Importer {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'nav_menu_item';
		$this->file = $file;

		$this->init();
	}

	/**
	 * Add any pre/post actions to processing.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 * @codeCoverageIgnore
	 */
	public function setup_actions() {}

	/**
	 * Process an individual item.
	 *
	 * @param array $process_args Import item context.
	 * @return int|WP_Error Menu item ID object on success, WP_Error object on failure.
	 */
	public function process( $process_args ) {
		// verify data before continuing
		if ( ! isset( $process_args[ 'item_data' ] ) ) {
			return new WP_Error( 'no-menu-item-data', 'No menu item data was set.' );
		}

		$menu = wp_get_nav_menu_object( $process_args[ 'item_data' ][ 'menu' ] );

		if ( ! $menu ) {
			return new WP_Error( 'menu-does-not-exist', 'The menu does not exist.' );
		}

		// fill in any missing data
		$menu_item_data = $process_args[ 'item_data' ][ 'menu_item_data' ];
		$menu_item_data = $this->_decorate_menu_item_data( $menu_item_data );

		// create a menu item
		$result = wp_update_nav_menu_item( $menu->term_id, 0, $menu_item_data );

		return $result;
	}

	/**
	 * Reset an individual item.
	 *
	 * Only items cereated with a set `menu-item-title` can be removed.
	 *
	 * @param array $process_args Import item context.
	 * @return object|WP_Error Object containing post data on success, WP_Error object on failure.
	 */
	public function reset( $process_args ) {
		// verify data before continuing
		if ( ! isset( $process_args[ 'item_data' ] ) ) {
			return new WP_Error( 'no-menu-item-data', 'No menu item data was set.' );
		}

		global $wpdb;

		$menu_item_data = $process_args[ 'item_data' ][ 'menu_item_data' ];

		if ( ! isset( $menu_item_data[ 'menu-item-title' ] ) ) {
			return new WP_Error( 'no-menu-title', 'Only menu items with custom titles can be removed.' );
		}

		$result = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = '%s' AND post_type = 'nav_menu_item'", $menu_item_data[ 'menu-item-title' ] ) );

		if ( $result ) {
			$result = wp_delete_post( $result->ID );
		} else {
			$result = new WP_Error( 'not-deleted', 'Menu item not deleted' );
		}

		return $result;
	}

	private function _decorate_menu_item_data( $menu_item_data ) {
		$menu_item_data[ 'menu-item-status' ] = 'publish';

		/**
		 * To set a parent we need to know the ID of the menu item we want as our ancestor.
		 * However we don't have this. So we pass `menu-item-parent-title` and use this to find
		 * the menu item object. Because of this if a menu item is going to have children it
		 * must explicitly set a its own `menu-item-title` so it can be queried against.
		 */
		if ( isset( $menu_item_data[ 'menu-item-parent-title' ] ) ) {
			global $wpdb;

			$parent_item = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = '%s' AND post_type = 'nav_menu_item'", $menu_item_data[ 'menu-item-parent-title' ] ) );

			if ( $parent_item ) {
				$menu_item_data[ 'menu-item-parent-id' ] = $parent_item->ID;
				unset( $menu_item_data[ 'menu-item-parent-title' ] );
			} else {
				unset( $menu_item_data[ 'menu-item-parent-id' ] );
				unset( $menu_item_data[ 'menu-item-parent-title' ] );
			}
		}

		/**
		 * To set a term archive we need to know the ID of the term. However, we don't have this.
		 * So we pass `menu-item-object-title` and use this to find the term object.
		 */
		if ( 'taxonomy' == $menu_item_data[ 'menu-item-type' ] && isset( $menu_item_data[ 'menu-item-object-title' ] ) ) {
			$term = get_term_by( 'name', $menu_item_data[ 'menu-item-object-title' ], $menu_item_data[ 'menu-item-object' ], 'raw' );

			if ( $term ) {
				$menu_item_data[ 'menu-item-object-id' ] = $term->term_id;
				unset( $menu_item_data[ 'menu-item-object-title' ] );
			} else {
				// set to an invalid menu so it fails early
				$menu->term_id = 0;
				unset( $menu_item_data[ 'menu-item-object-title' ] );
			}
		}

		return $menu_item_data;
	}

}
