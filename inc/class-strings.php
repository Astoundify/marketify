<?php

class Marketify_Strings {

	public $strings;
	public $domains;
	public $labels;

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'filters' ) );
	}

	public function filters() {
		$this->strings = $this->get_strings();
		$this->translations = get_translations_for_domain( 'marketify' );
		$this->domains = apply_filters( 'marketify_gettext_domains', array() );

		add_filter( 'gettext', array( $this, 'gettext' ), 0, 3 );
		add_filter( 'gettext_with_context', array( $this, 'gettext_with_context' ), 0, 4 );
		add_filter( 'ngettext', array( $this, 'ngettext' ), 0, 5 );
	}

	public function get_labels() {
		$this->labels = array(
			'singular' => 'Singular Label',
			'plural' => 'Plural Label'
		);

		return $this->labels;
	}

	private function translate_string( $string ) {
		$value = $string;
		$array = is_array( $value );
		
		$to_translate = $array ? $value[0] : $value;	

		$translated = $this->translate( $to_translate );

		if ( ! $translated ) {
			return $string;
		}

		if ( $array ) {
			$translated = vsprintf( $translated, $value[1] );
		}

		return $translated;
	}

	private function translate( $text ) {
		$translations = $this->translations->translate( $text );

		return $translations;
	}

	private function translate_plural( $single, $plural, $number ) {
		$translation = $this->translations->translate_plural( $single, $plural, $number );

		return $translation;
	}

	public function gettext( $translated, $original, $domain ) {
		if ( ! in_array( $domain, $this->domains ) ) {
			return $translated;
		}

		if ( isset( $this->strings[$domain][$original] ) ) {
			return $this->translate_string( $this->strings[$domain][$original] );
		} else {
			return $translated;
		}
	}

	public function gettext_with_context( $translated, $original, $context, $domain ) {
		if ( ! in_array( $domain, $this->domains ) ) {
			return $translated;
		}

		if ( isset( $this->strings[$domain][$original] ) ) {
			return $this->translate_string( $this->strings[$domain][$original] );
		} else {
			return $translated;
		}
	}

	public function ngettext( $original, $single, $plural, $number, $domain ) {
		if ( ! in_array( $domain, $this->domains ) ) {
			return $original;
		}

		if ( isset ( $this->strings[$domain][$single] ) ) {
			$base = $this->strings[$domain][$single];
			$single = $base[0];
			$plural = $base[1];

			return $this->translate_plural( $single, $plural, $number );
		} else {
			return $original;
		}
	}

	private function get_strings() {
		$strings = array();

		$this->strings = apply_filters( 'marketify_strings', $strings );

		return $this->strings;
	}

}
