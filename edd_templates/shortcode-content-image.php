<?php
/**
 *
 */

global $post;
?>

<div class="entry-image">
	<div class="overlay">
		<?php if ( function_exists( 'zilla_likes' ) ) zilla_likes(); ?>

		<div class="actions">
			<?php marketify_purchase_link( get_the_ID() ); ?>
			<a href="<?php the_permalink(); ?>" rel="bookmark" class="button">Details</a>
		</div>

		<strong class="item-price"><span><?php printf( __( 'Item Price: %s', 'marketify' ), edd_price( get_the_ID(), false ) ); ?></span></strong>
	</div>

	<?php if ( class_exists( 'MultiPostThumbnails' ) && MultiPostThumbnails::get_the_post_thumbnail( 'download', 'grid-image' ) ) : ?>
		<?php MultiPostThumbnails::the_post_thumbnail( 'download', 'grid-image', null, 'content-grid-download' ); ?>
	<?php else : ?>
		<?php the_post_thumbnail( 'content-grid-download' ); ?>
	<?php endif; ?>
</div>

<?php locate_template( array( 'modal-download-purchase.php' ), true, false ); ?>