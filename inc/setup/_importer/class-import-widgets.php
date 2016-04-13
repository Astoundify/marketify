<?php
/**
 * Widget importer.
 *
 * @see Astoundify_Importer
 * @see `tests/data/widgets.json`
 *
 * @see https://plugins.svn.wordpress.org/widget-importer-exporter/trunk/includes/
 *
 * @since 1.0.0
 */
class Astoundify_Import_Widgets extends Astoundify_Importer {

	/**
	 * Get the available widgets.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $available_widgets;

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'widget';
		$this->file = $file;

		$this->available_widgets = $this->_get_available_widgets();

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
	}

	/**
	 * Process an individual item.
	 *
	 * This is a pain. Where is my wp_add_widget()?
	 *
	 * @since 1.0.0
	 *
	 * @global array $wp_registered_sidebars
	 * @param array $process_args Import item context.
	 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
	 */
	public function process( $process_args ) {
		global $wp_registered_sidebars;

		// get site supported widgets
		$available_widgets = $this->_get_available_widgets();

		// get existing widgets
		$widget_instances = array();

		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data[ 'id_base' ] ] = get_option( 'widget_' . $widget_data[ 'id_base' ] );
		}

		$sidebar_id = $process_args[ 'item_data' ][ 'sidebar' ];

		// sidebar isnt registered don't do anything
		if ( ! isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
			return;
		}

		$id_base = $process_args[ 'item_data' ][ 'id_base' ];
		$sidebar_widgets = get_option( 'sidebars_widgets' );

		$single_widget_instances = get_option( 'widget_' . $id_base, array() );
		$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 );

		// remove sidebar from args, this is not a setting
		unset( $process_args[ 'item_data' ][ 'sidebar' ] );
		unset( $process_args[ 'item_data' ][ 'id_base' ] );

		$single_widget_instances[] = $process_args[ 'item_data' ];

		end( $single_widget_instances );
		$new_instance_id_number = key( $single_widget_instances );

		if ( '0' === strval( $new_instance_id_number ) ) {
			$new_instance_id_number = 1;
			$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
			unset( $single_widget_instances[0] );
		}

		// Move _multiwidget to end of array for uniformity
		if ( isset( $single_widget_instances['_multiwidget'] ) ) {
			$multiwidget = $single_widget_instances['_multiwidget'];
			unset( $single_widget_instances['_multiwidget'] );
			$single_widget_instances['_multiwidget'] = $multiwidget;
		}

		// Update option with new widget
		update_option( 'widget_' . $id_base, $single_widget_instances );

		$sidebars_widgets = get_option( 'sidebars_widgets' );
		$new_instance_id = $id_base . '-' . $new_instance_id_number;
		$sidebars_widgets[ $sidebar_id ][] = $new_instance_id;

		update_option( 'sidebars_widgets', $sidebars_widgets );

		return get_option( 'widget_' . $id_base );
	}

	/**
	 * Reset an individual item.
	 *
	 * This isnt really that great because we dont have the widget instance ID.
	 * So if there are multiple of the same widget in a single sidebar the first
	 * one we encounter will be removed.
	 *
	 * @since 1.0.0
	 *
	 * @global array $wp_registered_sidebars
	 * @param array $process_args Import item context.
	 * @return void
	 */
	public function reset( $process_args = false ) {
		$id_base = $process_args[ 'item_data' ][ 'id_base' ];

		// get list of widget settings
		$single_widget_instances = get_option( 'widget_' . $id_base, array() );

		$sidebar_instance_key = false;
		$multi_instance_name = '';
		
		// get the sidebar widgets
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		$sidebars_widgets = $sidebars_widgets[ $process_args[ 'item_data' ][ 'sidebar' ] ];

		// remove the first item we encounter and keep the key
		foreach ( $single_widget_instances as $key => $instance ) {
			if ( ! is_numeric( $key ) ) {
				continue;
			}

			$sidebar_instance_key = $key;
			unset( $single_widget_instances[ $key ] );
			continue;
		}

		// update list of widget settings
		update_option( 'widget_' . $id_base, $single_widget_instances );

		// if we found a key remove it from the sidebar list
		if ( $sidebar_instance_key ) {
			$multi_instance_name = $id_base . '-' . $sidebar_instance_key;

			if ( ( $key = array_search( $multi_instance_name, $sidebars_widgets ) ) !== false ) {
				unset( $sidebars_widgets[ $key ] );
				update_option( 'sidebars_widgets', $sidebars_widgets );
			}
		}
	}

	/**
	 * Get the available widgets.
	 *
	 * @since 1.0.0
	 *
	 * @global array $wp_registered_widget_controls
	 * @return array $available_widgets
	 */
	private function _get_available_widgets() {
		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;

		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget[ 'id_base' ] ) && ! isset( $available_widgets[ $widget[ 'id_base' ] ] ) ) { 
				$available_widgets[ $widget[ 'id_base' ] ][ 'id_base' ] = $widget[ 'id_base' ];
				$available_widgets[ $widget[ 'id_base' ] ][ 'name' ] = $widget[ 'name' ];
			}
		}

		$this->available_widgets = $available_widgets;

		return $this->available_widgets;
	}

}
