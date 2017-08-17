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

					<?php
					if ( ! is_active_widget( false, false, 'marketify-widget-footer-copyright', true ) ) {
						the_widget( 'Marketify_Widget_Footer_Copyright', array(), array(
							'before_widget' => '<aside>',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title widget-title--site-footer">',
							'after_title'   => '</h3>',
						) );
					}
					?>
				</div>
			</div>

			<?php do_action( 'marketify_footer_site_info' ); ?>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
