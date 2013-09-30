<?php
/**
 * Download Details
 *
 * @since Marketify 1.0
 */
class Marketify_Widget_Download_Details extends Marketify_Widget {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_download_details';
		$this->widget_description = __( 'Display information related to the current download', 'marketify' );
		$this->widget_id          = 'marketify_widget_download_details';
		$this->widget_name        = __( 'Marketify Download Product Details', 'marketify' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => 'Product Details',
				'label' => __( 'Title:', 'marketify' )
			)
		);
		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();

		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		
		echo $before_widget;

		if ( $title ) echo '<h1 class="section-title"><span>' . $title . '</span></h1>';
		?>
			<div class="download-product-details">
				<?php do_action( 'marketify_product_details_before', $instance ); ?>

				<div class="download-author">
					<?php do_action( 'marketify_download_author_before' ); ?>
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 50 ); ?>
					<?php printf( '<a class="author-link" href="%s" rel="author">%s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() ); ?>
					<span class="author-joined"><?php printf( __( 'Author since: %s', 'marketify' ), date_i18n( get_option( 'date_format' ), strtotime( get_the_author_meta( 'user_registered' ) ) ) ); ?></span>
					<?php do_action( 'marketify_download_author_after' ); ?>
				</div>
				
				<div class="download-purchases">
					<strong>3000</strong>
					Purchases
				</div>
				
				<div class="download-comments">
					<a href="#"><strong>450</strong>
					Comments</a>
				</div>

				<?php do_action( 'marketify_product_details_after', $instance ); ?>
			</div>
		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}
}