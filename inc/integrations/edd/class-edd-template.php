<?php
	
class Marketify_EDD_Template {

	public function __construct() {
		$this->navigation = new Marketify_EDD_Template_Navigation();
		$this->purchase_form = new Marketify_EDD_Template_Purchase_Form();
		$this->download = new Marketify_EDD_Template_Download();

        add_filter( 'edd_download_pagination_args', array( $this, 'pagination_args' ), 10, 4 );
    }

    public function pagination_args( $args, $atts, $downloads, $query ) {
        // on non salvattore/sliders dont output pagination
        if ( isset( $atts[ 'salvattore' ] ) && 'no' == $atts[ 'salvattore' ] ) {
            $args[ 'total' ] = 0;
        }

        $args[ 'prev_text' ] = __( 'Previous', 'marketify' );
        $args[ 'next_text' ] = __( 'Next', 'marketify' );

        return $args;
    }

	public function author_url( $user_id ) {
		$fes = marketify()->get( 'edd-fes' );

		if ( $fes ) {
			$vendor = $fes->vendor( $user_id );
			$url = $vendor->url();
		} else {
			$url = get_author_posts_url( $user_id );
		}

		return apply_filters( 'marketify_author_url', esc_url( $url ) );
	}


}
