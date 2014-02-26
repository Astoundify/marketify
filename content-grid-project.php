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

		<div class="entry-meta">
			<?php do_action( 'marketify_download_entry_meta_before_' . get_post_format() ); ?>

			<?php if ( marketify_is_multi_vendor() ) : ?>
				<?php
					printf(
						__( '<span class="byline"> by %1$s</span>', 'marketify' ),
						sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
							Marketify_Author::url( 'downloads', get_the_author_meta( 'ID' ) ),
							esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), get_the_author() ) ),
							esc_html( get_the_author_meta( 'display_name' ) ),
							get_avatar( get_the_author_meta( 'ID' ), 50, apply_filters( 'marketify_default_avatar', null ) )
						)
					);
				?>
			<?php endif; ?>

			<?php do_action( 'marketify_download_entry_meta_after_' . get_post_format() ); ?>
		</div>
	</header><!-- .entry-header -->
</article><!-- #post-## -->