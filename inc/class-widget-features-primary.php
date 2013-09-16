<?php
/**
 * Related Items
 *
 * @since Marketify 1.0
 */
class Marketify_Widget_Features_Primary extends Marketify_Widget {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_features_primary';
		$this->widget_description = __( 'Display a grid of primary site features.', 'marketify' );
		$this->widget_id          = 'marketify_widget_features_primary';
		$this->widget_name        = __( 'Features - Primary', 'marketify' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Title:', 'marketify' )
			),
			'category' => array(
				'type'  => 'select',
				'std'   => '',
				'label' => __( 'Features Category:', 'marketify' ),
				'options' => $this->categories()
			),
		);
		parent::__construct();

		add_filter( 'woothemes_features_item_template', array( $this, 'item_template' ), 10, 2 );
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

		$title  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		echo $before_widget;

		if ( $title ) echo $before_title . $title . $after_title;
		
		echo '<div class="row">';
		woothemes_features();
		echo '</div>';

		echo $after_widget;

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}

	function categories() {
		$categories = get_terms( array( 'feature-category' ), array( 'hide_empty' => false ) );

		if ( empty( $categories ) || is_wp_error( $categories ) )
			return array();

		$keys  = wp_list_pluck( $categories, 'term_id' );
		$names = wp_list_pluck( $categories, 'name' );

		$categories = array_combine( $keys, $names );

		return $categories; 
	}

	function item_template( $template, $args ) {
		return '<div class="%%CLASS%% col-lg-4 col-sm-6 col-xs-12">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
	}
}