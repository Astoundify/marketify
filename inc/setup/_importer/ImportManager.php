<?php
/**
 * Import manager
 *
 * @since 1.0.0
 */
class Astoundify_ImportManager {

	public static function init() {
		add_action( 'wp_ajax_astoundify_importer_stage', array( __CLASS__, 'ajax_stage_import' ) );
		add_action( 'wp_ajax_astoundify_importer_iterate_item', array( __CLASS__, 'ajax_iterate_item' ) );
	}

	/**
	 * AJAX Stage an import from a group of files.
	 *
	 * Sort and group import items and provide relevant information that
	 * can be used to construct a UI.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function ajax_stage_import() {
		$files = array_map( 'esc_url', $_POST[ 'files' ] );

		$importer = Astoundify_ImporterFactory::create( $files );

		if ( ! is_wp_error( $importer ) ) {
			$importer->stage();

			wp_send_json_success( array(
				'total' => count( $importer->get_items() ),
				'groups' => $importer->item_groups,
				'items' => $importer->get_items(),
			) );
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * AJAX iterate a single item.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function ajax_iterate_item() {
		if ( ! current_user_can( 'import' ) ) {
			wp_send_json_error( 'You do not have permission to import.' );
		}

		$iterate_action = esc_attr( $_POST[ 'iterate_action' ] );

		if ( ! in_array( $iterate_action, array( 'import', 'reset' ) ) ) {
			wp_send_json_error( 'Invalid operation.' );
		}

		$item = $_POST[ 'item' ];

		$item = Astoundify_ItemImportFactory::create( $item );

		if ( is_wp_error( $item ) ) {
			wp_send_json_error( 'Invalid import type.' );
		}

		$item = $item->iterate( $iterate_action );

		if ( ! $item ) {
			wp_send_json_error( 'Item failed to import.' );
		}

		if ( ! is_wp_error( $item->get_processed_item() ) ) {
			wp_send_json_success( array( 'item' => $item ) );
		} else {
			wp_send_json_error( $item->get_processed_item()->get_error_message() );
		}
	}

}

Astoundify_ImportManager::init();
