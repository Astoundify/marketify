<?php
/**
 * @package Marketify
 */
?>

<h2 class="section-title"><span><?php _e( 'About the Product', 'marketify' ); ?></span></h2>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'marketify' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<div id="modal-buy-now" class="popup">
		<h1 class="section-title"><span><?php _e( 'Buying Options', 'marketify' ); ?></span></h1>

		<?php echo edd_get_purchase_link(); ?>
	</div>
</article><!-- #post-## -->
