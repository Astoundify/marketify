<?php

class Marketify_EDD_Popular {

    public function __construct() {
        $this->slug = _x( 'popular', 'URL slug to determine category sorting', 'marketify' );

        add_action( 'marketify_downloads_before', array( $this, 'archive_section_title' ) );
        add_filter( 'marketify_get_the_archive_title', array( $this, 'get_the_archive_title' ) );

        add_action( 'init', array( $this, 'endpoint' ) );
        add_filter( 'edd_download_category_args', array( $this, 'category_args' ) );
        add_action( 'template_redirect', array( $this, 'filter_query' ) );
    }

    public function show_popular() {
        if ( 'on' != marketify_theme_mod( 'downloads-archives-popular' ) ) {
            return false;
        }

        if ( $this->is_popular_query() ) {
            return false;
        }

        return true;
    }

    public function is_popular_query() {
        if ( ! is_tax( array( 'download_category', 'download_tag' ) ) ) {
            return false;
        }

        global $wp_query;

        if ( ! isset( $wp_query->query_vars[ $this->slug ] ) ) {
            return false;
        }

        return true;
    }

    public function filter_query() {
        if ( ! $this->is_popular_query() ) {
            return;
        }

        add_filter( 'edd_downloads_query', array( $this, 'edd_downloads_query' ), 10, 2 );
    }

    public function edd_downloads_query( $query, $atts ) {
        $query[ 'meta_key' ] = '_edd_download_sales';
        $query[ 'orderby' ]  = 'meta_value_num';

        return $query;
    }

    public function category_args( $args ) {
        $args[ 'rewrite' ][ 'ep_mask' ] = EP_CATEGORIES;

        return $args;
    }

    public function endpoint() {
        add_rewrite_endpoint( $this->slug, EP_CATEGORIES );
    }

    public function get_the_archive_title( $title ) { 
        if ( ! did_action( 'marketify_entry_before' ) || did_action( 'marketify_downloads_before' ) ) {
            return $title;
        }

        if ( is_post_type_archive( 'download' ) ) {
            $title = __( 'Popular', 'marketify' );
        } else if ( is_tax( array( 'download_tag', 'download_category' ) ) ) {
            $title = sprintf( __( 'Popular in %s', 'marketify' ), single_term_title( '', false ) );
        }

        return $title;
    }

    public function archive_section_title() {
        if ( ! $this->show_popular() ) {
            return;
        }
    ?>
        <div class="section-title"><span>
            <?php the_archive_title(); ?>
        </span></div>
    <?php
    }

}
