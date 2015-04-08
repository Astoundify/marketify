<?php

class Marketify_Template_Footer {

	public function __construct() {
		add_action( 'marketify_footer_site_info', array( $this, 'social_menu' ), 10 );
		add_action( 'marketify_footer_site_info', array( $this, 'contact_address' ), 20 );
		add_action( 'marketify_footer_site_info', array( $this, 'site_info' ), 30 );

		add_action( 'marketify_footer_above', array( $this, 'footer_widget_areas' ) );
	}

	public function footer_widget_areas() {
	?>
		<div class="footer-widget-areas row">
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="footer-widget-area footer-widget-area--<?Php echo $i; ?> col-xs-12 col-sm-6 col-md-4">
				<?php dynamic_sidebar( 'footer-' . $i ); ?>
			</div>
		<?php endfor; ?>
		</div>
	<?php
	}

	private function has_social_menu() {
		return marketify()->template->navigation->get_theme_menu( 'social' );
	}	

	public function social_menu() {
		if ( ! $this->has_social_menu() ) {
			return;
		}
	?>
		<div class="<?php echo $this->get_column_class(); ?>">
			<h1 class="footer-widget-title"><?php echo marketify()->template->navigation->get_theme_menu_name( 'social' ); ?></h1>
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
		return marketify_theme_mod( 'footer-contact-us-display' );
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

	public function has_site_info() {
		return marketify_theme_mod( 'footer-copyright-display' ); 
	}

	public function site_info() {
	?>
		<div class="<?php echo $this->get_column_class(); ?>">
			<h1 class="site-title"><a href="<?php echo home_url(); ?>">
				<?php if ( marketify_theme_mod( 'footer-copyright-logo' ) ) : ?>
					<img src="<?php echo marketify_theme_mod( 'footer-copyright-logo' ); ?>" />
				<?php else : ?>
					<?php bloginfo( 'name' ); ?>
				<?php endif; ?>
			</a></h1>
			
			<?php echo esc_attr( marketify_theme_mod( 'footer-copyright-text' ) ); ?>
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

		if ( ! $this->has_site_info() ) {
			$columns--;
		}

		return floor( 12 / $columns );
	}

	private function get_column_class() {
		return sprintf( 'col-xs-12 col-sm-6 col-md-%s', $this->get_column_span() );
	}

}
