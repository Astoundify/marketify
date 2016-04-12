<?php
/**
 * Plugin importer.
 *
 * Handles importing data for a plugin. Will fire off an importer for each type
 * of data in the plugins .json
 *
 * @see Astoundify_Importer
 *
 * @since 1.0.0
 */
class Astoundify_Import_Plugin extends Astoundify_Importer {

	/**
	 * @var $importers
	 */
	public $importers = array();

	/**
	 * Start importer. Set the type, file, and init the rest.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->type = 'plugin';

		$this->setup_importers();
		$this->init();
	}

	/**
	 * Setup importers for each of the items in the file.
	 *
	 * @since 1.0.0
	 */
	public function setup_importers() {
		foreach ( $this->get_data() as $import_type => $import_data ) {
			$classname = 'Astoundify_Import_' . ucfirst( $import_type );

			if ( class_exists( $classname ) ) {
				$this->importers[ $import_type ] = new $classname();
				$this->importers[ $import_type ]->data = $import_data;
			// create a fake importer with the data so it can be accessed elsewhere
			} else {
				$this->importers[ $import_type ] = $import_data;
			}
		}
	}

	public function process_data( $process_action = 'process' ) {
		if ( ! $this->importers ) {
			return;
		}

		foreach ( $this->importers as $import_type => $importer ) {
			if ( method_exists( $importer, 'process_data' ) ) {
				$importer->process_data( $process_action );
			} else {
				// a ghettto way to let random things fire if the import type is "fake"
				do_action( "astoundify_import_content_{$process_action}_{$import_type}", $importer );
			}
		}
	}

}
