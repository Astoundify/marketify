<?php
/**
 * Popular downloads
 *
 * @since Marketify 1.0
 */

?>

<div class="marketify_widget_featured_popular popular">

	<h1 class="section-title"><span><?php printf( __( 'Popular in %s', 'marketify' ), single_term_title( '', false ) ); ?></span></h1>

	<div class="featured-popular-tabs">
		<div id="items-popular" class="inactive featured-popular-slick">
			<?php echo do_shortcode( '[downloads number=6 flat=true orderby=sales]' ); ?>
		</div>
	</div>

</div>
