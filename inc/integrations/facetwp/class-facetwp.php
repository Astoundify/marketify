<?php

class Marketify_FacetWP extends Marketify_Integration {

    public function __construct() {
        parent::__construct( dirname( __FILE__) );
    }

    public function setup_actions() {
        add_filter( 'downloads_shortcode', array( $this, 'facetwp_template' ), 20, 2 );
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    public function widgets_init() {
        unregister_widget( 'Marketify_Widget_Download_Archive_Sorting' );
    }

    public function facetwp_template( $output, $atts ) {
        if ( ! isset( $atts[ 'salvattore' ] ) || 'no' != $atts[ 'salvattore' ] ) {
            $output = str_replace( 'class="edd_downloads_list', 'class="edd_downloads_list facetwp-template', $output );
            $output .= do_shortcode( '[facetwp pager="true"]' );
        }

        return $output;
    }

}
