<?php

class Marketify_Template_Header {

    public function __construct() {
        $this->css = new Marketify_Customizer_CSS;

        add_action( 'after_setup_theme', array( $this, 'add_header_support' ) );
        add_action( 'marketify_output_customizer_css', array( $this, 'navigation_color' ), 10 );
    }

    public function add_header_support() {
        add_theme_support( 'custom-header', array(
            'width' => 150,
            'height' => 55,
            'flex-height' => true,
            'flex-width' => true,
            'wp-head-callback' => array( $this, 'frontend_style' )
        ) );
    }

    public function frontend_style() {
	$header_text_color = get_header_textcolor();
?>
    <style type="text/css">
    <?php
        // Has the text been hidden?
        if ( 'blank' == $header_text_color ) :
    ?>
        .site-branding .site-title,
        .site-branding .site-description,
        .site-title--minimal {
            display: none;
        }
    <?php
        endif;
    ?>
    .site-title a,
    .site-description {
        color: #<?php echo $header_text_color; ?>;
    }
    </style>
<?php
    }

    public function navigation_color() {
        $this->css->add( array(
            'selectors' => array(
                '.main-navigation a'
            ),
            'declarations' => array(
                'color' => '#' . get_header_textcolor()
            )
        ) );
    }

    public function search_form() {
        ob_start();

        locate_template( array( 'searchform-header.php' ), true, false );

        return ob_get_clean();
    }

}
