<?php

class Marketify_EDD_Shortcode {

	public function __construct() {
		add_filter( 'shortcode_atts_downloads', array( $this, 'shortcode_atts' ), 10, 3 );
		add_filter( 'edd_download_class', array( $this, 'grid_item_download_class' ), 10, 3 );

		add_filter( 'edd_downloads_list_wrapper_class', array( $this, 'grid_wrapper_class' ), 10, 2 );
		add_filter( 'downloads_shortcode', array( $this, 'grid_wrapper_columns' ), 10, 2 );
		add_filter( 'excerpt_length', array( $this, 'grid_excerpt_length' ) );
	}

	public function shortcode_atts( $out, $pairs, $atts ) {
		$out[ 'excerpt' ]      = 'no';
		$out[ 'full_content' ] = 'no';
		$out[ 'price' ]        = 'no';
		$out[ 'buy_button' ]   = 'no';

		if ( isset( $atts[ 'flat' ] ) && $atts[ 'flat' ] == true ) {
			$out[ 'salvattore' ] = 'no';
		}

		return $out;
	}

	public function grid_item_download_class( $class, $id, $atts ) {
		$classes[] = $class;
		$classes[] = 'content-grid-download';

		return implode( ' ', $classes );
	}

	public function grid_wrapper_class( $class, $atts ) {
		return 'row download-grid-wrapper ' . $class;
	}

	public function grid_wrapper_columns( $output, $atts ) {
		if ( ! isset( $atts[ 'salvattore' ] ) || 'no' != $atts[ 'salvattore' ] ) {
			$output = str_replace( '<div class="edd_downloads_list', '<div data-columns class="edd_downloads_list', $output );
		}

		$output = str_replace( '<div style="clear:both;"></div>', '', $output );

		return $output;
	}

	public function grid_excerpt_length( $length ) {
		if ( 'download' == get_post_type() && ! is_singular( 'download' ) ) {
			return 15;
		}

		return $length;
	}
}
