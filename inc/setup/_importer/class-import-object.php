<?php
/**
 * Object importer.
 *
 * The base object importer for simple object types (posts, pages, custom post types)
 *
 * @see Astoundify_Importer
 *
 * @since 1.0.0
 */
class Astoundify_Import_Object extends Astoundify_Importer {

	/**
	 * Start importer.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
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
	public function setup_object_actions() {
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'set_featured_image' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'add_post_meta' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'add_post_terms' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'set_menu_item' ) );
	}

	/**
	 * Process an individual item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $process_args Import item context.
	 * @return (WP_Post|null) A WP_Post instance on success.
	 */
	public function process( $process_args ) {
		$defaults = array(
			'post_type' => $process_args[ 'item_type' ],
			'post_status' => 'publish',
			'post_title' => $process_args[ 'item_id' ],
			'post_name' => $process_args[ 'item_id' ],
			'post_content' => $process_args[ 'item_id' ]
		);

		$object_atts = wp_parse_args( $process_args[ 'item_data' ], $defaults );

		$result = null;

		$object_id = wp_insert_post( $object_atts );

		if ( $object_id ) {
			$result = get_post( $object_id );
		}

		return $result;
	}

	/**
	 * Reset an individual item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $process_args Import item context.
	 * @return false|WP_Post False on failure.
	 */
	public function reset( $process_args ) {
		global $wpdb;

		$object = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = '%s' AND post_type = '%s'", $process_args[ 'item_id' ], $process_args[ 'item_type' ] ) );

		$result = false;

		if ( $object ) {
			$result = wp_delete_post( $object->ID, true );
		}

		return $result;
	}

	/**
	 * Set a featured image.
	 *
	 * Objects with a `featured_image` property will be uploaded and
	 * set as the posts thumbnail.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function set_featured_image( $args ) {
		if ( ! isset( $args[ 'item_data' ][ 'featured_image' ] ) ) {
			return;
		}

		$post_id = $args[ 'processed_item' ]->ID;
		$thumbnail_id = $this->upload_media( $args[ 'item_data' ][ 'featured_image' ], $post_id );

		set_post_thumbnail( $post_id, $thumbnail_id );
	}

	/**
	 * Add the associated meta.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function add_post_meta( $args ) {
		$meta = isset( $args[ 'item_data' ][ 'meta' ] ) ? $args[ 'item_data' ][ 'meta' ] : false;

		if ( ! $meta ) {
			return;
		}

		foreach ( $meta as $meta_key => $meta_value ) {
			// should probably sanitize
			add_post_meta( $args[ 'processed_item' ]->ID, $meta_key, $meta_value );
		}
	}

	/**
	 * Add the associated terms.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function add_post_terms( $args ) {
		$terms = isset( $args[ 'item_data' ][ 'terms' ] ) ? $args[ 'item_data' ][ 'terms' ] : false;

		if ( ! $terms ) {
			return;
		}

		foreach ( $terms as $taxonomy => $terms ) {
			wp_set_object_terms( $args[ 'processed_item' ]->ID, $terms, $taxonomy, false );
		}
	}

	/**
	 * Add an object to a menu.
	 *
	 * Objects with a `show_in_menu` property will be asssigned to a menu.
	 *
	 * "show_in_menu": { 
	 *   "primary" : {
	 *     "menu-item-position": 1,
	 *     "menu-item-type": "post_type"
	 *   }
	 * }
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function set_menu_item( $args ) {
		$menus = isset( $args[ 'item_data' ][ 'show_in_menu' ] ) ? $args[ 'item_data' ][ 'show_in_menu' ] : false;

		// if this should not appear, bail
		if ( ! $menus ) {
			return;
		}

		foreach ( $menus as $menu_location => $menu_item_data ) {
			// get the previously imported menu location (navs have to be imported first)
			$imported_menu = wp_get_nav_menu_object( $menu_location );

			if ( ! $imported_menu ) {
				continue;
			}

			$object_type = '';

			if ( 'post_type' == $menu_item_data[ 'menu-item-type' ] ) {
				$object_type = $args[ 'processed_item' ]->post_type;
			}

			$menu_item_data = wp_parse_args( $menu_item_data, array(
				'menu-item-object-id' => $args[ 'processed_item' ]->ID,
				'menu-item-object' => $object_type,
				'menu-item-title' => $args[ 'processed_item' ]->post_title,
				'menu-item-status' => 'publish'
			) );

			// set the menu item
			wp_update_nav_menu_item( $imported_menu->term_id, 0, $menu_item_data );
		}
	}

}
