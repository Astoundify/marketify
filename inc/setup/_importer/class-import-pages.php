<?php
/**
 * Page importer.
 *
 * @see Astoundify_Importer
 * @see `tests/data/pages.json`
 *
 * Example Import Item:
 *
 * "about": {
 *   "post_title": "About",
 *   "post_content": "We have a great team here.",
 *   "featured_image": "https://upload.wikimedia.org/wikipedia/commons/4/45/A_small_cup_of_coffee.JPG",
 *   "show_in_menu": [ "primary" ],
 *   "meta": {
 *     "hero_style": "video"
 *   }
 * }
 *
 * @since 1.0.0
 */
class Astoundify_Import_Pages extends Astoundify_Import_Objects {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'page';
		$this->file = $file;

		$this->setup_object_actions();

		parent::__construct();
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
		// set homepage
		add_action( 'astoundify_import_content_after_process_item_home', array( $this, 'set_page_on_front' ) );

		// set blog
		add_action( 'astoundify_import_content_after_process_item_blog', array( $this, 'set_page_for_posts' ) );
	}

	/**
	 * Set the homepage.
	 *
	 * When an item with a key of `home` is processed.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function set_page_on_front( $args ) {
		$post_id = isset( $args[ 'processed_item' ]->ID ) ? $args[ 'processed_item' ]->ID : false;

		if ( ! $post_id ) {
			return;
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post_id );
	}

	/**
	 * Set the blog.
	 *
	 * When an item with a key of `blog` is processed.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function set_page_for_posts( $args ) {
		$post_id = isset( $args[ 'processed_item' ]->ID ) ? $args[ 'processed_item' ]->ID : false;

		if ( ! $post_id ) {
			return;
		}

		update_option( 'page_for_posts', $post_id );
	}

}
