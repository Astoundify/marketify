<?php

class Marketify_WooThemes_Features extends Marketify_Integration {

	public function __construct() {
		parent::__construct( dirname( __FILE__ ) );
	}

	public function setup_actions() {
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );

		add_action( 'marketify_project_actions', array( $this, 'client_link' ) );

		add_action( 'marketify_single_project_content_before_content', array( $this, 'previewer_switcher' ) );
		add_action( 'marketify_project_featured_area', array( $this, 'previewer_standard' ) );
	}

	public function register_sidebars() {
		register_sidebar( array(
			'name'          => __( 'Projects Single Sidebar', 'marketify' ),
			'id'            => 'sidebar-single-project',
			'before_widget' => '<aside id="%1$s" class="widget download-single-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-single-widget-title">',
			'after_title'   => '</h1>',
		) );

		register_sidebar( array(
			'name'          => __( 'Projects Archive Sidebar', 'marketify' ),
			'id'            => 'sidebar-archive-project',
			'before_widget' => '<aside id="%1$s" class="widget download-archive-widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h1 class="download-archive-widget-title">',
			'after_title'   => '</h1>',
		) );
	}

	public function client_link() {
		global $post;

		$client = esc_attr( get_post_meta( $post->ID, '_client', true ) );
		$url    = esc_url( get_post_meta( $post->ID, '_url', true ) );

		if ( ! $url ) {
			return;
		}

		printf( '<a href="%s" class="button">%s</a>', $url, __( 'Visit Project', 'marketify' ) );
	}

	public function previewer_switcher() {
		if ( 'grid' != marketify_theme_mod( 'product-display', 'product-display-single-style' ) ) {
			return;
		}

		global $post;

		$attachment_ids = projects_get_gallery_attachment_ids( $post->ID );
		$before         = '<div class="download-image-grid-preview">';
		$after          = '</div>';

		echo $before;
		?>

		<div class="row">
			<div class="col-sm-12 image-preview">
				<a id="1" href="<?php echo wp_get_attachment_url( current( $attachment_ids ) ); ?>" class="image-preview-gallery"><?php echo wp_get_attachment_image( current( $attachment_ids ), 'large' ); ?></a>
			</div>

			<div class="col-sm-12 image-grid-previewer">
				<ul class="slides row">
					<?php $i = 1; foreach ( $attachment_ids as $image ) : ?>
					<li class="col-lg-2 col-md-3 col-sm-4 col-xs-6"><a id="<?php echo $i; ?>" href="<?php echo wp_get_attachment_url( $image->ID ); ?>" class="image-preview-gallery"><?php echo wp_get_attachment_image( $image, 'large' ); ?></a></li>
					<?php $i++; endforeach; ?>
				</ul>
			</div>
		</div>

		<?php
		echo $after;
	}

	public function previwer_standard() {
		global $post;

		if ( 'grid' == marketify_theme_mod( 'product-display', 'product-display-single-style' ) ) {
			return;
		}

		$images = projects_get_gallery_attachment_ids();
		$before = '<div class="download-image">';
		$after  = '</div>';

		$before = '<div class="download-image flexslider">';

		echo $before;
		?>

		<ul class="slides">
			<?php foreach ( $images as $image ) : ?>
			<li><?php echo wp_get_attachment_image( $image, 'fullsize' ); ?></li>
			<?php endforeach; ?>
		</ul>

		<?php
		echo $after;
	}

}
