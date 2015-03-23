<?php

class Marketify_Template_Page_Header {
	
	public function __construct() {
		add_filter( 'marketify_page_header', array( $this, 'tag_atts' ), 10, 2 );
		add_action( 'marketify_entry_before', array( $this, 'close_header_outer' ) );
		
		add_action( 'marketify_entry_before', array( $this, 'singular_object_title' ), 9 );
	}

	public function close_header_outer() {
		echo '</div>';
	}

	public function singular_object_title() {
		if ( ! is_singular( array( 'post', 'page' ) ) ) {
			return;
		}
	?>
		<div class="entry-page-title container">
			<?php get_template_part( 'content', 'author' ); ?>

			<h1 class="entry-title"><?php the_title(); ?></h1>

			<?php
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
				if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
					$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';

				if ( is_singular( 'post' ) )
					printf( $time_string,
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date() ),
						esc_attr( get_the_modified_date( 'c' ) ),
						esc_html( get_the_modified_date() )
					);
			?>
		</div>
	<?php
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
