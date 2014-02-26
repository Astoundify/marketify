<?php
/**
 * @package Marketify
 */

global $post;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-grid-download' ); ?>>
	<div class="entry-image">
		<div class="overlay">
			<?php do_action( 'marketify_project_content_image_overlay_before' ); ?>

			<div class="actions">
				<a href="<?php the_permalink(); ?>" rel="bookmark" class="button"><?php _e( 'Project Details', 'marketify' ); ?></a>
			</div>

			<strong class="item-price">
				<span><?php printf( 'Client: %s', esc_attr( get_post_meta( $post->ID, '_client', true ) ) ); ?></span>
			</strong>

			<?php do_action( 'marketify_project_content_image_overlay_after' ); ?>
		</div>

		<?php the_post_thumbnail( 'content-grid-download' ); ?>
	</div>

	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
