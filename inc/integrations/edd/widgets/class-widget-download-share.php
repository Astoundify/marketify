<?php

class Marketify_Widget_Download_Share extends Marketify_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'marketify-widget--download-single-share';
        $this->widget_description = __( 'Display sharing options for this product.', 'marketify' );
        $this->widget_id          = 'marketify_widget_download_share';
        $this->widget_name        = sprintf( __( 'Marketify - %s Sidebar: Sharing', 'marketify' ), edd_get_label_singular() );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => 'Sharing Options',
                'label' => __( 'Title:', 'marketify' )
            ),
            'description' => array(
                'type'  => 'text',
                'std'   => 'Like this item? Why not share it with your friends?',
                'label' => __( 'Description:', 'marketify' )
            )
        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        global $post;

        extract( $args );

        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $description = isset ( $instance[ 'description' ] ) ? esc_attr( $instance[ 'description' ] ) : null;

        echo $before_widget;

        if ( $title ) echo $before_title . $title . $after_title;

        if ( $description ) {
            echo '<span class="widget-description">' . $description . '</span>';
        }

        do_action( 'marketify_widget_download_share_before' );
        do_action( 'marketify_widget_download_share' );
        do_action( 'marketify_widget_download_share_after' );

        echo $after_widget;
    }

}
