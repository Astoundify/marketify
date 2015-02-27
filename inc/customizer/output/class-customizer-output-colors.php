<?php

class Marketify_Customizer_Output_Colors {
	
	public function __construct() {
		$this->css = new Marketify_Customizer_CSS;

		add_action( 'marketify_output_customizer_css', array( $this, 'page_header' ), 10 );
		add_action( 'marketify_output_customizer_css', array( $this, 'primary' ), 30 );
		add_action( 'marketify_output_customizer_css', array( $this, 'accent' ), 30 );
	}

	public function page_header() {
		$page_header_background = marketify_theme_mod( 'color-page-header-background' );

		$this->css->add( array(
			'selectors' => array(
				'.header-outer:not(.custom-featured-image) .site-header',
				'.header-outer:not(.custom-featured-image) .page-header',
				'.site-footer',
				'body.minimal',
				'body.custom-background.minimal',
				'.header-outer',
				'.minimal .entry-content .edd-slg-social-container span legend)'
			),
			'declarations' => array(
				'background-color' => $page_header_background
			)
		) );
	}
	
	public function primary() {
		$primary = marketify_theme_mod( 'color-primary' );

		$this->css->add( array(
			'selectors' => array(
				'button',
				'input[type=reset]',
				'input[type=submit]',
				'.button',
				'a.button',
				'.fes-button',
				'.main-navigation .edd-cart .cart_item.edd_checkout a',
				'.page-header .button:hover',
				'.content-grid-download .button:hover',
				'body .marketify_widget_slider_hero .soliloquy-caption a.button:hover',
				'#edd_checkout_form_wrap fieldset#edd_cc_fields legend',
				'.marketify_widget_featured_popular .home-widget-title span:hover',
				'.marketify_widget_featured_popular .home-widget-title span.active',
				'.nav-previous a:hover i',
				'.nav-next a:hover i',
				'body-footer.light .site-info .site-title',
				'body a.edd-wl-action',
				'body a.edd-wl-action.edd-wl-button',
				'#recaptcha_area .recaptchatable a',
				'#recaptcha_area .recaptchatable a:hover',
				'.fes-feat-image-btn',
				'.upload_file_button',
				'.fes-avatar-image-btn',
			),
			'declarations' => array(
				'color' => $primary
			)
		) );

	}

	public function accent() {
		$accent = marketify_theme_mod( 'color-accent' );
	
	}

}

new Marketify_Customizer_Output_Colors();
