<?php

class Marketify_Widget_FES_Vendor_Contact extends Marketify_EDD_FES_Vendor_Widget {

	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_fes_vendor_contact';
		$this->widget_description = __( 'Display the vendor contact form.', 'marketify' );
		$this->widget_id          = 'marketify_widget_fes_vendor_contact';
		$this->widget_name        = __( 'Marketify - Vendor: Contact', 'marketify' );
		$this->settings           = array(
			'extras' => array(
				'type'  => 'description',
				'std' => __( 'This widget has no options.', 'marketify' )
			),
		);
		parent::__construct();
	}

	function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];
		echo do_shortcode( '[fes_vendor_contact_form id="' . $this->vendor->obj->ID . '"]' );
		echo $args[ 'after_widget' ];
	}

}