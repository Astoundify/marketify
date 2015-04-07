<?php

class Marketify_EDD_Template_Download {

	public function __construct() {
		add_action( 'init', array( $this, 'featured_area' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_action( 'marketify_entry_before', array( $this, 'featured_area_header_actions' ), 5 );

		add_action( 'marketify_download_info', array( $this, 'download_price' ), 5 );
		add_action( 'marketify_download_actions', array( $this, 'demo_link' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'marketify-download', get_template_directory_uri() . '/js/download/download.js', array( 'marketify' ) );

		$args = array(
			'format' => get_post_format() ? get_post_format() : 'standard',
			'featuredLocation' => $this->get_featured_area_location()
		);

		wp_localize_script( 'marketify-download', 'marketifyDownload', $args );
	}

	public function download_price() {
		global $post;

		edd_price( $post->ID );
	}

	function demo_link( $download_id = null ) {
		global $post, $edd_options;

		if ( 'download' != get_post_type() ) {
			return;
		}

		if ( ! $download_id ) {
			$download_id = $post->ID;
		}

		$field = apply_filters( 'marketify_demo_field', 'demo' );
		$demo  = get_post_meta( $download_id, $field, true );

		if ( ! $demo ) {
			return;
		}

		$label = apply_filters( 'marketify_demo_button_label', __( 'Demo', 'marketify' ) );

		if ( $post->_edd_cp_custom_pricing ) {
			echo '<br /><br />';
		}

		echo apply_filters( 'marketify_demo_link', sprintf( '<a href="%s" class="button" target="_blank">%s</a>', esc_url( $demo ), $label ) );
	}

	private function get_featured_area_location() {
		return marketify_theme_mod( 'download-feature-area' );
	}

	private function get_featured_images() {
		global $post;

		$images  = array();
		$_images = get_post_meta( $post->ID, 'preview_images', true );

		if ( is_array( $_images ) && ! empty( $_images ) ) {
			foreach ( $_images as $image ) {
				$images[] = get_post( $image );
			}
		} else {
			$images = get_attached_media( 'image', $post );
		}

		return $images;
	}

	public function featured_area() {
		global $post;

		$format = get_post_format();

		if ( '' == $format ) {
			$format = 'standard';
		}

		if ( 'top' == $this->get_featured_area_location() ) {
			add_action( 'marketify_entry_before', array( $this, 'featured_' . $format ), 9 );
		} else {
			add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_' . $format ) );

			if ( 'standard' == $format ) {
				add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_standard_navigation' ), 11 );
			}
		}
	}

	public function featured_area_header_actions() {
		if ( 'top' != $this->get_featured_area_location() ) {
			return;
		}
	?>
		<div class="download-actions">
			<?php do_action( 'marketify_download_actions' ); ?>
		</div>

		<div class="download-info">
			<?php do_action( 'marketify_download_info' ); ?>
		</div>
	<?php
	}

	public function featured_standard() {
		$images = $this->get_featured_images();
		$before = '<div class="download-gallery">';
		$after  = '</div>';

		$size = apply_filters( 'marketify_featured_standard_image_size', 'fullsize' );

		echo $before;
		if ( empty( $images ) && has_post_thumbnail( get_the_ID() ) ) {
			echo get_the_post_thumbnail( get_the_ID(), $size );
			echo $after;
			return;
		} else {
	?>
		<?php foreach ( $images as $image ) : ?>
		<div class="download-gallery__image"><?php echo wp_get_attachment_image( $image->ID, $size ); ?></div>
		<?php endforeach; ?>
	<?php
		}
		
		echo $after;
	}

	public function featured_standard_navigation() {
		$images = $this->get_featured_images();
		$before = '<div class="download-gallery-navigation">';
		$after  = '</div>';

		$size = apply_filters( 'marketify_featured_standard_image_size_navigation', 'thumbnail' );

		if ( empty( $images ) && has_post_thumbnail( get_the_ID() ) ) {
			return;
		} 

		echo $before;

		foreach ( $images as $image ) {
	?>
		<div class="download-gallery-navigation__image"><?php echo wp_get_attachment_image( $image->ID, $size ); ?></div>
	<?php
		}
		
		echo $after;
	}

	public function featured_audio() {
		echo 'audio';
	}

	public function featured_video() {
		echo 'video';
	}

}
