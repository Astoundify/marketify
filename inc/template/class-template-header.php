<?php

class Marketify_Template_Header {

    public function __construct() {
        $this->css = new Marketify_Customizer_CSS;

        add_action( 'after_setup_theme', array( $this, 'add_header_support' ) );
        add_action( 'marketify_output_customizer_css', array( $this, 'navigation_color' ), 10 );
    }

    public function add_header_support() {
        add_theme_support( 'custom-header', array(
            'default-text-color' => 'ffffff'
        ) );
    }

    public function navigation_color() {
        $this->css->add( array(
            'selectors' => array(
                '.main-navigation a'
            ),
            'declarations' => array(
                'color' => '#' . get_header_textcolor()
            )
        ) );
    }

}
