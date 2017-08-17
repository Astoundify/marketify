<?php

class Marketify_Template_Footer {

	public function __construct() {
		add_action( 'marketify_footer_above', array( $this, 'footer_widget_areas' ) );
	}

	public function footer_widget_areas() {
		if ( ! ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3 ' ) ) ) {
			return;
		}
	?>

<div class="footer-widget-areas">
	<div class="widget widget--site-footer">
		<?php dynamic_sidebar( 'footer-1' ); ?>

		<div class="widget widget--site-footer">
			<aside>
				<h3 class="widget-title widget-title--site-footer"><?php echo esc_attr( marketify()->template->navigation->get_theme_menu_name( 'social' ) ); ?></h3>
				<?php
					$social = wp_nav_menu( array(
						'theme_location'  => 'social',
						'container_class' => 'footer-social',
						'items_wrap'      => '%3$s',
						'depth'           => 1,
						'echo'            => false,
						'link_before'     => '<span class="screen-reader-text">',
						'link_after'      => '</span>',
					) );

					echo strip_tags( $social, '<a><div><span>' );
				?>
			</aside>
		</div>
	</div>

	<div class="widget widget--site-footer">
		<?php dynamic_sidebar( 'footer-2' ); ?>

		<div class="widget widget--site-footer">
			<aside>
				<h3 class="widget-title widget-title--site-footer"><?php echo esc_attr( get_theme_mod( 'footer-contact-us-title', 'Contact Us' ) ); ?></h3>

				<?php echo do_shortcode( wp_kses_post( get_theme_mod( 'footer-contact-us-address', '393 Bay Street, 2nd Floor Toronto, Ontario, Canada, L9T8S2' ) ) ); ?>
			</aside>
		</div>
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

	<?php
	}

}
