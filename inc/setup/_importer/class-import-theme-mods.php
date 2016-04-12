<?php
/**
 * Theme Mod importer.
 *
 * @see Astoundify_Importer
 * @see `tests/data/theme_mods.json`
 *
 * Example Import:
 *
 * {
 *   "color-primary": "#fff000"
 * }
 *
 * @since 1.0.0
 */
class Astoundify_Import_Theme_Mods extends Astoundify_Importer {

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The path to the .json file to import.
	 * @return void
	 */
	public function __construct( $file = false ) {
		$this->type = 'theme_mod';
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
	}

	/**
	 * Process an individual item.
	 *
	 * @param array $process_args Import item context.
	 * @return null|WP_Error Null on success, WP_Error object on failure.
	 */
	public function process( $process_args ) {
		if ( ! is_array( $process_args ) ) {
			return new WP_Error( 'no-process-args', 'No mod key was set.' );
		}

		if ( ! isset( $process_args[ 'item_data' ] ) ) {
			return new WP_Error( 'no-mod-value', 'No mod value was set.' );
		}

		$key = $process_args[ 'item_id' ];
		$value = $process_args[ 'item_data' ];

		set_theme_mod( $key, $value );

		return get_theme_mod( $key );
	}

	/**
	 * Reset an individual item.
	 *
	 * @param array $process_args Import item context.
	 * @return int|WP_Error Menu ID on success, WP_Error object on failure.
	 */
	public function reset( $process_args ) {
		if ( ! isset( $process_args[ 'item_id' ] ) ) {
			return new WP_Error( 'no-mod-key', 'No mod key was set.' );
		}

		if ( ! isset( $process_args[ 'item_data' ] ) ) {
			return new WP_Error( 'no-mod-value', 'No mod value was set.' );
		}

		return remove_theme_mod( $process_args[ 'item_id' ], $process_args[ 'item_data' ] );
	}

}
