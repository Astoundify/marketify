<?php

class Marketify_EDD_Product_Reviews extends Marketify_Integration {

    public function __construct() {
        $this->includes = array(
            'widgets/class-widget-download-review-details.php',
            'class-edd-product-reviews-widgets.php',
            'class-edd-product-reviews-walker.php'
        );

        parent::__construct( dirname( __FILE__ ) );
    }

    public function setup_actions() {
		remove_action( 'edd_download_after_title', array( edd_reviews(), 'display_average_rating' ) );
		add_filter( 'edd_reviews_render_reviews_args', array( $this, 'edd_reviews_render_reviews_args' ) );
    }

    public function init() {
        $this->widgets = new Marketify_EDD_Product_Reviews_Widgets();
    }

	/**
	 * Modify the review output
	 *
	 * @since 2.8.0
	 * @param array $args
	 * @return array $args
	 */
	public function edd_reviews_render_reviews_args( $args ) {
		$args[ 'walker' ] = new Walker_Marketify_EDD_Review();

		return $args;
	}

}
