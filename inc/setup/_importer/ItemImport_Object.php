<?php
/**
 * Import an object
 *
 * @uses Astoundify_AbstractItemImport
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
class Astoundify_ItemImport_Object extends Astoundify_AbstractItemImport implements Astoundify_ItemImportInterface {

	public function __construct( $item ) {
		parent::__construct( $item );
	}

	/**
	 * Add any pre/post actions to processing.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function setup_actions() {
		// add extra object components
		$actions = array( 
			'set_post_format',
			'set_featured_image',
			'set_post_meta',
			'set_post_terms',
			'set_post_media',
			'set_menu_item'
		);

		foreach ( $actions as $action ) {
			$tag = 'astoundify_import_content_after_import_item_type_' . $this->get_type();

			if ( ! has_action( $tag, array( $this, $action ) ) ) {
				add_action( $tag, array( $this, $action ) );
			}
		}

		// set homepage and blog
		add_action( 
			'astoundify_import_content_after_import_item_home',
			array( $this, 'set_page_on_front' ) 
		);

		add_action( 
			'astoundify_import_content_after_import_item_blog',
			array( $this, 'set_page_for_posts' ) 
		);
	}

	/**
	 * Import a single item
	 *
	 * @since 1.0.0
	 * @return (WP_Post|WP_Error)
	 */
	public function import() {
		$defaults = array(
			'post_type' => 'object' == $this->get_type() ? 'post' : $this->item[ 'data' ][ 'post_type' ],
			'post_status' => 'publish',
			'post_name' => $this->item[ 'id' ],
			'post_content' => Astoundify_Utils::get_lipsum_content()
		);

		$object_atts = wp_parse_args( $this->item[ 'data' ], $defaults );

		$object_id = wp_insert_post( $object_atts );

		$result = $this->get_default_error();

		if ( $object_id && 0 !== $object_id ) {
			$result = get_post( $object_id );
		}

		return $result;
	}
	
	/**
	 * Reset a single item
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Error|WP_Post
	 */
	public function reset() {
		global $wpdb;

		$object = $wpdb->get_row( $wpdb->prepare( 
			"SELECT ID FROM $wpdb->posts WHERE post_name = '%s' AND post_type = '%s'", 
			$this->get_id(),
			$this->get_type()
		) );

		if ( ! $object ) {
			return $this->get_default_error();
		}

		$result = wp_delete_post( $object->ID, true );

		if ( ! $result ) {
			return $this->get_default_error();
		}

		return $result;
	}

	/**
	 * Set the object's format
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public function set_post_format() {
		$error = new WP_Error( 
			'set-post-format', 
			sprintf( 'Format for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$format = false;

		if ( isset( $this->item[ 'data' ][ 'post_format' ] ) ) {
			$format = $this->item[ 'data' ][ 'post_format' ];
		}

		if ( ! $format ) {
			return $error;
		}

		$format = esc_attr( $format );

		if ( post_type_supports( $object->post_type, 'post-formats' ) ) {
			return set_post_format( $object->ID, $format );
		}

		return $error;
	}

	/**
	 * Set the featured image
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the format can be set.
	 */
	public function set_featured_image() {
		$error = new WP_Error( 
			'set-post-featured-image', 
			sprintf( 'Featured image for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$featured_image = false;

		if ( isset( $this->item[ 'data' ][ 'featured_image' ] ) ) {
			$featured_image = $this->item[ 'data' ][ 'featured_image' ];
		}

		if ( ! $featured_image ) {
			return $error;
		}

		$featured_image = esc_url( $featured_image );

		$image_id = Astoundify_Utils::upload_asset( $featured_image, $object->ID );

		if ( $image_id ) {
			return set_post_thumbnail( $object->ID, $image_id );
		}

		return $error;
	}

	/**
	 * Set post meta
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if all meta can be set
	 */
	public function set_post_meta() {
		$error = new WP_Error( 
			'set-post-meta', 
			sprintf( 'Meta for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$meta = false;

		if ( isset( $this->item[ 'data' ][ 'meta' ] ) ) {
			$meta = $this->item[ 'data' ][ 'meta' ];
		}

		if ( ! $meta ) {
			return $error;
		}

		$passed = true;

		foreach ( $meta as $k => $v ) {
			$k = sanitize_key( $k );
			$v = sanitize_meta( $k, $v, 'post' );

			$passed = add_post_meta( $object->ID, $k, $v, true );
		}

		if ( $passed ) {
			return true;
		}

		return $error;
	}

	/**
	 * Set post terms
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the terms can be set
	 */
	public function set_post_terms() {
		$error = new WP_Error( 
			'set-post-terms', 
			sprintf( 'Terms for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$terms = false;

		if ( isset( $this->item[ 'data' ][ 'terms' ] ) ) {
			$terms = $this->item[ 'data' ][ 'terms' ];
		}

		if ( ! $terms ) {
			return $error;
		}

		$passed = true;

		foreach ( $terms as $tax => $terms ) {
			if ( ! taxonomy_exists( $tax ) ) {
				$passed = false;
				continue;
			}

			$passed = wp_set_object_terms( $object->ID, $terms, $tax, false );
		}

		if ( $passed && ! is_wp_error( $passed ) ) {
			return true;
		}

		return $error;
	}

	/**
	 * Set post media
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the media was added
	 */
	public function set_post_media() {
		$error = new WP_Error( 
			'set-post-media', 
			sprintf( 'Media for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$media = false;

		if ( isset( $this->item[ 'data' ][ 'media' ] ) ) {
			$media = $this->item[ 'data' ][ 'media' ];
		}

		if ( ! $media ) {
			return $error;
		}

		$passed = true;

		foreach ( $media as $file ) {
			$file = esc_url( $file );

			$passed = Astoundify_Utils::upload_asset( $file, $object->ID );
		}

		if ( $passed ) {
			return true;
		}

		return $error;
	}

	/*
	 * Set menu item
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the media was added
	 */
	public function set_menu_item() {
		$error = new WP_Error( 
			'set-menu-item', 
			sprintf( 'Menu item for %s was not set', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$menus = false;

		if ( isset( $this->item[ 'data' ][ 'menus' ] ) ) {
			$menus = $this->item[ 'data' ][ 'menus' ];
		}

		if ( ! $menus ) {
			return $error;
		}

		$passed = true;

		foreach ( $menus as $menu => $args ) {
			if ( ! isset( $args[ 'menu-item-title' ] ) ) {
				$args[ 'menu-item-title' ] = $object->post_title;
			}

			$args[ 'menu-item-object' ] = $object->post_type;
			$args[ 'menu-item-object-id' ] = $object->ID;
			$args[ 'menu-item-type' ] = 'post_type';
			$args[ 'menu_name' ] = $menu;

			// mock out a menu item that can be imported
			$item = array(
				'id' => $this->get_id() . '-nav-menu-item',
				'type' => 'nav-menu-item',
				'data' => $args
			);

			$item = Astoundify_ItemImportFactory::create( $item );
			$item->set_action( 'import' );

			$passed = $item->iterate();
		}

		if ( $passed ) {
			return true;
		}

		return $error;
	}
	
	/**
	 * Set the homepage.
	 *
	 * When an item with a key of `home` is processed.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the media was added
	 */
	public function set_page_on_front() {
		$error = new WP_Error( 
			'set-homepage', 
			sprintf( 'Page %s was not set as homepage', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$post_id = $object->ID;

		if ( $post_id ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $post_id );

			return true;
		}

		return $error;
	}

	/**
	 * Set the blog.
	 *
	 * When an item with a key of `blog` is processed.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error True if the media was added
	 */
	public function set_page_for_posts() {
		$error = new WP_Error( 
			'set-blog', 
			sprintf( 'Page %s was not set as blog', $this->get_id() )
		);

		// only work with a valid processed object
		$object = $this->get_processed_item();

		if ( is_wp_error( $object ) ) {
			return $error;
		}

		$post_id = $object->ID;

		if ( $post_id ) {
			update_option( 'page_for_posts', $post_id );

			return true;
		}

		return $error;
	}

}
