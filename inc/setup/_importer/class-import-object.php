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
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'set_post_format' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'set_featured_image' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'add_post_meta' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'add_post_terms' ) );
		add_action( 'astoundify_import_content_after_process_item_type_' . $this->type, array( $this, 'add_media' ) );
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
		$result = null;

		if ( ! isset( $process_args[ 'item_type' ] ) ) {
			return $result;
		}

		if ( ! isset( $process_args[ 'item_id' ] ) ) {
			return $result;
		}

		$defaults = array(
			'post_type' => $process_args[ 'item_type' ],
			'post_status' => 'publish',
			'post_title' => $process_args[ 'item_id' ],
			'post_name' => $process_args[ 'item_id' ],
			'post_content' => $this->get_default_content()
		);

		$object_atts = wp_parse_args( $process_args[ 'item_data' ], $defaults );

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

			$attachments = get_children( array( 'post_parent' => $object->ID, 'post_type' => 'attachment' ) );

			foreach ( $attachments as $attachment ){
				wp_delete_post( $attachment->ID, true );
			}
		}

		return $result;
	}

	/**
	 * Set the object format.
	 *
	 * Objects with a `format` property will set a format.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function set_post_format( $args ) {
		if ( ! isset( $args[ 'item_data' ][ 'post_format' ] ) ) {
			return false;
		}

		if ( ! post_type_supports( $args[ 'processed_item' ]->post_type, 'post-formats' ) ) {
			return false;
		}

		$post_id = $args[ 'processed_item' ]->ID;
		$format = $args[ 'item_data' ][ 'post_format' ];

		if ( 'standard' !== $format ) {
			return set_post_format( $post_id, $format );
		}

		return false;
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
		$thumbnail_id = $this->upload_attachment( $args[ 'item_data' ][ 'featured_image' ], $post_id );

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
	 * Add media.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function add_media( $args ) {
		$media = isset( $args[ 'item_data' ][ 'media' ] ) ? $args[ 'item_data' ][ 'media' ] : false;

		if ( ! $media ) {
			return;
		}

		$post_id = $args[ 'processed_item' ]->ID;

		foreach ( $media as $file ) {
			$this->upload_attachment( $file, $post_id );
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

		$nav_menu_items_importer = new Astoundify_Import_Nav_Menu_Items();

		foreach ( $menus as $menu_name => $menu_item_data ) {
			$menu_item_data[ 'menu-item-title' ] = $args[ 'processed_item' ]->post_title;
			$menu_item_data[ 'menu-item-object-id' ] = $args[ 'processed_item' ]->ID;
			$menu_item_data[ 'menu-item-object' ] = $args[ 'processed_item' ]->post_type;
			$menu_item_data[ 'menu-item-type' ] = 'post_type';

			$process_args = array(
				'item_data' => array(
					'menu' => $menu_name,
					'menu_item_data' => $menu_item_data
				)
			);

			$nav_menu_items_importer->process( $process_args );
		}
	}

	/**
	 * Get generated content from a lorem ipsum generator.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_default_content() {
		$url = 'http://www.randomtext.me/api/gibberish/p-5/5-300';
		$response = wp_remote_get( esc_url_raw( $url ) );

		$default = 'Default content';

		if ( is_wp_error( $response ) ) {
			return $default;
		}

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( ! $body[ 'text_out' ] ) {
			return $default;
		}

		return $body[ 'text_out' ];
	}

}
