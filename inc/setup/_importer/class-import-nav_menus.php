<?php
/**
 * Nav Menu importer.
 *
 * @see Astoundify_Importer
 * @see `tests/data/nav_menus.json`
 *
 * Example Import:
 *
 * {
 *   "primary": "Primary Navigation"
 * }
 *
 * Where the key is the menu location name and the value is the menu name.
 *
 * @since 1.0.0
 */
class Astoundify_Import_Nav_Menus extends Astoundify_Importer {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'nav_menu';
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
	public function setup_actions() {
		add_action( 'astoundify_import_content_after_process_item_type_nav_menu', array( $this, 'set_menu_location' ) );
	}

	/**
	 * Process an individual item.
	 *
	 * @param array $process_args Import item context.
	 * @return object|WP_Error Menu object on success, WP_Error object on failure.
	 */
	public function process( $process_args ) {
		if ( ! isset( $process_args[ 'item_data' ] ) ) {
			return new WP_Error( 'no-menu-name', 'No menu name was set' );
		}

		$result = wp_create_nav_menu( $process_args[ 'item_data' ] );

		if ( ! is_wp_error( $result ) ) {
			$result = wp_get_nav_menu_object( $result );

			// wp_get_nav_menu_object() does not return a WP_Error
			if ( ! $result ) {
				$result = new WP_Error( 'cant-get-menu', 'Cannot get created menu' );
			}
		}

		return $result;
	}

	/**
	 * Reset an individual item.
	 *
	 * @param array $process_args Import item context.
	 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
	 */
	public function reset( $process_args ) {
		$result = wp_delete_nav_menu( $process_args[ 'item_data' ] );

		// wp_delete_nav_menu() can return false
		if ( ! $result ) {
			$result = new WP_Error( 'menu-not-deleted', 'Menu not deleted' );
		}

		return $result;
	}

	/**
	 * Set the menu location.
	 *
	 * The $item_id of the imported item represents the menu location.
	 *
	 * @param array $args Import item context.
	 * @return null|WP_Error null on success.
	 */
	public function set_menu_location( $args ) {
		// get existing locations
		$locations = get_theme_mod( 'nav_menu_locations' );

		// get desired location and menu to assign
		$menu_location = $args[ 'item_id' ];
		$menu = $args[ 'processed_item' ];

		if ( is_wp_error( $menu ) ) {
			return $menu;
		}

		// set
		$locations[ $menu_location ] = $menu->term_id;

		// update
		set_theme_mod( 'nav_menu_locations', $locations );

		return null;
	}

}
