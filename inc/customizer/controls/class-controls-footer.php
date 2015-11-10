<?php

class Marketify_Customizer_Controls_Footer extends Marketify_Customizer_Controls {

    public $controls = array();

    public function __construct() {
        $this->section = 'footer';
        $this->priority = new Marketify_Customizer_Priority(49, 1);

        parent::__construct();

        add_action( 'customize_register', array( $this, 'add_controls' ), 30 );
        add_action( 'customize_register', array( $this, 'set_controls' ), 35 );
    }

    public function add_controls( $wp_customize ) {
        $this->controls = array(
            'footer-style' => array(
                'label' => __( 'Display Style', 'marketify' ),
                'type'    => 'select',
                'choices' => array(
                    'light' => __( 'Light', 'marketify' ),
                    'dark'  => __( 'Dark', 'marketify' )
                ),
                'sanitize_callback' => 'esc_attr'
            ),
        );

        return $this->controls;
    }

}

new Marketify_Customizer_Controls_Footer();
