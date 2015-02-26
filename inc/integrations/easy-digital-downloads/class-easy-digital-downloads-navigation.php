<?php

class Marketify_Easy_Digital_Downloads_Navigation {
	
	public function __construct() {
		add_filter( 'wp_nav_menu_items', array( $this, 'add_cart_item' ), 10, 2 );
	}

	public function add_cart_item( $items, $args ) {
		if ( 'primary' != $args->theme_location ) {
			return $items;
		}

		ob_start();

		$widget_args = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => ''
		);

		$widget = the_widget( 'edd_cart_widget', array( 'title' => '' ), $widget_args );

		$widget = ob_get_clean();

		$link = sprintf( '<li class="current-cart"><a href="%s"><i class="icon-cart"></i> <span class="edd-cart-quantity">%d</span></a><ul class="sub-menu nav-menu"><li class="widget">%s</li></ul></li>', get_permalink( edd_get_option( 'purchase_page' ) ), edd_get_cart_quantity(), $widget );

		return $link . $items;
	}

}
