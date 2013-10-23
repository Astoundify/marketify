<?php
/**
 * Easy Digital Download Tweaks/Functions
 *
 * @package Marketify
 */

add_filter( 'edd_csau_show_excerpt', '__return_false' );
add_filter( 'edd_csau_show_price', '__return_false' );

function marketify_edd_download_class( $class, $id, $atts ) {
	return $class . ' content-grid-download';
}
add_filter( 'edd_download_class', 'marketify_edd_download_class', 10, 3 );

function marketify_shortcode_atts_downloads( $atts ) {
	$atts[ 'excerpt' ] = 'no';
	$atts[ 'full_content' ] = 'no';
	$atts[ 'price' ] = 'no';
	$atts[ 'buy_button' ] = 'no';

	return $atts;
}
add_filter( 'shortcode_atts_downloads', 'marketify_shortcode_atts_downloads' );

/**
 * Add standard comments to the Downloads post type.
 *
 * @since Marketify 1.0
 *
 * @param array $supports
 * @return array $supports
 */
function marketify_edd_product_supports( $supports ) {
	$supports[] = 'comments';

	return $supports;	
}
add_filter( 'edd_download_supports', 'marketify_edd_product_supports' );

/**
 * Check if we are a standard Easy Digital Download install,
 * or a multi-vendor marketplace.
 *
 * @since Marketify 1.0
 *
 * @return boolean
 */
function marketify_is_multi_vendor() {
	if ( ! class_exists( 'Easy_Digital_Downloads' ) )
		return false;

	if ( false === ( $is_multi_vendor = get_transient( 'marketify_is_multi_vendor' ) ) ) {
		$vendors = get_users( array(
			'role' => 'shop_vendor'
		) );

		print_r( $vendors );

		$total = count( $vendors );
		$is_multi_vendor = $total > 0 ? true : false;

		set_transient( 'marketify_is_multi_vendor', $is_multi_vendor );
	}

	return $is_multi_vendor;
}

/**
 * When a user is updated, or created, clear the multi vendor
 * cache check.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function __marketify_clear_multi_vendor_cache() {
	delete_transient( 'marketify_is_multi_vendor' );
}
add_action( 'profile_update', '__marketify_clear_multi_vendor_cache' );
add_action( 'user_register',  '__marketify_clear_multi_vendor_cache' );

/**
 * Add the Price to the download information at the top of the page.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_price() {
	global $post;

	edd_price( $post->ID );
}
add_action( 'marketify_download_info', 'marketify_download_price', 5 );

/**
 * Add the Star Rating to the download information at the top of the page,
 * as well in the download grid.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_entry_meta_rating() {
	if ( ! class_exists( 'EDD_Reviews' ) )
		return;

	global $post;
	
	$rating = edd_reviews()->average_rating( false );

	if ( 0 == $rating )
		return;
?>
	<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating">
		<?php for ( $i = 1; $i <= $rating; $i++ ) : ?>
		<i class="icon-star"></i>
		<?php endfor; ?>

		<?php for( $i = 0; $i < ( 5 - $rating ); $i++ ) : ?>
		<i class="icon-star-empty"></i>
		<?php endfor; ?>
		
		<div style="display:none" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
			<meta itemprop="worstRating" content="1" />
			<span itemprop="ratingValue"><?php echo $rating; ?></span>
			<span itemprop="bestRating">5</span>
		</div>
	</div>
<?php
}
add_action( 'marketify_download_entry_meta', 'marketify_download_entry_meta_rating' );
add_action( 'marketify_download_info', 'marketify_download_entry_meta_rating' );

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

	<div class="row">
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
	remove_filter( 'edd_after_download_content', 'edd_rp_display_single' );
	remove_filter( 'edd_after_checkout_cart', 'edd_rp_display_checkout' );
}
add_action( 'init', 'marketify_recommended_products_shim' );

/**
 * Download Authors
 *
 * Since WordPres only supports author archives of a singular (or all) post
 * types, we need to create a way to just view an author's downloads.
 *
 * This sets up a new query variable called `author_ptype` which can be used
 * to fetch a specific post type when viewing author archives.
 *
 * @since Marketify 1.0
 */
class Marketify_Author {
	/*
	 * Init so we can attach to an action
	 */
	public static function init() {
		new self;
	}

	/*
	 * Hooks and Filters
	 */
	public function __construct() {
		add_filter( 'query_vars', array( $this, 'query_vars' ) ); 
		add_filter( 'generate_rewrite_rules', array( $this, 'rewrites' ) );
		add_action( 'pre_get_posts', array( $this, 'filter_endpoints' ) );
	}

	/**
	 * Register the `author_ptype` query variable.
	 *
	 * @since Marketify 1.0
	 *
	 * @param array $query_vars Existing query variables
	 * @return array $query_vars Modified array of query variables
	 */
	public function query_vars( $query_vars ) {
		$query_vars[] = 'author_ptype';

		return $query_vars;
	}

	/**
	 * Create the new permalink endpoints/structures.
	 *
	 * @since Marketify 1.0
	 *
	 * @return object $wp_rewrite
	 */
	public function rewrites() {
		global $wp_rewrite;

		$new_rules = array(
			'author/([^/]+)/([^/]+)/?$' => 'index.php?author_name=' . $wp_rewrite->preg_index(1) . '&author_ptype=' . $wp_rewrite->preg_index(2),
		);

		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

		return $wp_rewrite->rules;
	}

	/**
	 * Filter the query when we are viewing an author
	 * archive. Set the post type to be the type set in the permastruct.
	 *
	 * @since Marketify 1.0
	 *
	 * @param object $query Current WP_Query
	 * @return void
	 */
	public function filter_endpoints( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_author() )
			return;

		$type = get_query_var( 'author_ptype' );

		if ( ! $type )
			return $query;

		if ( ! in_array( $type, array( 'downloads' ) ) )
			return $query;

		$query->set( 'post_type', 'download' );
		$query->set( 'is_author', true );
	}
}
add_action( 'init', array( 'Marketify_Author', 'init' ), 100 );