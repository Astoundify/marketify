<?php
/**
 * Import a term
 *
 * @uses Astoundify_AbstractItemImport
 * @implements Astoundify_ItemImportInterface
 *
 * @since 1.0.0
 */
class Astoundify_ItemImport_Term extends Astoundify_AbstractItemImport implements Astoundify_ItemImportInterface {

	public function __construct( $item ) {
		parent::__construct( $item );
	}

	/**
	 * Get the taxonomy the term is associated with
	 *
	 * @since 1.0.0
	 * @return string|false The taxonomy slug or false if the taxonomy does not exist
	 */
	private function get_taxonomy() {
		if ( isset( $this->item[ 'data' ][ 'taxonomy' ] ) ) {
			$tax = $this->item[ 'data' ][ 'taxonomy' ];

			return taxonomy_exists( $tax ) ? $tax : false;
		}

		return false;
	}

	/**
	 * Import a single item
	 *
	 * @since 1.0.0
	 * @return (WP_Term|WP_Error) WP_Term on success. WP_Error on failure.
	 */
	public function import() {
		$taxonomy = $this->get_taxonomy();

		if ( ! $taxonomy ) {
			return $this->get_default_error();
		}

		$result = wp_insert_term( $this->item[ 'data' ][ 'name' ], $taxonomy );

		if ( ! is_wp_error( $result ) ) {
			$result = get_term( $result[ 'term_id' ], $taxonomy );
		}

		return $result;
	}

	/**
	 * Reset a single item
	 *
	 * @since 1.0.0
	 * @return (true|WP_Error) True on success, WP_Erorr on failure
	 */
	public function reset() {
		$taxonomy = $this->get_taxonomy();

		if ( ! $taxonomy ) {
			return $this->get_default_error();
		}

		global $wpdb;

		if ( ! isset( $this->item[ 'data' ][ 'name' ] ) ) {
			return $this->get_default_error();
		}

		$term_name = $this->item[ 'data' ][ 'name' ];
		$term = get_term_by( 'name', $term_name, $taxonomy );

		if ( ! $term ) {
			return $this->get_default_error();
		}

		$result = wp_delete_term( $term->term_id, $taxonomy );

		if ( is_wp_error( $result ) || ! $result || 0 == $result ) {
			return $this->get_default_error();
		}

		return $result;
	}

}
