<?php

class Marketify_Easy_Digital_Downloads_Purchase_Form {
	
	public function __construct() {
		add_filter( 'edd_purchase_download_form', array( $this, 'download_form_class' ), 10, 2 );
		add_filter( 'edd_button_colors', array( $this, 'button_colors' ) );
	}

	public function download_form_class( $purchase_form, $args ) {
		$download_id = $args[ 'download_id' ];

		if ( ! $download_id || edd_has_variable_prices( $download_id ) ) {
			return $purchase_form;
		}

		$purchase_form = str_replace( 'class="edd_download_purchase_form"', 'class="edd_download_purchase_form download-variable"', $purchase_form );

		return $purchase_form;
	}

	public function button_colors( $colors ) {
		$unset = array( 'white', 'blue', 'gray', 'red', 'green', 'yellow', 'orange', 'dark-gray' );

		foreach ( $unset as $color ) {
			unset( $colors[ $color ] );
		}

		return $colors;
	}
}
