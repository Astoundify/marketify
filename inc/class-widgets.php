<?php

class Marketify_Widgets {

	public function __construct() {
		$widgets = array(
			'class-widget-blog-posts.php',
			'class-widget-price-option.php',
			'class-widget-price-table.php',
			'class-widget-slider.php',
			'class-widget-feature-callout.php'
		);

		foreach ( $widgets as $widget ) {
			require_once( get_template_directory() . '/inc/widgets/' . $widget );
		}

		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	function register_widgets() {
		register_widget( 'Marketify_Widget_Slider' );
		register_widget( 'Marketify_Widget_Price_Table' );
		register_widget( 'Marketify_Widget_Price_Option' );
		register_widget( 'Marketify_Widget_Recent_Posts' );
		register_widget( 'Marketify_Widget_Feature_Callout' );
	}

	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'marketify' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="widget-title section-title"><span>',
			'after_title'   => '</span></h1>',
		) );

		register_sidebar( array(
			'name'          => __( 'Homepage', 'marketify' ),
			'description'   => __( 'Widgets that appear on the "Homepage" Page Template', 'marketify' ),
			'id'            => 'home-1',
			'before_widget' => '<aside id="%1$s" class="home-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="home-widget-title"><span>',
			'after_title'   => '</span></h1>',
		) );

		/*
		 * Figure out how many columns the footer has
		 */
		$the_sidebars = wp_get_sidebars_widgets();
		$footer       = isset ( $the_sidebars[ 'footer-1' ] ) ? $the_sidebars[ 'footer-1' ] : array();
		$count        = count( $footer );
		$count        = floor( 12 / ( $count == 0 ? 1 : $count ) );

		/* Footer */
		register_sidebar( array(
			'name'          => __( 'Footer', 'marketify' ),
			'description'   => __( 'Widgets that appear in the page footer', 'marketify' ),
			'id'            => 'footer-1',
			'before_widget' => '<aside id="%1$s" class="footer-widget %2$s col-md-' . $count . '">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="footer-widget-title">',
			'after_title'   => '</h1>',
		) );

		/*
		 * Figure out how many columns the price table has
		 */
		$prices = isset ( $the_sidebars[ 'widget-area-price-options' ] ) ? $the_sidebars[ 'widget-area-price-options' ] : array();
		$count = count( $prices );
		$count = floor( 12 / ( $count == 0 ? 1 : $count ) );

		/* Price Table */
		register_sidebar( array(
			'name'          => __( 'Price Table', 'marketify' ),
			'id'            => 'widget-area-price-options',
			'description'   => __( 'Drag multiple "Price Option" widgets here. Then drag the "Pricing Table" widget to the "Homepage" Widget Area.', 'marketify' ),
			'before_widget' => '<div id="%1$s" class="pricing-table-widget %2$s col-lg-' . $count . ' col-md-6">',
			'after_widget'  => '</div>'
		) );
	}

}
