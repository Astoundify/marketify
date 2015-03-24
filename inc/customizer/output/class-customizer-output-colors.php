<?php

class Marketify_Customizer_Output_Colors {
	
	public function __construct() {
		$this->css = new Marketify_Customizer_CSS;

		add_action( 'marketify_output_customizer_css', array( $this, 'page_header' ), 10 );
		add_action( 'marketify_output_customizer_css', array( $this, 'primary' ), 30 );
		add_action( 'marketify_output_customizer_css', array( $this, 'accent' ), 30 );
		add_action( 'marketify_output_customizer_css', array( $this, 'footer' ), 40 );
		add_action( 'marketify_output_customizer_css', array( $this, 'overlay' ), 40 );
	}

	public function page_header() {
		$page_header_background = marketify_theme_mod( 'color-page-header-background' );

		$this->css->add( array(
			'selectors' => array(
				'.header-outer',
				'.site-footer',
				'body.minimal',
				'body.custom-background.minimal',
				'.minimal .entry-content .edd-slg-social-container span legend'
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
				'.header-outer a.button:hover',
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

		$this->css->add( array(
			'selectors' => array(
				'button',
				'input[type=reset]',
				'input[type=submit]',
				'.button',
				'a.button',
				'.fes-button',
				'.edd_price_options input[type=radio]:checked',
				'body #edd-wl-modal input[type=radio]:checked',
				'#edd_checkout_form_wrap fieldset#edd_cc_fields legend',
				'.marketify_widget_featured_popular .home-widget-title span:hover',
				'.marketify_widget_featured_popular .home-widget-title span.active',
				'.entry-content blockquote',
				'.nav-previous a:hover',
				'.nav-next a:hover',
				'body a.edd-wl-action',
				'body a.edd-wl-action.edd-wl-button',
				'body a.edd-wl-action.edd-wl-button:hover',
				'.fes-feat-image-btn',
				'.upload_file_button',
				'.fes-avatar-image-bt'
			),
			'declarations' => array(
				'border-color' => $primary
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'button:hover',
				'input[type=reset]:hover',
				'input[type=submit]:hover',
				'.button:hover',
				'a.button:hover',
				'#edd_checkout_form_wrap fieldset#edd_cc_fields > span:after',
				'.edd-reviews-voting-buttons a:hover',
				'.flex-control-nav a.flex-active',
				'.search-form .search-submit',
				'.fes-pagination a.page-numbers:hover',
				'body a.edd-wl-action.edd-wl-button:hover',
				'.fes-feat-image-btn:hover',
				'.upload_file_button:hover'
			),
			'declarations' => array(
				'background-color' => $primary
			)
		) );

	}

	public function accent() {
		$accent = marketify_theme_mod( 'color-accent' );
	
		$this->css->add( array(
			'selectors' => array(
				'a.edd-cart-saving-button',
				'input[name=edd_update_cart_submit]',
				'.main-navigation .edd-cart .cart_item.edd_checkout a',
				'.download-variable .entry-content .edd-add-to-cart.button.edd-submit:hover',
				'.download-variable .entry-content .edd_go_to_checkout.button.edd-submit:hover',
				'.popup .edd-add-to-cart.button.edd-submit:hover',
				'.edd-reviews-voting-buttons a',
				'a.edd-fes-adf-submission-add-option-button',
				'#fes-insert-image',
				'#fes-view-comment a',
				'a.edd_terms_link',
			),
			'declarations' => array(
				'color' => $accent
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'a.edd-cart-saving-button',
				'input[name=edd_update_cart_submit]',
				'.main-navigation .edd-cart .cart_item.edd_checkout a:hover',
				'.download-variable .entry-content .edd-add-to-cart.button.edd-submit:hover',
				'.download-variable .entry-content .edd_go_to_checkout.button.edd-submit:hover',
				'.popup .edd-add-to-cart.button.edd-submit:hover',
				'.popup .edd_go_to_checkout.button.edd-submit',
				'.popup .edd_go_to_checkout.button.edd-submit:hover',
				'.edd-reviews-voting-buttons a',
				'.edd-fes-adf-submission-add-option-button',
				'#fes-insert-image',
				'#fes-view-comment a',
				'.edd_terms_links',
				'.site-footer.dark .mailbag-wrap input[type=submit]',
				'.insert-file-row',
			),
			'declarations' => array(
				'border-color' => $accent
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'a.edd-cart-saving-button:hover',
				'input[name=edd_update_cart_submit]:hover',
				'.minimal #edd_purchase_submit input[type=submit]',
				'.main-navigation .edd-cart .cart_item.edd_checkout a:hover',
				'.minimal a.edd-cart-saving-button',
				'.minimal input[name=edd_update_cart_submit]',
				'.minimal .fes-form input[type=submit]',
				'.popup .edd_go_to_checkout.button.edd-submit',
				'.popup .edd_go_to_checkout.button.edd-submit:hover',
				'.main-navigation .search-form.active .search-submit',
				'.main-navigation.toggled .search-form .search-submit',
				'.edd-fes-adf-submission-add-option-button:hover',
				'#fes-insert-image:hover',
				'.edd-reviews-voting-buttons a:hover',
				'.minimal #edd_login_submit',
				'.minimal input[name=edd_register_submit]',
				'.edd_terms_links:hover',
				'.site-footer.dark .mailbag-wrap input[type=submit]',
				'.home-search .page-header .search-submit',
				'.search-form-overlay .search-submit',
				'.marketify_widget_taxonomy_stylized',
				'.insert-file-row',
			),
			'declarations' => array(
				'border-color' => $accent
			)
		) );
	}

	public function footer() {
		$this->css->add( array(
			'selectors' => array(
				'.site-footer.light'
			),
			'declarations' => array(
				'color' => '#' . get_theme_mod( 'background_color' )
			)
		) );
	}

	public function overlay() {
		$primary = marketify_theme_mod( 'color-primary' );

		$this->css->add( array(
			'selectors' => array(
				'.content-grid-download .entry-image:hover .overlay',
				'.content-grid-download .entry-image.hover .overlay',
				'.download-image-grid-preview .slides li.active a:before',
				'.download-image-grid-preview .slides li:hover a:before',
			),
			'declarations' => array(
				'background' => 'rgba(' . $this->css->hex2rgb( $primary ) . ',.80)',
				'border' => '1px solid rgba(' . $this->css->hex2rgb( $primary ) . ',.80)',
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.search-form-overlay',
			),
			'declarations' => array(
				'background-color' => 'rgba(' . $this->css->hex2rgb( $primary ) . ', .90)',
			)
		) );
	}

}

new Marketify_Customizer_Output_Colors();
