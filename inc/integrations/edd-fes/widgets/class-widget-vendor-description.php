<?php

class Marketify_Widget_FES_Vendor_Description extends Marketify_EDD_FES_Vendor_Widget {

    public function __construct() {
        $this->widget_cssclass    = 'marketify_widget_fes_vendor_description';
        $this->widget_description = __( 'Display the vendor description.', 'marketify' );
        $this->widget_id          = 'marketify_widget_fes_vendor_description';
        $this->widget_name        = __( 'Marketify - Vendor: Description', 'marketify' );
        $this->settings           = array(
            'desc' => array(
                'type'  => 'description',
                'std'   => __( 'This widget has no options', 'marketify' )
            ),
        );
        parent::__construct();
    }

    function widget( $args, $instance ) {
        $description = $this->vendor->obj->description;

        if ( '' == $description ) {
            return;
        }

        echo $args[ 'before_widget' ];
    ?>
        <div class="download-author-bio">
            <?php echo esc_html( $description ); ?>
        </div>
    <?php
        echo $args[ 'after_widget' ];
    }

}
