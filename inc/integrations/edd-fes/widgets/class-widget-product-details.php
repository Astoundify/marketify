<?php

class Marketify_Widget_FES_Product_Details extends Marketify_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'widget--download-single-meta marketify_widget_fes_product_details';
        $this->widget_description = __( 'Output specificed submission form fields.', 'marketify' );
        $this->widget_id          = 'marketify_widget_fes_product_details';
        $this->widget_name        = sprintf( __( 'Marketify - %1$s: %1$s Meta', 'marketify' ), edd_get_label_singular() );
        $this->settings           = array(
            'title' => array(
                'type'  => 'text',
                'std'   => '',
                'label' => __( 'Title:', 'marketify' )
            ),
            'fields' => array(
                'type'  => 'multicheck',
                'label' => __( 'Fields to Output:', 'marketify' ),
                'std' => '',
                'options' => $this->get_fields_list()
            ),
        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        $title  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        $chosen = isset( $instance[ 'fields' ] ) ? $instance[ 'fields' ] : array();
        $chosen = maybe_unserialize( $chosen );

        $output = $this->get_product_details_output( $chosen );

        if ( empty( $output ) ) {
            return;
        }

        echo $args[ 'before_widget' ];

        if ( $title ) {
            echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
        }
    ?>

        <table class="edd-fpd">
            <?php foreach ( $output as $label => $value ) : if ( '' == $value ) continue; ?>
            <tr>
                <th><?php echo $label; ?></th>
                <td><?php echo $value; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php
        echo $args[ 'after_widget' ];
    }

    private function get_fields_list() {
        $fields  = $this->get_form_fields();

        if ( ! is_array( $fields ) ) {
            return array();
        }

        $labels = wp_list_pluck( $fields, 'label' );
        $names  = wp_list_pluck( $fields, 'name' );

        $options = array_combine( $names, $labels );
        $options = array_filter( $options );

        return $options;
    }

    public function get_form_fields() {
        $form_id = EDD_FES()->helper->get_option( 'fes-submission-form' );

        if ( ! $form_id ) {
            return array();
        }

        $fields  = get_post_meta( $form_id, 'fes-form', true );

        return $fields;
    }

    public function get_product_details_output( $chosen ) {
        global $post;

        $fields  = $this->get_form_fields();
        $meta    = array();

        if ( ! $fields ) {
            return;
        }

        foreach ( $fields as $field ) {
            if ( ! in_array( $field[ 'name' ], $chosen ) ) {
                continue;
            }

            $value = get_post_meta( $post->ID, $field[ 'name' ], true );

            switch ( $field[ 'input_type' ] ) {
                case 'image_upload' :
                    if ( 'featured_image' == $field[ 'template' ] ) {
                        $value = get_the_post_thumbnail( $post->ID, 'thumbnail' );
                    }
                break;

                case 'file_upload' :
                    $uploads = array();

                    $value = '';

                    if ( is_array( $value ) ) {
                        foreach ( $value as $attachment_id ) {
                            $uploads[] = wp_get_attachment_link( $attachment_id, 'thumbnail', false, true );
                        }

                        $value = implode( '<br />', $uploads );
                    }
                break;

                case 'checkbox' :
                case 'multiselect' :
                    if ( ! is_array( $value ) ) {
                        $value = explode( '|', $value );
                    } else {
                        $value = array_map( 'trim', $value );
                    }

                    $value = implode( $this->multi_sep, $value );
                break;

                case 'taxonomy' :
                    $terms = wp_get_post_terms( $post->ID, $field[ 'name' ] );

                    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                        switch ( $field[ 'type' ] ) {

                            case 'checkbox' :
                            case 'multiselect' :
                            case 'text' :
                                $_terms = array();

                                foreach ( $terms as $term ) {
                                    $_terms[] = sprintf( '<a href="%s">%s</a>', get_term_link( $term, $field[ 'name' ] ), $term->name );
                                }

                                $value = implode( $this->multi_sep, $_terms );
                            break;

                            case 'select' :
                                $value = sprintf( '<a href="%s">%s</a>', get_term_link( current( $terms ), $field[ 'name' ] ), current( $terms )->name );
                            break;

                        }
                    }
                break;

                default :
                    if ( 'no' != $field[ 'is_meta' ] ) {
                        $value = get_post_meta( $post->ID, $field[ 'name' ], true );
                    } else {
                        $value = get_post_field( $field[ 'name' ], $post->ID );
                    }
                break;
            }

            $value = make_clickable( $value );

            $label = apply_filters( 'edd_fpd_label', isset( $field[ 'label' ] ) ? $field[ 'label' ] : '', $field );
            $value = apply_filters( 'edd_fpd_value', $value, $field );

            if ( empty( $value ) )
                continue;

            $meta[ $label ] = $value;

        }

        return $meta;
    }

}
