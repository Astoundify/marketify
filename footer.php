<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Marketify
 */
?>

	<footer id="colophon" class="site-footer site-footer--<?php echo esc_attr( get_theme_mod( 'footer-style', 'light' ) ); ?>" role="contentinfo">
		<div class="container">
			<?php do_action( 'marketify_footer_above' ); ?>

			<div class="footer-widget-areas">
				<div class="widget widget--site-footer">
					<?php dynamic_sidebar( 'footer-1' ); ?>

					<?php
					if ( ! is_active_widget( false, false, 'marketify-widget-footer-social-icons', true ) ) {
						the_widget( 'Marketify_Widget_Footer_Social_Icons', array(), array(
							'before_widget' => '<aside>',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title widget-title--site-footer">',
							'after_title'   => '</h3>',
						) );
					}
					?>
				</div>

				<div class="widget widget--site-footer">
					<?php dynamic_sidebar( 'footer-2' ); ?>

					<?php
					if ( ! is_active_widget( false, false, 'marketify-widget-footer-contact', true ) ) {
						the_widget( 'Marketify_Widget_Footer_Contact', array(), array(
							'before_widget' => '<aside>',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title widget-title--site-footer">',
							'after_title'   => '</h3>',
						) );
					}
					?>
				</div>

				<div class="widget widget--site-footer">
					<?php dynamic_sidebar( 'footer-3' ); ?>

					<div class="widget widget--site-footer">
						<aside>
							<h3 class="site-title site-title--footer"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php if ( esc_attr( get_theme_mod( 'footer-copyright-logo', false ) ) ) : ?>
									<img src="<?php echo esc_attr( get_theme_mod( 'footer-copyright-logo', '' ) ); ?>" />
								<?php else : ?>
									<?php bloginfo( 'name' ); ?>
								<?php endif; ?>
							</a></h3>

							<?php echo wp_kses_post( get_theme_mod( 'footer-copyright-text', sprintf( 'Copyright &copy; %s %s', date( 'Y' ), get_bloginfo( 'name' ) ) ) ); ?>
						</aside>
					</div>
				</div>
			</div>

			<?php do_action( 'marketify_footer_site_info' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
