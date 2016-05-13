<?php
/**
 * Extra procsesing for Frontend Submissions
 *
 * @since 1.0.0
 */
class Astoundify_Plugin_FrontendSubmissions implements Astoundify_PluginInterface {

	/**
	 * Initialize the plugin processing
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		self::setup_actions();
	}

	/**
	 * Add any pre/post actions to processing.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function setup_actions() {
		$pages = array( 'vendor', 'vendor-dashboard' );

		foreach ( $pages as $page ) {
			add_action( 
				'astoundify_import_content_after_import_item_' . $page, 
				array( __CLASS__, 'add_page_option' ) 
			);

			add_action( 
				'astoundify_import_content_after_reset_item_' . $page, 
				array( __CLASS__, 'delete_page_option' ) 
			);
		}
	}

	/**
	 * Assign the relevant setting.
	 *
	 * @since 1.0.0
	 * @param array $args Import item context.
	 * @return void
	 */
	public static function add_page_option( $ItemImport ) {
		edd_update_option( "fes-{$ItemImport->get_id()}-page", $ItemImport->get_processed_item()->ID );
	}

	/**
	 * Delete the relevant setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Import item context.
	 * @return void
	 */
	public static function delete_page_option( $ItemImport ) {
		edd_delete_option( "fes-{$ItemImport->get_id()}-page" );
	}

}

Astoundify_Plugin_FrontendSubmissions::init();
