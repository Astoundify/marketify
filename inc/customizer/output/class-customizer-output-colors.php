<?php

class Marketify_Customizer_Output_Colors {

    public function __construct() {
        $this->css = new Marketify_Customizer_CSS;

        add_action( 'marketify_output_customizer_css', array( $this, 'page_header' ), 10 );
        add_action( 'marketify_output_customizer_css', array( $this, 'navigation' ), 20 );
        add_action( 'marketify_output_customizer_css', array( $this, 'primary' ), 30 );
        add_action( 'marketify_output_customizer_css', array( $this, 'accent' ), 30 );
        add_action( 'marketify_output_customizer_css', array( $this, 'footer' ), 40 );
        add_action( 'marketify_output_customizer_css', array( $this, 'overlay' ), 40 );
        add_action( 'marketify_output_customizer_css', array( $this, 'minimal' ), 50 );
    }

    public function page_header() {
        $page_header_background = marketify_theme_mod( 'color-page-header-background' );

        $this->css->add( array(
            'selectors' => array(
                '.header-outer',
                '.site-footer',
                '.minimal',
                '.custom-background.minimal',
            ),
            'declarations' => array(
                'background-color' => $page_header_background
            )
        ) );
    }

    public function navigation() {
        $primary = marketify_theme_mod( 'color-primary' );

        $this->css->add( array(
            'selectors' => array(
                '.nav-menu--primary li li a'
            ),
            'declarations' => array(
                'color' => $primary
            )
        ) );
    }

    public function primary() {
        $primary = marketify_theme_mod( 'color-primary' );

        $this->css->add( array(
            'selectors' => array(
                '.featured-popular-switcher span:hover'
            ),
            'declarations' => array(
                'border-color' => $primary,
                'color' => $primary
            )
        ) );
        
        // Buttons
        $this->css->add( array(
            'selectors' => array(
                'button',
                'input[type=reset]',
                'input[type=submit]',
                'input[type=radio]:checked',
                '.button',

                // edd
                '#edd-purchase-button',
                '.edd-submit',
                '.edd-submit.button',
                '.edd-submit.button:visited',
                'input[type=submit].edd-submit',

                // edd wish lists
                '.edd-wl-button',
                'a.edd-wl-button',
                '.edd-wl-button.edd-wl-action',
                'a.edd-wl-button.edd-wl-action'
            ),
            'declarations' => array(
                'color' => $primary,
                'border-color' => $primary,
                'background' => '#ffffff'
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                'button:hover',
                'input[type=reset]:hover',
                'input[type=submit]:hover',
                '.button:hover',

                // edd
                '#edd-purchase-button:hover',
                '.edd-submit:hover',
                '.edd-submit.button:hover',
                'input[type=submit].edd-submit:hover',

                // edd wish lists
                '.edd-wl-button:hover',
                'a.edd-wl-button:hover',
                '.edd-wl-button.edd-wl-action:hover',
                'a.edd-wl-button.edd-wl-action:hover'
            ),
            'declarations' => array(
                'color' => '#ffffff',
                'background-color' => $primary,
                'border-color' => $primary
            )
        ) );

        // white buttons use text color
        $this->css->add( array(
            'selectors' => array(
                '.button.button--color-white:hover',

                // edd
                '.edd-submit.button.edd-add-to-cart.button--color-white:hover',
                '.edd-submit.button.edd_go_to_checkout.button--color-white:hover'
            ),
            'declarations' => array(
                'color' => $primary,
            )
        ) );

    }

    public function accent() {
        $accent = marketify_theme_mod( 'color-accent' );

        // Buttons
        $this->css->add( array(
            'selectors' => array(
            ),
            'declarations' => array(
                'color' => $accent
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
            ),
            'declarations' => array(
                'border-color' => $accent
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
            ),
            'declarations' => array(
                'border-color' => $accent
            )
        ) );
    }

    public function footer() {
    }

    public function overlay() {
        $primary = marketify_theme_mod( 'color-primary' );

        $this->css->add( array(
            'selectors' => array(
                '.content-grid-download__entry-image:hover .content-grid-download__overlay',
                '.content-grid-download__entry-image.hover .content-grid-download__overlay'
            ),
            'declarations' => array(
                'background' => 'rgba(' . $this->css->hex2rgb( $primary ) . ',.80)',
                'border' => '1px solid rgba(' . $this->css->hex2rgb( $primary ) . ',.80)',
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.search-form-overlay',
                '.download-gallery-navigation__image.slick-active:before'
            ),
            'declarations' => array(
                'background-color' => 'rgba(' . $this->css->hex2rgb( $primary ) . ', .90)',
            )
        ) );
    }

    public function minimal() {
        $page_header_background = marketify_theme_mod( 'color-page-header-background' );
        $primary = marketify_theme_mod( 'color-primary' );
        $accent = marketify_theme_mod( 'color-accent' );

        $this->css->add( array(
            'selectors' => array(
                '.minimal',
                '.custom-background.minimal',
            ),
            'declarations' => array(
                'background-color' => $page_header_background
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                '.minimal .section-title__inner',
                '.minimal .edd_form fieldset > span legend',
                '.minimal #edd_checkout_form_wrap .edd_form fieldset > span legend',
                '.minimal .entry-content .edd-slg-social-container > span legend'
            ),
            'declarations' => array(
                'background-color' => $page_header_background,
                'color' => '#fff'
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                // edd
                '.minimal #edd_login_form input[type=submit]',
                '.minimal #edd_register_form input[type=submit]',
                '.minimal #edd-purchase-button.edd-submit.button'
            ),
            'declarations' => array(
                'background-color' => $accent,
                'border-color' => $accent,
                'color' => '#fff'
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                // edd
                '.minimal #edd_login_form input[type=submit]:hover',
                '.minimal #edd_register_form input[type=submit]:hover',
                '.minimal #edd-purchase-button.button.edd-submit:hover',
            ),
            'declarations' => array(
                'background-color' => $page_header_background,
                'border-color' => $accent,
                'color' => $accent
            )
        ) );
    }

}

new Marketify_Customizer_Output_Colors();
