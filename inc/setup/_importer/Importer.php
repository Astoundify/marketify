<?php
/**
 * Importer
 *
 * @since 1.0.0
 */
abstract class Astoundify_AbstractImporter implements Astoundify_SortableInterface {

	/**
	 * The order items should be imported
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $item_groups = array( 
		'setting' => array(),
		'theme-mod' => array(),
		'nav-menu' => array(),
		'term' => array(),
		'object' => array(),
		'nav-menu-item' => array(),
		'widget' => array()
	);

	/**
	 * A list of items to import.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $items = array();

	/**
	 * A list of files to parse.
	 *
	 * @since 1.0.0
	 * @access public
	 * @var array
	 */
	public $files = array();

	/**
	 * Set the importer's items
	 *
	 * @since 1.0.0
	 * @param array $items A list of items to set
	 * @return array A list of items
	 */
	public function set_items( $items ) {
		$this->items = $items;

		return $this->items;
	}

	/**
	 * Get the importer's items
	 *
	 * @since 1.0.0
	 * @return array A list of items
	 */
	public function get_items() {
		return $this->items;
	}

	/**
	 * Set the importer's files
	 *
	 * @since 1.0.0
	 * @param array $files A list of files to set
	 * @return array A list of files
	 */
	public function set_files( $files ) {
		$this->files = $files;

		return $this->files;
	}

	/**
	 * Get the importer's files
	 *
	 * @since 1.0.0
	 * @return array A list of files
	 */
	public function get_files() {
		return $this->files;
	}

	/**
	 * Sort the items
	 *
	 * First group and order based on $this->import_groups then sort by priority.
	 *
	 * @since 1.0.0
	 * @return array A sorted list of items
	 */
	public function sort() {
		if ( empty( $this->get_items() ) ) {
			return $this->get_items();
		};

		// group by type
		foreach ( $this->get_items() as $item ) {
			$this->item_groups[ $item[ 'type' ] ][] = $item;
		}

		// sort by priority
		foreach ( $this->item_groups as $type => $items ) {
			uasort( $items, array( $this, 'sort_by_priority' ) );

			$this->item_groups[ $type ] = $items;
		}

		$_items = array();

		foreach ( $this->item_groups as $items ) {
			foreach ( $items as $item ) {
				$_items[] = $item;
			}
		}

		$this->set_items( $_items );

		return $this->get_items();
	}

	/**
	 * Sort by priority
	 *
	 * @since 1.0.0
	 * @param int $a
	 * @param int $b
	 * @return int Sort order
	 */
	public function sort_by_priority( $a, $b ) {
		if ( $a == $b ) {
			return 0;
		}

		return ( $a < $b ) ? -1 : 1;
	}

}
