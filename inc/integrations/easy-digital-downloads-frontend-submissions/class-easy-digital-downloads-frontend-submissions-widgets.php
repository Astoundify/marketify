<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions_Widgets {
	
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ), 20 );
	}

	public function register_widgets() {
		register_widget( 'Marketify_Widget_FES_Vendor' );
		register_widget( 'Marketify_Widget_FES_Vendor_Description' );
		register_widget( 'Marketify_Widget_FES_Product_Details' );
	}

	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Vendor Sidebar', 'marketify' ),
			'id'            => 'sidebar-vendor',
			'before_widget' => '<aside id="%1$s" class="widget vendor-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="vendor-widget-title">',
			'after_title'   => '</h3>',
		) );
	}

}
