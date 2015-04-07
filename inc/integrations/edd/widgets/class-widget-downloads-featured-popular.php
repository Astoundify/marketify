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
		$this->has_featured = marketify()->get( 'edd-featured-downloads' );

		$this->widget_cssclass    = 'marketify_widget_featured_popular';
		$this->widget_description = sprintf( __( 'Display featured and popular %s in sliding grid.', 'marketify' ), edd_get_label_plural() );
		$this->widget_id          = 'marketify_widget_featured_popular';
		$this->widget_name        = sprintf( __( 'Marketify - Home:  Featured &amp; Popular %s', 'marketify' ), edd_get_label_plural() );

		$this->settings           = array(
			'popular-title' => array(
				'type'  => 'text',
				'std'   => 'Popular',
				'label' => __( 'Popular Title:', 'marketify' )
			),
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 6,
				'label' => __( 'Number to display:', 'marketify' )
			),
			'timeframe' => array(
				'type'  => 'select',
				'std'   => 'week',
				'label' => __( 'Based on the current:', 'marketify' ),
				'options'   => array(
					'day'   => __( 'Day', 'marketify' ),
					'week'  => __( 'Week', 'marketify' ),
					'month' => __( 'Month', 'marketify' ),
					'year'  => __( 'Year', 'marketify' ),
					'all'   => __( 'All Time', 'marketify' )
				)
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

		if ( $this->has_featured ) {
			$this->settings[ 'featured-title' ] = array(
				'type'  => 'text',
				'std'   => 'Featured',
				'label' => __( 'Featured Title:', 'marketify' )
			);
		}

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
		ob_start();

		global $post;

		extract( $args );

		$number    = isset( $instance[ 'number' ] ) ? absint( $instance[ 'number' ] ) : 8;
		$timeframe = isset( $instance[ 'timeframe' ] ) ? $instance[ 'timeframe' ] : 'week';
		$f_title   = isset( $instance[ 'featured-title' ] ) ? $instance[ 'featured-title' ] : __( 'Featured', 'marketify' );
		$p_title   = isset( $instance[ 'popular-title' ] ) ? $instance[ 'popular-title' ] : __( 'Popular', 'marketify' );

		echo $before_widget;
	?>

		<h1 class="home-widget-title">
			<?php if ( $this->has_featured ) : ?>
				<span><?php echo esc_attr( $f_title ); ?> </span>
			<?php endif; ?>

			<span><?php echo esc_attr( $p_title ); ?></span>
		</h1>

		<?php if ( $this->has_featured ) : ?>
			<div id="items-featured" class="featured-popular-slick">
				<?php echo do_shortcode( '[edd_featured_downloads]' ); ?>
			</div>
		<?php endif; ?>

		<div id="items-popular" class="featured-popular-slick">
			<?php echo do_shortcode( '[downloads flat=true orderby=sales]' ); ?>
		</div>

	<?php
		echo $after_widget;

		$content = ob_get_clean();

		echo $content;
	}
}
