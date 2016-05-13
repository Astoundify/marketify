<?php
/**
 * Import a widget
 *
 * @uses Astoundify_AbstractItemImport
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
class Astoundify_ItemImport_Widget extends Astoundify_AbstractItemImport implements Astoundify_ItemImportInterface {

	public function __construct( $item ) {
		parent::__construct( $item );
	}

	/**
	 * Get the widget ID base
	 *
	 * @since 1.0.0
	 * @return false|string The ID base if set, false if it does not exist
	 */
	private function get_widget_id_base() {
		if ( isset( $this->item[ 'data' ][ 'widget' ] ) ) {
			return $this->item[ 'data' ][ 'widget' ];
		}

		return false;
	}

	/**
	 * Get the sidebar the widget will be assigned to.
	 *
	 * @since 1.0.0
	 * @return false|string The sidebar ID if set, false if it does not exist
	 */
	private function get_sidebar() {
		global $wp_registered_sidebars;

		if ( isset( $this->item[ 'data' ][ 'sidebar' ] ) ) {
			$sidebar = $this->item[ 'data' ][ 'sidebar' ];

			if ( ! isset( $wp_registered_sidebars[ $sidebar ] ) ) {
				return false;
			}

			return $sidebar;
		}

		return false;
	}

	/**
	 * Import a single item
	 *
	 * @since 1.0.0
	 * @return (array|WP_Error) Widget settings on success. WP_Error on failure.
	 */
	public function import() {
		$widget_id_base = $this->get_widget_id_base();
		$sidebar_id = $this->get_sidebar();

		if ( ! $sidebar_id || ! $widget_id_base ) {
			return $this->get_default_error();
		}

		$sidebar_widgets = get_option( 'sidebars_widgets' );

		$single_widget_instances = get_option( 'widget_' . $widget_id_base, array() );
		$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 );

		// save initial data
		$old_data = $this->item[ 'data' ];

		// remove sidebar from args, this is not a setting
		unset( $this->item[ 'data' ][ 'sidebar' ] );
		unset( $this->item[ 'data' ][ 'widget' ] );

		$single_widget_instances[] = $this->item[ 'data' ];

		// restore all data
		$this->item[ 'data' ] = $old_data;

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
		update_option( 'widget_' . $widget_id_base, $single_widget_instances );

		$sidebars_widgets = get_option( 'sidebars_widgets', array() );
		$new_instance_id = $widget_id_base . '-' . $new_instance_id_number;
		$sidebars_widgets[ $sidebar_id ][] = $new_instance_id;

		update_option( 'sidebars_widgets', $sidebars_widgets );

		$widgets = get_option( 'widget_' . $widget_id_base );

		$result = $this->get_default_error();

		if ( isset( $widgets[ $new_instance_id_number ] ) ) {
			$result = $widgets[ $new_instance_id_number ];
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
		$widget_id_base = $this->get_widget_id_base();
		$sidebar_id = $this->get_sidebar();

		if ( ! $sidebar_id || ! $widget_id_base ) {
			return $this->get_default_error();
		}
		
		// get list of widget settings
		$single_widget_instances = get_option( 'widget_' . $widget_id_base, array() );

		$sidebar_instance_key = false;
		$multi_instance_name = '';
		
		// get the sidebar widgets
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		$sidebar_widgets = $sidebars_widgets[ $sidebar_id ];

		// remove the first item we encounter and keep the key
		foreach ( $single_widget_instances as $key => $instance ) {
			if ( ! is_numeric( $key ) ) {
				continue;
			}

			$sidebar_instance_key = $key;
			unset( $single_widget_instances[ $key ] );
			break;
		}

		// update list of widget settings
		update_option( 'widget_' . $widget_id_base, $single_widget_instances );

		// if we found a key remove it from the sidebar list
		if ( $sidebar_instance_key ) {
			$multi_instance_name = $widget_id_base . '-' . $sidebar_instance_key;

			if ( ( $key = array_search( $multi_instance_name, $sidebar_widgets ) ) !== false ) {
				unset( $sidebar_widgets[ $key ] );

				$sidebars_widgets[ $sidebar_id ] = $sidebar_widgets;
				update_option( 'sidebars_widgets', $sidebars_widgets );
			}
		}

		$result = $this->get_default_error();

		if ( ! array_search( $multi_instance_name, $sidebars_widgets[ $sidebar_id ] ) ) {
			$result = true;
		}

		return $result;
	}

}