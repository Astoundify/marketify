<?php

class Marketify_Author {
	public function __construct() {
		add_filter( 'query_vars', array( $this, 'query_vars' ) ); 
		add_filter( 'generate_rewrite_rules', array( $this, 'rewrites' ) );
		add_action( 'pre_get_posts', array( $this, 'filter_endpoints' ) );
	}

	public static function init() {
		new self;
	}

	public function query_vars( $query_vars ) {
		$query_vars[] = 'author_ptype';

		return $query_vars;
	}

	public function rewrites() {
		global $wp_rewrite;

		$new_rules = array(
			'author/([^/]+)/([^/]+)/?$' => 'index.php?author_name=' . $wp_rewrite->preg_index(1) . '&author_ptype=' . $wp_rewrite->preg_index(2),
		);

		$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

		return $wp_rewrite->rules;
	}

	public function filter_endpoints( $query ) {
		if ( is_admin() || ! $query->is_main_query() )
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

function marketify_download_price() {
	global $post;

	edd_price( $post->ID );
}
add_action( 'marketify_download_info', 'marketify_download_price', 5 );

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

function marketify_recommended_products_shim() {
	remove_filter( 'edd_after_download_content', 'edd_rp_display_single', 10, 1 );
}
add_action( 'init', 'marketify_recommended_products_shim' );

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