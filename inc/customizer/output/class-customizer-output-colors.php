<?php

class Jobify_Customizer_Output_Colors {
	
	public function __construct() {
		$this->css = new Jobify_Customizer_CSS;

		add_action( 'jobify_output_customizer_css', array( $this, 'header' ), 10 );
		add_action( 'jobify_output_customizer_css', array( $this, 'navigation' ), 20 );
		add_action( 'jobify_output_customizer_css', array( $this, 'primary' ), 30 );
		add_action( 'jobify_output_customizer_css', array( $this, 'accent' ), 30 );
		add_action( 'jobify_output_customizer_css', array( $this, 'cta' ), 40 );
	}

	public function header() {
		$header_background = jobify_theme_mod( 'color-header-background' );

		$this->css->add( array(
			'selectors' => array(
				'.site-header',
				'.nav-menu-primary .sub-menu',
			),
			'declarations' => array(
				'background' => $header_background
			)
		) );
	}
	
	public function navigation() {
		$navigation = jobify_theme_mod( 'color-navigation-text' );
		$header_background = jobify_theme_mod( 'color-header-background' );

		$this->css->add( array(
			'selectors' => array(
				'.nav-menu-primary ul li a',
				'.nav-menu-primary li a',
				'.primary-menu-toggle i',
				'.site-primary-navigation .primary-menu-toggle',
				'.site-primary-navigation #searchform input[type="text"]',
				'.site-primary-navigation #searchform button',
			),
			'declarations' => array(
				'color' => $navigation
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.nav-menu-primary li.login > a',
				'.nav-menu-primary li.highlight > a',
			),
			'declarations' => array(
				'border-color' => $navigation
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.site-primary-navigation:not(.open) li.login > a:hover',
				'.site-primary-navigation:not(.open) li.highlight > a:hover',
			),
			'declarations' => array(
				'color' => $header_background,
				'background' => $navigation
			)
		) );
	}

	public function primary() {
		$primary = jobify_theme_mod( 'color-primary' );

		// Standard
		$this->css->add( array(
			'selectors' => array(
			),
			'declarations' => array(
				'color' => $primary
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.search_jobs',
				'.mfp-close-btn-in .mfp-close',
				'.cluster div',
				'.job-type',
				'.pricing-table-widget-title',
				
				// WooCommerce
				'.single-product .page-header .sale',
				'.single-product .price ins',
				'.woocommerce ul.product_list_widget ins'
			),
			'declarations' => array(
				'background-color' => $primary
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.cluster div:after'
			),
			'declarations' => array(
				'border-color' => $primary
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'ul.job_listings .job_listing:hover',
				'.job_position_featured',
				'li.type-resume:hover',
			),
			'declarations' => array(
				'box-shadow' => 'inset 5px 0 0 ' . $primary
			)
		) );

		// Buttons
		$this->css->add( array(
			'selectors' => array(
				'.button',
				'input[type=button]',
				'button',
				'.job-manager-pagination a',
				'.job-manager-pagination span',
				'.page-numbers',
				
				// Applications
				'input[name=wp_job_manager_send_application]',

				// Bookmarks
				'input[name=submit_bookmark]',

				// LinkedIn
				'input[name=apply-with-linkedin-submit]',

				// XING 
				'input[name=apply-with-xing-submit]'
			),
			'declarations' => array(
				'background-color' => $primary,
				'color' => '#fff'
			)
		) );
		
		// Button Hover
		$this->css->add( array(
			'selectors' => array(
				'.button:hover',
				'input[type=button]:hover',
				'button:hover',
				'.job-manager-pagination a:hover',
				'.job-manager-pagination span:hover',
				'.page-numbers:hover',

				// Applications
				'input[name=wp_job_manager_send_application]:hover',

				// Bookmarks
				'input[name=submit_bookmark]:hover',

				// LinkedIn
				'input[name=apply-with-linkedin-submit]:hover',

				// XING 
				'input[name=apply-with-xing-submit]:hover'
			),
			'declarations' => array(
				'background-color' => 'transparent',
				'color' => $primary,
				'border-color' => $primary
			)
		) );
		
		// Inverted Buttons
		$this->css->add( array(
			'selectors' => array(
				'.button:hover',
				'.button-secondary',
				'.load_more_jobs',
				'.load_more_resumes',

				// Bookmarks
				'.job-manager-form.wp-job-manager-bookmarks-form a.bookmark-notice'
			),
			'declarations' => array(
				'color' => $primary,
				'border-color' => $primary
			)
		) );
	
		// Inverted Button Hover
		$this->css->add( array(
			'selectors' => array(
				'.button',
				'.button-secondary:hover',
				'.load_more_jobs:hover',
				'.load_more_resumes:hover',

				// Bookmarks
				'.job-manager-form.wp-job-manager-bookmarks-form a.bookmark-notice:hover'
			),
			'declarations' => array(
				'background-color' => $primary,
				'color' => '#fff'
			)
		) );
	}

	public function accent() {
		$accent = jobify_theme_mod( 'color-accent' );
	
		// Special Button
		$this->css->add( array(
			'selectors' => array(
				'input[type=button].application_button',
				'.single-product #content .single_add_to_cart_button',
				'.checkout-button',
				'#place_order'
			),
			'declarations' => array(
				'background-color' => $accent,
				'border-color' => $accent
			)
		) );
		
		// Special Button Hover
		$this->css->add( array(
			'selectors' => array(
				'input[type=button].application_button:hover',
				'.single-product #content .single_add_to_cart_button:hover',
				'.checkout-button:hover',
				'#place_order:hover'
			),
			'declarations' => array(
				'background-color' => 'transparent',
				'color' => $accent,
				'border-color' => $accent
			)
		) );
	}

	public function cta() {
		$text = jobify_theme_mod( 'cta-text-color' );
		$background = jobify_theme_mod( 'cta-background-color' );

		$this->css->add( array(
			'selectors' => array(
				'.footer-cta',
				'.footer-cta a',
				'.footer-cta tel'
			),
			'declarations' => array(
				'color' => $text
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.footer-cta a.button:hover'
			),
			'declarations' => array(
				'color' => $background
			)
		) );

		$this->css->add( array(
			'selectors' => array(
				'.footer-cta',
			),
			'declarations' => array(
				'background-color' => $background,
			)
		) );
		
	}

}
