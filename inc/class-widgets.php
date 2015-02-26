<?php

class Jobify_Widgets {

	public function __construct() {
		$widgets = array(
			'class-widget-callout.php',
			'class-widget-video.php',
			'class-widget-blog-posts.php',
			'class-widget-slider-generic.php',
			'class-widget-stats.php'
		);

		foreach ( $widgets as $widget ) {
			require_once( get_template_directory() . '/inc/widgets/' . $widget );
		}

		if ( ! ( defined( 'RCP_PLUGIN_VERSION' ) || class_exists( 'WooCommerce' ) ) ) {
			require_once( get_template_directory() . '/inc/widgets/class-widget-price-option.php' );
			require_once( get_template_directory() . '/inc/widgets/class-widget-price-table.php' );
		}

		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
	}

	function register_widgets() {
		register_widget( 'Jobify_Widget_Callout' );
		register_widget( 'Jobify_Widget_Video' );
		register_widget( 'Jobify_Widget_Blog_Posts' );
		register_widget( 'Jobify_Widget_Slider_Generic' );
	}

	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'jobify' ),
			'id'            => 'sidebar-blog',
			'description'   => __( 'Choose what should display on blog pages.', 'jobify' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="sidebar-widget-title">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array(
			'name'          => __( 'Homepage Widget Area', 'jobify' ),
			'id'            => 'widget-area-front-page',
			'description'   => __( 'Choose what should display on the custom static homepage.', 'jobify' ),
			'before_widget' => '<section id="%1$s" class="homepage-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="homepage-widget-title">',
			'after_title'   => '</h3>',
		) );

		/*
		 * Figure out how many columns the footer has
		 */
		$the_sidebars = wp_get_sidebars_widgets();
		$footer       = isset ( $the_sidebars[ 'widget-area-footer' ] ) ? $the_sidebars[ 'widget-area-footer' ] : array();
		$count        = count( $footer );
		$count        = floor( 12 / ( $count == 0 ? 1 : $count ) );

		register_sidebar( array(
			'name'          => __( 'Footer Widget Area', 'jobify' ),
			'id'            => 'widget-area-footer',
			'description'   => __( 'Display columns of widgets in the footer.', 'jobify' ),
			'before_widget' => '<aside id="%1$s" class="footer-widget %2$s col-md-' . $count . ' col-sm-6 col-xs-12">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		) );

		if ( ! ( defined( 'RCP_PLUGIN_VERSION' ) || class_exists( 'WooCommerce' ) ) ) {
			register_widget( 'Jobify_Widget_Price_Table' );
			register_widget( 'Jobify_Widget_Price_Option' );

			register_sidebar( array(
				'name'          => __( 'Price Table', 'jobify' ),
				'id'            => 'widget-area-price-options',
				'description'   => __( 'Drag multiple "Price Option" widgets here. Then drag the "Pricing Table" widget to the "Homepage Widget Area".', 'jobify' ),
				'before_widget' => '<div class="col-lg-4 col-md-6 col-sm-12 pricing-table-widget-wrapper"><div id="%1$s" class="pricing-table-widget %2$s">',
				'after_widget'  => '</div></div>'
			) );
		}
	}

}
