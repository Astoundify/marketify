<?php

class Marketify_EDD_Product_Reviews extends Marketify_Integration {

	public function __construct() {
		$this->includes = array(
			'widgets/class-widget-download-review-details.php',
			'class-edd-product-reviews-widgets.php'
		);

		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_filter( 'edd_reviews_reviews_title', array( $this, 'section_title' ) );

		add_action( 'marketify_edd_rating', array( $this, 'output_in_comment' ) );
		add_filter( 'edd_reviews_ratings_html', '__return_false', 10, 2 );

		add_action( 'marketify_download_info', array( $this, 'output_stars' ) );
		add_action( 'marketify_download_content_image_overlay_after', array( $this, 'output_stars' ) );

		add_filter( 'edd_reviews_rating_box', array( $this, 'output_comment_form' ) );
	}

	public function init() {
		$this->widgets = new Marketify_EDD_Product_Reviews_Widgets();
	}

	function section_title( $title ) {
		return __( 'Customer Reviews', 'marketify' );
	}

	function output_in_comment( $comment ) {
		global $post;
	?>
		<div class="marketify-edd-rating">
			<?php echo $this->output_stars( $comment->comment_ID ); ?>

			<span itemprop="name" class="review-title-text"><?php echo get_comment_meta( $comment->comment_ID, 'edd_review_title', true ); ?></span>
		</div>
	<?php
	}

	function output_stars( $comment_id = null ) {
		global $post;

		if ( ! $comment_id ) {
			$rating = edd_reviews()->average_rating( false );
		} else {
			$rating = get_comment_meta( $comment_id, 'edd_rating', true );
		}
	?>
		<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating">
			<?php for ( $i = 1; $i <= $rating; $i++ ) : ?>
			<i class="icon-star"></i>
			<?php endfor; ?>

			<?php for( $i = 0; $i < ( 5 - $rating ); $i++ ) : ?>
			<i class="icon-star2"></i>
			<?php endfor; ?>

			<div style="display:none" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				<meta itemprop="worstRating" content="1" />
				<span itemprop="ratingValue"><?php echo $rating; ?></span>
				<span itemprop="bestRating">5</span>
			</div>
		</div>
	<?php
	}

	function output_comment_form() {
		ob_start();
	?>
		<span class="edd_reviews_rating_box">
			<span class="edd_ratings">
				<a class="edd_rating" href="" data-rating="5"><i class="icon-star2"></i></a>
				<span class="edd_show_if_no_js"><input type="radio" name="edd_rating" id="edd_rating" value="5"/>5&nbsp;</span>

				<a class="edd_rating" href="" data-rating="4"><i class="icon-star2"></i></a>
				<span class="edd_show_if_no_js"><input type="radio" name="edd_rating" id="edd_rating" value="4"/>4&nbsp;</span>

				<a class="edd_rating" href="" data-rating="3"><i class="icon-star2"></i></a>
				<span class="edd_show_if_no_js"><input type="radio" name="edd_rating" id="edd_rating" value="3"/>3&nbsp;</span>

				<a class="edd_rating" href="" data-rating="2"><i class="icon-star2"></i></a>
				<span class="edd_show_if_no_js"><input type="radio" name="edd_rating" id="edd_rating" value="2"/>2&nbsp;</span>

				<a class="edd_rating" href="" data-rating="1"><i class="icon-star2"></i></a>
				<span class="edd_show_if_no_js"><input type="radio" name="edd_rating" id="edd_rating" value="1"/>1&nbsp;</span>
			</span>
		</span>
	<?php
		return ob_get_clean();
	}

}
