<?php
/**
 * Import manager
 *
 * @since 1.0.0
 */
class Astoundify_ImportManager {

	public static function init() {
		add_action( 'wp_ajax_astoundify_importer_stage', array( __CLASS__, 'stage' ) );
		add_action( 'wp_ajax_astoundify_importer_iterate_item', array( __CLASS__, 'ajax_iterate_item' ) );
	}

	public static function stage() {
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

	public static function ajax_iterate_item() {
		if ( ! current_user_can( 'import' ) ) {
			wp_die( '-1' );
		}

		$iterate_action = $_POST[ 'iterate_action' ];
		$item = $_POST[ 'item' ];

		$item = Astoundify_ItemImportFactory::create( $item );
		$item->set_action( $iterate_action );
		$item = $item->iterate();

		if ( ! $item ) {
			wp_send_json_error( 'Item failed to import.' );
		}

		if ( ! is_wp_error( $item ) ) {
			wp_send_json_success( array( 'item' => $item ) );
		} else {
			wp_send_json_error( $item->get_error_message() );
		}
	}

}

Astoundify_ImportManager::init();
