<?php

class Marketify_Template_Footer {

	public function __construct() {
		add_action( 'marketify_footer_site_info', array( $this, 'social_menu' ), 10 );
		add_action( 'marketify_footer_site_info', array( $this, 'contact_address' ), 20 );
		add_action( 'marketify_footer_site_info', array( $this, 'site_info' ), 30 );

		add_action( 'marketify_footer_above', array( $this, 'footer_widget_areas' ) );
	}

	public function footer_widget_areas() {
		if ( ! ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3 ' ) ) ) {
			return;
		}
	?>
		<div class="footer-widget-areas row">
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="widget widget--site-footer">
				<?php dynamic_sidebar( 'footer-' . $i ); ?>
			</div>
		<?php endfor; ?>
		</div>
	<?php
	}

	private function has_social_menu() {
		return has_nav_menu( 'social' );
	}

	public function social_menu() {
		if ( ! $this->has_social_menu() ) {
			return;
		}
	?>
		<div class="widget widget--site-footer">
			<aside>
				<h3 class="widget-title widget-title--site-footer"><?php echo marketify()->template->navigation->get_theme_menu_name( 'social' ); ?></h3>
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
	<?php
	}

	private function has_contact_address() {
		return get_theme_mod( 'footer-contact-us-display', 'on' );
	}

	public function contact_address() {
		if ( ! $this->has_contact_address() ) {
			return;
		}
	?>
		<div class="widget widget--site-footer">
			<aside>
				<h3 class="widget-title widget-title--site-footer"><?php echo esc_attr( get_theme_mod( 'footer-contact-us-title', 'Contact Us' ) ); ?></h3>

				<?php echo do_shortcode( wp_kses_post( get_theme_mod( 'footer-contact-us-address', '393 Bay Street, 2nd Floor Toronto, Ontario, Canada, L9T8S2' ) ) ); ?>
			</aside>
		</div>
	<?php
	}

	public function has_site_info() {
		return get_theme_mod( 'footer-copyright-display', 'on' );
	}

	public function site_info() {
	?>
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
	<?php
	}

}
