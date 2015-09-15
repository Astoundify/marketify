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
        
        // Buttons
        $this->css->add( array(
            'selectors' => array(
                'button',
                'input[type=reset]',
                'input[type=submit]',
                '.button'
            ),
            'declarations' => array(
                'color' => $primary,
                'border-color' => $primary
            )
        ) );

        $this->css->add( array(
            'selectors' => array(
                'button:hover',
                'input[type=reset]:hover',
                'input[type=submit]:hover',
                '.button:hover'
            ),
            'declarations' => array(
                'color' => '#ffffff',
                'background-color' => $primary
            )
        ) );

        // white buttons use text color
        $this->css->add( array(
            'selectors' => array(
                '.button--color-white:hover'
            ),
            'declarations' => array(
                'color' => $primary
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

}

new Marketify_Customizer_Output_Colors();
