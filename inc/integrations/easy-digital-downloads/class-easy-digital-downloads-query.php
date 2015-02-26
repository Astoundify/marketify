<?php

class Marketify_Easy_Digital_Downloads_Query {
	
	public function __construct() {
		add_filter( 'edd_downloads_query', array( $this, 'shortcode_query' ), 10, 2 );
		add_filter( 'pre_get_posts', array( $this, 'orderby' ) );
	}

	public function shortcode_query( $query, $atts ) {
		if ( is_tax( array( 'download_category', 'download_tag' ) ) ) {
			$tax = get_queried_object();

			$query[ 'tax_query' ] = array(
				array(
					'taxonomy' => $tax->taxonomy,
					'field'    => 'id',
					'terms'    => $tax->term_id
				)
			);
		}

		if ( is_page_template( 'page-templates/popular.php' ) ) {
			$query[ 'meta_key' ] = '_edd_download_sales';
			$query[ 'orderby' ]  = 'meta_value_num';

			if ( get_query_var( 'popular_cat' ) ) {
				$query[ 'tax_query' ] = array(
					array(
						'taxonomy' => 'download_category',
						'field'    => 'id',
						'terms'    => explode( ',', get_query_var( 'popular_cat' ) )
					)
				);
			}
		} else {
			foreach ( array( 'm-orderby', 'm-order' ) as $key ) {
				if ( get_query_var( $key ) ) {
					$query[ str_replace( 'm-', '', $key ) ] = get_query_var( $key );
				}
			}

			if ( 'sales' == get_query_var( 'm-orderby' ) ) {
				$query[ 'orderby' ]  = 'meta_value_num';
				$query[ 'meta_key' ] = '_edd_download_sales';
			} elseif( 'pricing' == get_query_var( 'm-orderby' ) ) {
				$query[ 'orderby' ]  = 'meta_value_num';
				$query[ 'meta_key' ] = 'edd_price';
			}
		};

		if ( isset( $_GET[ 's' ] ) && 'download' == isset( $_GET[ 'post_type' ] ) ) {
			$query[ 's' ] = esc_attr( $_GET[ 's' ] );
		}

		return $query;
	}

	public function orderby( $query ) {
		if (
			! $query->is_main_query() || 
			is_admin() || 
			( defined( 'DOING_AJAX' ) && DOING_AJAX ) || 
			is_page_template( 'page-templates/shop.php' ) 
		) {
			return;
		}

		if ( get_query_var( 'm-orderby' ) && 'pricing' == get_query_var( 'm-orderby' ) ) {
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', 'edd_price' );
		} elseif ( get_query_var( 'm-orderby' ) && 'sales' == get_query_var( 'm-orderby' ) ) {
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'meta_key', '_edd_download_sales' );
		}
	}

}
