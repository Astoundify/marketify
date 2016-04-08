<?php
/**
 * Term importer.
 *
 * @see Astoundify_Importer
 *
 * @since 1.0.0
 */
class Astoundify_Import_Terms extends Astoundify_Importer {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'term';
		$this->file = $file;

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
		// nothing special here
	}

	/**
	 * Import an individual item.
	 *
	 * @since 1.0.0
	 *
	 * @param array $process_args Import item context.
	 * @return (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
	 */
	public function process( $process_args ) {
		$taxonomy = $process_args[ 'item_id' ];
		$terms = isset( $process_args[ 'item_data' ] ) ? $process_args[ 'item_data' ] : false;

		if ( ! $terms || ! taxonomy_exists( $taxonomy ) ) {
			return false;
		}

		// a lazy way of tracking errors
		$inserted = true;

		foreach ( $terms as $term_slug => $term_name ) {
			$term = wp_insert_term( $term_name, $taxonomy );

			if ( is_wp_error( $term ) ) {
				$inserted = false;
			}
		}

		return $inserted;
	}

	/**
	 * Reset an individual item.
	 *
	 * @todo This doesn't seem optimal.
	 *
	 * @since 1.0.0
	 *
	 * @param array $process_args Import item context.
	 * @return (int|WP_Error) The post ID on success. The value 0 or WP_Error on failure.
	 */
	public function reset( $process_args ) {
		global $wpdb;

		$taxonomy = $process_args[ 'item_id' ];
		$terms = isset( $process_args[ 'item_data' ] ) ? $process_args[ 'item_data' ] : false;

		if ( ! $terms || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		// a lazy way of tracking errors
		$deleted = true;

		foreach ( $terms as $term_slug => $term_name ) {
			$term = $wpdb->get_row( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE `slug` = '%s'", $term_slug ) );

			if ( ! $term ) {
				$deleted = false;
				continue;
			}

			$term = wp_delete_term( $term->term_id, $taxonomy );

			if ( ! $term ) {
				$deleted = false;
			}
		}

		return $deleted;
	}

}
