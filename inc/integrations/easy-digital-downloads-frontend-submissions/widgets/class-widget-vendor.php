<?php

class Marketify_Widget_FES_Vendor extends Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendor_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_fes_vendor';
		$this->widget_description = __( 'Display the vendor avatar and extra information.', 'marketify' );
		$this->widget_id          = 'marketify_widget_fes_vendor';
		$this->widget_name        = __( 'Marketify - Vendor: Vendor', 'marketify' );
		$this->settings           = array(
			'extras' => array(
				'type'  => 'textarea',
				'std'   => '',
				'label' => __( 'Additional Information:', 'marketify' ),
				'description' => __( 'Use the [marketify-vendor key=""] shortcode to provide additional information about the vendor.', 'marketify' )
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
		$url = $this->vendor->url();
		$display_name = $this->vendor->display_name();
		$registered = $this->vendor->date_registered();

		ob_start();

		echo $args[ 'before_widget' ];
	?>
		<div class="download-author">
			<?php echo get_avatar( $this->vendor->obj->ID, 130 ); ?>
			<?php printf( '<a class="author-link" href="%s" rel="author">%s</a>', $url, $display_name ); ?>
			<span class="author-joined"><?php printf( __( 'Author since: %s', 'marketify' ), $registered ); ?></span>

			<?php echo wpautop( do_shortcode( $instance[ 'extras' ] ) ); ?>
		</div>
	<?php
		echo $args[ 'after_widget' ];

		$content = ob_get_clean();

		echo $content;
	}

}