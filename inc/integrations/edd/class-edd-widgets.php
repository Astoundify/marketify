<?php

class Marketify_EDD_Widgets {
	
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ), 20 );
	}

	public function register_widgets() {
		register_widget( 'Marketify_Widget_Recent_Downloads' );
		register_widget( 'Marketify_Widget_Curated_Downloads' );
		register_widget( 'Marketify_Widget_Featured_Popular_Downloads' );
		register_widget( 'Marketify_Widget_Taxonomy_Stylized' );

		register_widget( 'Marketify_Widget_Download_Archive_Sorting' );

		register_widget( 'Marketify_Widget_Download_Details' );
		register_widget( 'Marketify_Widget_Download_Share' );

		unregister_widget( 'edd_product_details_widget' );

	}

	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Shop Sidebar', 'marketify' ),
			'id'            => 'sidebar-download',
			'before_widget' => '<aside id="%1$s" class="widget download-archive-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-archive-widget-title">',
			'after_title'   => '</h1>',
		) );

		register_sidebar( array(
			'name'          => sprintf( __( '%s Sidebar', 'marketify' ), edd_get_label_singular() ),
			'id'            => 'sidebar-download-single',
			'before_widget' => '<aside id="%1$s" class="widget download-single-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-single-widget-title">',
			'after_title'   => '</h1>',
		) );

		register_sidebar( array(
			'name'          => sprintf( __( '%s Comments Sidebar', 'marketify' ), edd_get_label_singular() ),
			'id'            => 'sidebar-download-single-comments',
			'before_widget' => '<aside id="%1$s" class="widget download-single-widget comments %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-single-widget-title">',
			'after_title'   => '</h1>',
		) );
	}

}
