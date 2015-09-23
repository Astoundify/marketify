<?php

class Marketify_Widget_FES_Vendor extends Marketify_EDD_FES_Vendor_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'marketify_widget_fes_vendor';
        $this->widget_description = __( 'Display the vendor avatar and extra information.', 'marketify' );
        $this->widget_id          = 'marketify_widget_fes_vendor';
        $this->widget_name        = __( 'Marketify - Vendor: Name + Avatar', 'marketify' );
        $this->settings           = array(
            'extras' => array(
                'type'  => 'textarea',
                'std'   => '',
                'label' => __( 'Additional Information:', 'marketify' ),
                'description' => __( 'Use the [marketify-vendor key=""] shortcode to provide additional information about the vendor.', 'marketify' )
            ),
        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        $url = $this->vendor->url();
        $display_name = $this->vendor->display_name();
        $registered = $this->vendor->date_registered();
    ?>
        <div class="download-author widget-detail widget-detail--full widget-detail--author">
            <?php printf(  '<a class="author-avatar" href="%s" rel="author">%s</a>', $url, get_avatar( $this->vendor->obj->D, 130 ) ); ?>
            <?php printf( '<a class="author-link" href="%s" rel="author">%s</a>', $url, $display_name ); ?>

            <span class="widget-detail__info"><?php 
                printf( 
                    __( 'Author since: %s', 'marketify' ), 
                    date_i18n( get_option( 'date_format' ), $registered )
                );
            ?></span>

            <?php echo wpautop( do_shortcode( $instance[ 'extras' ] ) ); ?>
        </div>
        <div class="widget-detail widget-detail--full">
            <strong class="widget-detail__title"><?php echo $this->vendor->downloads_count(); ?></strong>
            <span class="widget-detail__info"><?php echo _n( edd_get_label_singular(), edd_get_label_plural(), $this->vendor->downloads_count(), 'marketify' ); ?></span>
        </div>
    <?php
        echo $args[ 'after_widget' ];
    }

}
