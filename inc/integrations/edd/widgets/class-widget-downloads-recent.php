<?php
/**
 * Related Items
 *
 * @since Marketify 1.0
 */
class Marketify_Widget_Recent_Downloads extends Marketify_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_recent_downloads';
		$this->widget_description = sprintf( __( 'Display recent %s in a grid.', 'marketify' ), edd_get_label_plural() );
		$this->widget_id          = 'marketify_widget_recent_downloads';
		$this->widget_name        = sprintf( __( 'Marketify - Home: Recent %s', 'marketify' ), edd_get_label_plural() );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => 'Recent',
				'label' => __( 'Title:', 'marketify' )
			),
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 8,
				'label' => __( 'Number to display:', 'marketify' )
			),
			'columns' => array(
				'type' => 'select',
				'std' => 3,
				'label' => __( 'Columns:', 'marketify' ),
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4 )
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
		ob_start();

		$title        = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$number       = isset ( $instance[ 'number' ] ) ? absint( $instance[ 'number' ] ) : 8;
		$columns      = isset ( $instance[ 'columns' ] ) ? absint( $instance[ 'columns' ] ) : 3;

		echo $args[ 'before_widget' ];

		if ( $title ) echo $args[ 'before_title' ] . $title . $args[ 'after_title' ];
		?>

		<?php echo do_shortcode( "[downloads columns={$columns} number={$number}]" ); ?>

		<?php
		echo $args[ 'after_widget' ];

		$content = ob_get_clean();

		echo $content;
	}
}
