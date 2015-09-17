<?php

class Marketify_EDD_FES_Vendors {

    public function __construct() {
        add_action( 'template_redirect', array( $this, 'vendor_archive' ) );

        add_filter( 'fes_vendor_dashboard_menu', array( $this, 'dashboard_menu_icons' ) );
        add_filter( 'marketify_header_outer_image', array( $this, 'profile_cover_image' ), 1 );

        add_action( 'marketify_download_entry_meta', array( $this, 'byline' ) );

        add_action( 'save_post', array( $this, 'clear_download_count_cache' ), 10, 2 );
    }

    public function vendor_archive() {
        if ( ! is_page_template( 'page-templates/vendor.php' ) ) {
            return;
        }

        if ( ! get_query_var( 'vendor' ) ) {
            $fes = marketify()->get( 'edd-fes' );
            $vendor = $fes->vendor();

            wp_redirect( $vendor->url( get_current_user_id() ) );
            exit();
        }
    }

    public function clear_download_count_cache( $post_id, $post ) {
        if ( 'download' != $post->post_type ) {
            return;
        }

        delete_transient( $post->post_author . 'download' );
    }

    public function byline() {	
        global $post;

        $vendor = marketify()->get( 'edd-fes' )->vendor( $post->post_author );

        printf(
            __( '<span class="byline"> by %1$s</span>', 'marketify' ),
            sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
                $vendor->url(),
                esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), get_the_author() ) ),
                esc_html( get_the_author_meta( 'display_name', $post->post_author ) ),
                get_avatar( get_the_author_meta( 'ID', $post->post_author ), 50, apply_filters( 'marketify_default_avatar', null ) )
            )
        );
    }

    public function dashboard_menu_icons( $menu ) {
        if ( EDD_FES()->integrations->is_commissions_active() ) {
            $menu[ 'earnings' ][ 'icon' ] = 'graph';
        }

        $menu[ 'home' ][ 'icon' ] = 'house';
        $menu[ 'orders' ][ 'icon' ] = 'ticket';
        $menu[ 'logout' ][ 'icon' ] = 'logout';

        return $menu;
    }

    public function marketify_header_outer_image_fes( $background ) {
        global $wp_query;

        if ( ! is_page_template( 'page-templates/vendor.php' ) ) {
            return $background;
        }

        $vendor = isset( $wp_query->query_vars[ 'vendor' ] ) ? $wp_query->query_vars[ 'vendor' ] : null;

        if ( ! $vendor ) {
            return $background;
        }

        $vendor = new WP_User( $vendor );

        $image = get_user_meta( $vendor->ID, 'cover_image', true );
        $image = wp_get_attachment_image_src( $image, 'fullsize' );

        if ( is_array( $image ) ) {
            add_filter( 'marketify_needs_a_background', '__true' );

            return $image;
        }

        return $background;
    }

}