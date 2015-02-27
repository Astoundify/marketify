<?php

class Marketify_Page_Header {
	
	public function __construct() {
		add_filter( 'marketify_page_header', array( $this, 'tag_atts' ), 10, 2 );
	}

	public function tag_atts( $args ) {
		$defaults = apply_filters( 'marketify_page_header_defaults', array(
			'classes' => 'header-outer',
			'object_ids' => false,
			'size' => 'large'
		) );

		$args = wp_parse_args( $args, $defaults );
		$atts = $this->build_tag_atts( $args );

		$output = '';

		foreach ( $atts as $attribute => $properties ) {
			$output .= sprintf( '%s="%s"', $attribute, trim( $properties ) );
		}

		return $output;
	}

	private function build_tag_atts( $args ) {
		$atts = array(
			'class' => $args[ 'classes' ],
			'style' => ''
		);

		$atts = $this->add_background_image( $atts, $args );

		return $atts;
	}

	private function add_background_image( $atts, $args ) {
		$background_image = $this->find_background_image( $args );

		if ( $background_image ) {
			$atts[ 'style' ] .= ' background-image:url(' . $background_image . ');';
			$atts[ 'class' ] .= ' has-image';
		} else {
			$atts[ 'class' ] .= ' no-image';
		}

		return $atts;
	}

	private function find_background_image( $args ) {
		$background_image = false;

		if ( is_page() ) {
			$background_image = wp_get_attachment_image_src( get_post_thumbnail_id(), $args[ 'size' ] );
			$background_image = $background_image[0];
		}

		return apply_filters( 'marketify_page_header_image', $background_image, $args );
	}

}
