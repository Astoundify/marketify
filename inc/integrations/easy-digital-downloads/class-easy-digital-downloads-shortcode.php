<?php

class Marketify_Easy_Digital_Download_Shortcode {

	public function __construct() {
		add_filter( 'shortcode_atts_downloads', array( $this, 'shortcode_atts' ) );
		add_filter( 'edd_downloads_list_wrapper_class', array( $this, 'wrapper_class' ), 10, 2 );
		add_filter( 'downloads_shortcode', array( $this, 'grid_columns' ) );
	}

	public function shortcode_atts( $atts ) {
		$atts[ 'excerpt' ]      = 'no';
		$atts[ 'full_content' ] = 'no';
		$atts[ 'price' ]        = 'no';
		$atts[ 'buy_button' ]   = 'no';
		$atts[ 'columns' ] = 9999;

		return $atts;
	}

	public function wrapper_class( $class, $atts ) {
		$columns = marketify_theme_mod( 'product-display', 'product-display-columns' );

		return 'row download-grid-wrapper columns-' . $columns . ' ' . $class;
	}

	public function grid_columns( $output ) {
		$output = str_replace( '<div class="edd_downloads_list', '<div data-columns class="edd_downloads_list', $output );

		return $output;
	}

}
