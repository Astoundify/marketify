<?php

class Marketify_Template_Footer {

	public function __construct() {
		add_action( 'marketify_footer_site_info', array( $this, 'social_menu' ), 10 );
		add_action( 'marketify_footer_site_info', array( $this, 'contact_address' ), 20 );
		add_action( 'marketify_footer_site_info', array( $this, 'site_info' ), 30 );
	}

	private function has_social_menu() {
		return Marketify::$template->navigation->get_theme_menu( 'social' );
	}	

	public function social_menu() {
		if ( ! $this->has_social_menu() ) {
			return;
		}
	?>
		<div class="<?php echo $this->get_column_class(); ?>">
			<h1 class="footer-widget-title"><?php echo Marketify::$template->navigation->get_theme_menu_name( 'social' ); ?></h1>
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
		</div>
	<?php
	}

	private function has_contact_address() {
		return marketify_theme_mod( 'footer-contact-address' );
	}

	public function contact_address() {
		if ( ! $this->has_contact_address() ) {
			return;
		}
	?>
		<div class="<?php echo $this->get_column_class(); ?>">
			<h1 class="footer-widget-title"><?php _e( 'Contact Us', 'marketify' ); ?></h1>

			<?php echo wpautop( marketify_theme_mod( 'footer', 'footer-contact-address' ) ); ?>
		</div>
	<?php
	}

	public function site_info() {
	?>
		<div class="<?php echo $this->get_column_class(); ?>">
			<h1 class="site-title"><a href="<?php echo home_url(); ?>">
				<?php if ( marketify_theme_mod( 'footer', 'footer-logo' ) ) : ?>
					<img src="<?php echo marketify_theme_mod( 'footer', 'footer-logo' ); ?>" />
				<?php else : ?>
					<?php bloginfo( 'name' ); ?>
				<?php endif; ?>
			</a></h1>
			
			<?php echo esc_attr( marketify_theme_mod( 'footer-copyright' ) ); ?>
		</div>
	<?php
	}

	private function get_column_span() {
		$columns = 3;

		if ( ! $this->has_social_menu() ) {
			$columns--;
		}

		if ( ! $this->has_contact_address() ) {
			$columns--;
		}

		return floor( 12 / $columns );
	}

	private function get_column_class() {
		return sprintf( 'col-xs-12 col-sm-6 col-md-%s', $this->get_column_span() );
	}

}
