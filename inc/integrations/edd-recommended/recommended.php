<?php
/**
 * Easy Digital Downloads - Recommended Products
 *
 * @package Marketify
 */

/**
 * Add our own output of recommended products, as the plugin
 * uses the standard grid by default, and we need our own.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_recommended_products() {
	global $edd_options;

	if ( ! function_exists( 'edd_rp_get_suggestions' ) )
		return;

	if ( is_singular( 'download' ) ) {
		global $post;

		$suggestion_data = edd_rp_get_suggestions( $post->ID );
	} else {
		$cart_items = edd_get_cart_contents();
		
		$post_ids        = wp_list_pluck( $cart_items, 'id' );
		$user_id         = is_user_logged_in() ? get_current_user_id() : false;
		$suggestion_data = edd_rp_get_multi_suggestions( $post_ids, $user_id );
	}

	if ( ! is_array( $suggestion_data ) || empty( $suggestion_data ) )
		return;

	$suggestions = array_keys( $suggestion_data );

	$suggested_downloads = new WP_Query( array( 'post__in' => $suggestions, 'post_type' => 'download' ) );
?>
	
	<h1 class="section-title"><span><?php _e( 'Recommended Products', 'marketify' ); ?></span></h1>

	<div class="row edd-recommended-products">
		<?php while ( $suggested_downloads->have_posts() ) : $suggested_downloads->the_post(); ?>
		<div class="col-lg-3 col-md-4 col-sm-6">
			<?php get_template_part( 'content-grid', 'download' ); ?>
		</div>
		<?php endwhile; ?>
	</div>
<?php
}
add_action( 'marketify_single_download_after', 'marketify_recommended_products' );

/**
 * Remove the automatic output of Recommended Products
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_recommended_products_shim() {
	remove_filter( 'edd_after_download_content', 'edd_rp_display_single', 10, 1 );
	remove_filter( 'edd_after_checkout_cart', 'edd_rp_display_checkout' );
}
add_action( 'init', 'marketify_recommended_products_shim' );