<?php
/**
 *
 */

/**
 * Sidebars and Widgets
 *
 * @since Marketify 1.2
 *
 * @return void
 */
function marketify_projects_widgets_init() {
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
add_action( 'widgets_init', 'marketify_projects_widgets_init' );

if ( ! function_exists( 'marketify_project_client_link' ) ) :
/**
 * Download Purchase Link
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_project_client_link() {
	global $post;

	$client = esc_attr( get_post_meta( $post->ID, '_client', true ) );
	$url    = esc_url( get_post_meta( $post->ID, '_url', true ) );

	printf( '<a href="%s" class="button">%s</a>', $url, __( 'Visit Project' ) );
}
add_action( 'marketify_project_actions', 'marketify_project_client_link' );
endif;

function marketify_single_project_content_before_content() {
	if ( 'grid' != marketify_theme_mod( 'general', 'general-product-single-style' ) )
		return;

	global $post;

	$attachment_ids = projects_get_gallery_attachment_ids();
	$before         = '<div class="download-image-grid-preview">';
	$after          = '</div>';

	echo $before;
	?>

	<div class="row">
		<div class="col-md-10 col-sm-12 image-preview">
			<a id="1" href="<?php echo wp_get_attachment_url( $image->ID ); ?>" class="image-preview-gallery"><?php echo wp_get_attachment_image( current( $attachment_ids ), 'large' ); ?></a>
		</div>

		<div class="col-md-2 col-sm-12">
			<ul class="slides row">
				<?php $i = 1; foreach ( $attachment_ids as $image ) : ?>
				<li class="col-md-12 col-xs-4"><a id="<?php echo $i; ?>" href="<?php echo wp_get_attachment_url( $image ); ?>" class="image-preview-gallery"><?php echo wp_get_attachment_image( $image, 'large' ); ?></a></li>
				<?php $i++; endforeach; ?>
			</ul>
		</div>
	</div>

	<?php
	echo $after;
}
add_action( 'marketify_single_project_content_before_content', 'marketify_single_project_content_before_content' );