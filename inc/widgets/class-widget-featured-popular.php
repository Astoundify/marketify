<?php
/**
 * Related Items
 *
 * @since Marketify 1.0
 */
class Marketify_Widget_Featured_Popular_Downloads extends Marketify_Widget {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_featured_popular';
		$this->widget_description = sprintf( __( 'Display featured and popular %s in sliding grid.', 'marketify' ), edd_get_label_plural() );
		$this->widget_id          = 'marketify_widget_featured_popular';
		$this->widget_name        = sprintf( __( 'Marketify Featured &amp; Popular %s', 'marketify' ), edd_get_label_plural() );
		$this->settings           = array(
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 6,
				'label' => __( 'Number to display:', 'marketify' )
			),
			'scroll' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Automatically scroll items', 'marketify' )
			),
			'speed' => array(
				'type'  => 'text',
				'std'   => 7000,
				'label' => __( 'Slideshow Speed (ms)', 'marketify' )
			),
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

		global $post;

		extract( $args );

		$number = isset ( $instance[ 'number' ] ) ? absint( $instance[ 'number' ] ) : 8;

		$featured = new WP_Query( array(
			'post_type'              => 'download',
			'posts_per_page'         => $number,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'cache_results'          => false,
			'meta_query'             => array(
				array(
					'key'   => 'edd_feature_download',
					'value' => true
				)
			)
		) );

		$popular = new WP_Query( array(
			'post_type'              => 'download',
			'posts_per_page'         => $number,
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'cache_results'          => false
		) );

		if ( ! $featured->have_posts() && ! $popular->have_posts() )
			return;

		echo $before_widget;

		?>

		<?php echo $before_title; ?>
			<?php _e( 'Featured', 'marketify' ); ?> </span> 
			<span><?php _e( 'Popular', 'marketify' ); ?>
		<?php echo $after_title; ?>
		
		<div id="items-featured" class="row flexslider">
			<ul class="slides">
				<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
				<li class="col-lg-3 col-sm-6">
					<?php get_template_part( 'content-grid', 'download' ); ?>
				</li>
				<?php endwhile; ?>
			</ul>
		</div>

		<div id="items-popular" class="row flexslider">
			<ul class="slides">
				<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
				<li class="col-lg-3 col-sm-6">
					<?php get_template_part( 'content-grid', 'download' ); ?>
				</li>
				<?php endwhile; ?>
			</ul>
		</div>

		<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}
}