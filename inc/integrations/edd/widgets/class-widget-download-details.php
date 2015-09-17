<?php

class Marketify_Widget_Download_Details extends Marketify_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'marketify_widget_download_details';
        $this->widget_description = __( 'Display information related to the current download', 'marketify' );
        $this->widget_id          = 'marketify_widget_download_details';
        $this->widget_name        = sprintf( __( 'Marketify - %s Sidebar: About', 'marketify' ), edd_get_label_singular() );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => 'Product Details',
                'label' => __( 'Title:', 'marketify' )
            ),
            'purchase-count' => array(
                'type'  => 'checkbox',
                'std'   => '',
                'label' => __( 'Hide purchase count', 'marketify' )
            )
        );

        parent::__construct();

        add_action( 'marketify_product_details_after', array( $this, 'inline_extra_details' ) );
    }

    function widget( $args, $instance ) {
        global $post;

        if ( ! $post->post_author ) {
            return;
        }

        $args[ 'widget_id' ] = $args[ 'widget_id' ] . '-' . $post->post_author;

        $title = apply_filters( 'widget_title', isset( $instance['title'] ) ? $instance[ 'title' ] : '', $instance, $this->id_base );
        $count = isset( $instance[ 'purchase-count' ] ) && 1 == $instance[ 'purchase-count' ] ? false : true;

        $user = new WP_User( $post->post_author );
        $url = marketify()->get( 'edd' )->template->author_url( $user->ID );

        echo $args[ 'before_widget' ];

        if ( $title ) echo '<h1 class="section-title"><span>' . $title . '</span></h1>';

        do_action( 'marketify_product_details_widget_before', $instance );
    ?>
        <?php do_action( 'marketify_product_details_before', $instance ); ?>

        <div class="widget-detail widget-detail--full">
            <?php do_action( 'marketify_download_author_before' ); ?>

            <?php printf(  '<a class="author-link" href="%s" rel="author">%s</a>', $url, get_avatar( $user->ID, 130 ) ); ?>
            <?php printf( '<a class="author-link" href="%s" rel="author">%s</a>', $url, $user->display_name ); ?>

            <span class="author-joined"><?php 
                printf( 
                    __( 'Author since: %s', 'marketify' ), 
                    date_i18n( get_option( 'date_format' ), strtotime( $user->user_registered ) )
                );
            ?></span>
            <?php do_action( 'marketify_download_author_after' ); ?>
        </div>

        <div class="widget-detail widget-detail--half">
            <strong><?php echo edd_get_download_sales_stats( get_the_ID() ); ?></strong>
            <?php echo _n( 'Purchases', 'Purchases', edd_get_download_sales_stats( get_the_ID() ), 'marketify' ); ?>
        </div>

        <div class="widget-detail widget-detail--half widget-detail--last">
            <a href="#comments"><strong><?php echo get_comments_number(); ?></strong>
            <?php echo _n( 'Comment', 'Comments', get_comments_number(), 'marketify' ); ?></a>
        </div>

        <?php do_action( 'marketify_product_details_after', $instance ); ?>
    <?php

        do_action( 'marketify_product_details_widget_after', $instance );

        echo $args[ 'after_widget' ];
    }

    function inline_extra_details() {
        if ( 'top' == marketify_theme_mod( 'download-feature-area' ) ) {
            return;
        }
    ?>
        <div class="widget-detail">
            <?php do_action( 'marketify_download_info' ); ?>
        </div>

        <div class="widget-detail">
            <?php do_action( 'marketify_download_actions' ); ?>
        </div>
    <?php
    }

}
