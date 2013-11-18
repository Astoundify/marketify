<?php
/**
 * Solioquy Hero Slider
 *
 * @since Marketify 1.0
 */
class Marketify_Widget_Slider_Soliloquy extends Marketify_Widget {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'marketify_widget_slider_hero';
		$this->widget_description = __( 'Display "Hero" Soliloquy slider.', 'marketify' );
		$this->widget_id          = 'marketify_widget_slider_hero';
		$this->widget_name        = __( 'Marketify Solioquy Slider', 'marketify' );
		$this->settings           = array(
			'slider' => array(
				'type'    => 'select',
				'label'   => __( 'Slider:', 'marketify' ),
				'options' => $this->slider_options(),
				'std'     => 0
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

		extract( $args );
		
		$slider     = absint( $instance[ 'slider' ] );
		
		echo '</div>';

		echo $before_widget;
		
		if ( function_exists( 'soliloquy_slider' ) ) {
			add_filter( 'tgmsp_caption_output', array( $this, 'caption_output' ), 10, 3 );
			add_filter( 'tgmsp_slider_width_output', array( $this, 'width_height' ) );
			add_filter( 'tgmsp_slider_height_output', array( $this, 'width_height' ) );
			add_filter( 'tgmsp_image_output', array( $this, 'image_output' ), 10, 5 );

			soliloquy_slider( $slider );

			remove_filter( 'tgmsp_caption_output', array( $this, 'caption_output' ), 10, 3 );
			remove_filter( 'tgmsp_slider_width_output', array( $this, 'width_height' ) );
			remove_filter( 'tgmsp_slider_height_output', array( $this, 'width_height' ) );
			remove_filter( 'tgmsp_image_output', array( $this, 'image_output' ), 10, 5 );
		}

		echo $after_widget;

		echo '<div class="container">';
	}

	function width_height() {
		return;
	}

	function image_output( $output, $id, $image, $alt, $title ) {
		$retina      = get_post_meta( $id, '_soliloquy_settings', true );
		$retina      = isset ( $retina[ 'retina' ] ) ? 1 : 0;

		if ( $retina ) {
			$output = '<img class="soliloquy-item-image" src="' . esc_url( $image['src'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" width="' . $image[ 'width' ] / 2 . '" height="' . $image[ 'height' ] / 2 . '" />';
		}

		return $output;
	}

	function caption_output( $output, $id, $image ) {
		$caption = '<div class="soliloquy-caption-wrap">';
		$caption .= '<h2 class="soliloquy-caption-title">' . $image[ 'title' ] . '</h2>';
		$caption .= '<div class="soliloquy-caption"><div class="soliloquy-caption-inside">';
		$caption .= '<div class="caption-full">';
		$caption .= wpautop( $image[ 'caption' ] );
		$caption .= '</div>';
		$caption .= '<div class="caption-alt">';
		$caption .= wpautop( isset ( $image[ 'alt' ] ) ? $image[ 'alt' ] : null );
		$caption .= wpautop( sprintf( '<a href="%s" class="button">%s</a>', $image[ 'link' ], $image[ 'linktitle' ] ) );
		$caption .= '</div>';
		$caption .= '</div></div>';
		$caption .= '</div>';

		return $caption;
	}

	function slider_options() {
		$sliders  = new WP_Query( array(
			'post_type'              => array( 'soliloquy' ),
			'no_found_rows'          => true,
			'nopaging'               => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false
		) );

		if ( ! $sliders->have_posts() )
			return array();

		$_sliders = array_combine(
			wp_list_pluck( $sliders->posts, 'ID' ),
			wp_list_pluck( $sliders->posts, 'post_title' )
		);

		return $_sliders;
	}
}

function marketify_tgmsp_end_of_settings( $post ) {
	$retina      = get_post_meta( $post->ID, '_soliloquy_settings', true );
	$retina      = isset ( $retina[ 'retina' ] ) ? 1 : 0;
?>
	<tr id="soliloquy-retina-box" valign="middle">
		<th scope="row"><label for="soliloquy-retina"><?php _e( 'Retina Images', 'marketify' ); ?></label></th>
		<td>
			<input id="soliloquy-retina" type="checkbox" name="_soliloquy_settings[retina]" value="<?php echo $retina; ?>" <?php checked( $retina, 1 ); ?> />
			<span class="description"><?php _e( 'Load images for retina (upload <code>@2x</code> images)', 'marketify' ); ?></span>
		</td>
	</tr>
<?php
}
add_action( 'tgmsp_end_of_settings', 'marketify_tgmsp_end_of_settings' );