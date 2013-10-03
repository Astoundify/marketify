<?php
/**
 * @package Marketify
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-grid-download' ); ?>>
	<div class="entry-image">
		<div class="overlay">
			<div class="actions">
				<?php marketify_purchase_link( get_the_ID() ); ?>
				<a href="<?php the_permalink(); ?>" rel="bookmark" class="button">Details</a>
			</div>

			<strong class="item-price"><span>Item Price: $55</span></strong>
		</div>

		<?php the_post_thumbnail( 'content-grid-download' ); ?>
	</div>

	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<div class="entry-meta">
			<?php if ( marketify_is_multi_vendor() ) : ?>
				<?php
					printf( 
						__( '<span class="byline"> by %1$s</span>', 'marketify' ),
						sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
							esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
							esc_attr( sprintf( __( 'View all %s by %s', 'marketify' ), edd_get_label_plural(), get_the_author() ) ),
							esc_html( get_the_author_meta( 'user_login' ) ),
							get_avatar( get_the_author_meta( 'ID' ), 25, apply_filters( 'marketify_default_avatar', null ) )
						)
					);
				?>
			<?php endif; ?>

			<?php do_action( 'marketify_download_entry_meta' ); ?>
		</div>
	</header><!-- .entry-header -->

	<?php locate_template( array( 'modal-download-purchase.php' ), true ); ?>
</article><!-- #post-## -->
