<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Marketify
 */
?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">

			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="row">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</div>
			<?php endif; ?>

			<div class="site-info row">
				<div class="col-sm-4">
					<h1 class="footer-widget-title"><?php echo marketify_get_theme_menu_name( 'social' ); ?></h1>

					<?php
						wp_nav_menu( array(
							'theme_location' => 'social'
						) );
					?>
				</div>

				<div class="col-sm-4">
					<h1 class="footer-widget-title"><?php _e( 'Contact Us', 'marketify' ); ?></h1>
				</div>

				<div class="col-sm-4">
					<?php printf( __( '&copy; %d %s. All rights reserved.', 'marketify' ), date( 'Y' ), get_bloginfo( 'name' ) ); ?>
				</div>
			</div><!-- .site-info -->

		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>