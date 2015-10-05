<?php

class Marketify_EDD_Wish_Lists extends Marketify_Integration {

    public function __construct() {
        parent::__construct( dirname( __FILE__ ) );
    }

    public function setup_actions() {
        add_action( 'init', array( $this, 'relocate' ) );
        add_filter( 'facetwp_is_main_query', array( $this, 'facetwp_is_main_query' ), 10, 2 );
    }

    public function relocate() {
        remove_action( 'edd_purchase_link_top', 'edd_wl_load_wish_list_link' );
        add_action( 'marketify_single_download_content_after', 'edd_wl_load_wish_list_link' );
    }

    public function facetwp_is_main_query( $is_main_query, $query ) {
        if ( isset( $query->query_vars['post_type'] ) ) {
            if ( 'edd_wish_list' == $query->query_vars['post_type'] ) {
                $is_main_query = false;
            }
        }

        return $is_main_query;
    }

}
