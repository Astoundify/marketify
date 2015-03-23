<?php
	
class Marketify_Easy_Digital_Downloads_Template {

	public function __construct() {
		add_action( 'init', array( $this, 'featured_area' ) );

		add_filter( 'marketify_archive_title', array( $this, 'archive_title' ) );
	}

	public function author_url( $user_id ) {
		$fes = marketify()->get( 'easy-digital-downloads-frontend-submissions' );

		if ( ! $fes ) {
			$vendor = $this->fes->vendor( $user_id );
			$url = $vendor->url();
		} else {
			$url = get_author_posts_url( $user_id );
		}

		return apply_filters( 'marketify_author_url', esc_url( $url ) );
	}

	public function archive_title( $title ) {
		if ( is_tax() ) {
			$title = single_term_title( '', false );
		} else if ( is_search() ) {
			$title = esc_attr( get_search_query() );
		}

		return $title;
	}

	private function get_featured_area_location() {
		return 'top';
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
			add_action( 'marketify_single_download_content_before_content', array( $this, 'featured_' . $format ) );
		} else {
			if ( 'standard' == $format ) {
				$format = 'grid';
			}

			add_action( 'marketify_single_download_content_before', array( $this, 'featured_' . $format ) );
		}
	}

	public function featured_standard() {
		$images = $this->get_featured_images();
		$before = '<div class="download-image">';
		$after  = '</div>';

		$size = apply_filters( 'marketify_featured_standard_image_size', 'fullsize' );

		if ( empty( $images ) && has_post_thumbnail( get_the_ID() ) ) {
			echo $before;
			echo get_the_post_thumbnail( get_the_ID(), $size );
			echo $after;

			return;
		} else {
			$before = '<div class="download-image flexslider">';

			echo $before;
	?>
		<ul class="slides">
			<?php foreach ( $images as $image ) : ?>
			<li><?php echo wp_get_attachment_image( $image->ID, $size ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php
			echo $after;
		}
	}

	public function featured_grid() {
		echo 'grid';
	}

	public function featured_audio() {
		echo 'audio';
	}

	public function featured_video() {
		echo 'video';
	}

}
