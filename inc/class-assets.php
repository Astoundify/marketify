<?php

class Marketify_Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_filter( 'mce_css', array( $this, 'mce_css' ) );
	}

	public function enqueue_scripts() {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script( 'marketify', get_template_directory_uri() . '/js/marketify.min.js', array( 'jquery' ), '20140908', true );
	}

	public function enqueue_styles() {
		/* do_action( 'jobify_output_customizer_css' ); */

		$fonts_url = $this->google_fonts_url();

		if ( ! empty( $fonts_url ) ) {
			wp_enqueue_style( 'marketify-fonts', esc_url_raw( $fonts_url ), array(), null );
		}

		wp_enqueue_style( 'marketify-base', get_template_directory_uri() . '/style.css' );
		/* $jobify_customizer_css = new Jobify_Customizer_CSS(); */
		/* $jobify_customizer_css->output(); */
	}

	public function mce_css( $mce_css ) {
		$fonts_url = $this->google_fonts_url();

		if ( empty( $fonts_url ) )
			return $mce_css;

		if ( ! empty( $mce_css ) )
			$mce_css .= ',';

		$mce_css .= esc_url_raw( str_replace( ',', '%2C', $fonts_url ) );

		return $mce_css;
	}

	private function google_fonts_url() {
		$fonts_url = '';

		/* Translators: If there are characters in your language that are not
		 * supported by Source Sans Pro, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'marketify' );

		/* Translators: If there are characters in your language that are not
		 * supported by Roboto Slab, translate this to 'off'. Do not translate into your
		 * own language.
		 */
		$roboto = _x( 'on', 'Roboto Slab font: on or off', 'marketify' );

		/* Translators: If there are characters in your language that are not
		 * supported by Montserrat, translate this to 'off'. Do not translate into your
		 * own language.
		 */
		$montserrat = _x( 'on', 'Montserrat font: on or off', 'marketify' );

		if ( 'off' !== $source_sans_pro || 'off' !== $roboto || 'off' !== $montserrat ) {
			$font_families = array();

			if ( 'off' !== $source_sans_pro )
				$font_families[] = apply_filters( 'marketify_font_source_sans', 'Source Sans Pro:300,400,700,300italic,400italic,700italic' );

			if ( 'off' !== $roboto )
				$font_families[] = apply_filters( 'marketify_font_roboto', 'Roboto Slab:300,400' );

			if ( 'off' !== $montserrat )
				$font_families[] = apply_filters( 'marketify_font_montserrat', 'Montserrat:400,700' );

			$query_args = array(
				'family' => urlencode( implode( '|', apply_filters( 'marketify_font_families', $font_families ) ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
		}

		return $fonts_url;
	}

}
