<?php
/**
 * Testimonials importer.
 *
 * @see Astoundify_Importer
 *
 * @since 1.0.0
 */
class Astoundify_Import_Plugin_WooThemes_Testimonials extends Astoundify_Import_Plugin {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->file = $file;

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
		$widgets = array( 'widget-testimonials', 'widget-companies' );

		foreach ( $widgets as $widget ) {
			add_action( 
				'astoundify_import_content_after_process_item_' . $widget, 
				array( $this, 'add_widget_settings' ) 
			);
		}
	}

	/**
	 * Assign the relevant setting to the widget.
	 *
	 * Converts the testimonial category slug in to an ID needed by the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public function add_widget_settings( $args ) {
		$widget_settings = get_option( 'widget_' . $args[ 'item_data' ][ 'id_base' ], array() );

		if ( empty( $widget_settings ) ) {
			return false;
		}

		foreach ( $widget_settings as $key => $single_widget_settings ) {
			if ( ! is_int( $key ) ) {
				continue;
			}

			if ( $single_widget_settings[ 'title' ] == $args[ 'item_data' ][ 'title' ] ) {
				$category = get_term_by( 'slug', $args[ 'item_data' ][ 'category' ], 'testimonial-category' );
				$single_widget_settings[ 'category' ] = $category->term_id;

				$widget_settings[ $key ] = $single_widget_settings;
			}
		}	

		update_option( 'widget_' . $args[ 'item_data' ][ 'id_base' ], $widget_settings, true );
	}

}
