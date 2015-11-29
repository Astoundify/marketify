<?php

class Marketify_EDD_FES extends Marketify_Integration {

    public function __construct() {
        $this->includes = array(
            'class-edd-fes-vendors.php',
            'class-edd-fes-vendor.php',
            'class-edd-fes-widgets.php',

            'widgets/class-widget-vendor.php',
            'widgets/class-widget-vendor-description.php',
            'widgets/class-widget-vendor-contact.php',
            'widgets/class-widget-product-details.php'
        );

        parent::__construct( dirname( __FILE__ ) );
    }

    public function init() {
        $this->vendors = new Marketify_EDD_FES_Vendors();
        $this->widgets = new Marketify_EDD_FES_Widgets();
    }

    public function setup_actions() {
        add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' )  );
        add_action( 'wp_head', array( $this, 'recaptcha_style' ) );
    }

    public function vendor( $author = false ) {
        return new Marketify_EDD_FES_Vendor( $author );
    }

    function recaptcha_style() {
        if ( ! EDD_FES()->helper->get_option( 'fes-recaptcha-public-key' ) ) {
            return;
        }
    ?>
        <script type="text/javascript">
            var RecaptchaOptions = {
                theme : 'clean',
            };
        </script>
    <?php
    }

    function dequeue_styles() {
        wp_dequeue_style( 'fes-css' );
    }

}
