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
	 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
	 */
	public function process( $process_args ) {
		if ( ! isset( $process_args[ 'item_data' ] ) ) {
			return new WP_Error( 'no-menu-name', 'No menu name was set' );
		}

		$item = wp_create_nav_menu( $process_args[ 'item_data' ] );

		return $item;
	}

	/**
	 * Reset an individual item.
	 *
	 * @param array $process_args Import item context.
	 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
	 */
	public function reset( $process_args ) {
		$item = wp_delete_nav_menu( $process_args[ 'item_data' ] );

		return $item;
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
		$menu_id = $args[ 'processed_item' ];

		if ( is_wp_error( $menu_id ) ) {
			return $menu_id;
		}

		// set
		$locations[ $menu_location ] = $menu_id;

		// update
		set_theme_mod( 'nav_menu_locations', $locations );

		return null;
	}

}
