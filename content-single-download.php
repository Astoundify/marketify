<?php
/**
 * @package Marketify
 */
?>

<?php do_action( 'marketify_single_download_before' ); ?>

<h2 class="section-title"><span><?php _e( 'About the Product', 'marketify' ); ?></span></h2>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php do_action( 'marketify_single_download_content_before' ); ?>

		<?php the_content(); ?>

		<?php do_action( 'marketify_single_download_content_after' ); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'marketify' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php locate_template( array( 'modal-download-purchase.php' ), true, false ); ?>
</article><!-- #post-## -->

<?php do_action( 'marketify_single_download_after' ); ?>
