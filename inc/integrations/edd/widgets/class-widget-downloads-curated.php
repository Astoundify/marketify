<?php

class Marketify_Widget_Curated_Downloads extends Marketify_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'marketify_widget_curated_downloads';
        $this->widget_description = sprintf( __( 'Display curated %s in a grid.', 'marketify' ), edd_get_label_plural() );
        $this->widget_id          = 'marketify_widget_curated_downloads';
        $this->widget_name        = sprintf( __( 'Marketify - Home: Curated %s', 'marketify' ), edd_get_label_plural() );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => edd_get_label_plural(),
                'label' => __( 'Title:', 'marketify' )
            ),
            'ids' => array(
                'type' => 'text',
                'std'  => '',
                'label' => sprintf( __( '%s IDs: (comma separated)', 'marketify' ), edd_get_label_singular() )
            ),
            'columns' => array(
                'type' => 'select',
                'std' => 3,
                'label' => __( 'Columns:', 'marketify' ),
                'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 )
            ),
            'description' => array(
                'type'  => 'textarea',
                'std'   => '',
                'label' => __( 'Description:', 'marketify' )
            )
        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        global $post;

        extract( $args );

        $title        = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $description  = isset( $instance[ 'description' ] ) ? $instance[ 'description' ] : null;

        $ids          = isset ( $instance[ 'ids' ] ) ? $instance[ 'ids' ] : array();
        $ids          = implode( ',', array_map( 'trim', explode( ',', $instance[ 'ids' ] ) ) );

        $columns      = isset ( $instance[ 'columns' ] ) ? absint( $instance[ 'columns' ] ) : 3;

        echo $before_widget;

        if ( $title ) { 
            echo $before_title . $title . $after_title;
        }

        if ( $description ) {
            echo '<h2 class="home-widget-description">' . $description . '</h2>';
        }

        echo do_shortcode( "[downloads columns={$columns} ids={$ids}]" );

        echo $after_widget;
    }
}
