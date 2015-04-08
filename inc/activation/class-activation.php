<?php

class Marketify_Activation {

	public function __construct() {
		$this->theme = wp_get_theme( 'marketify' );

		if ( ! isset( $this->theme->Version ) ) {
			$this->theme = wp_get_theme();
		}

		$this->version = get_option( 'marketify_version', 0 );

		if ( version_compare( $this->version, $this->theme->Version, '<' ) ) {
			$version = str_replace( '.', '', $this->theme->Version );

			$this->upgrade( $version );
		}

		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ), 10 );
	}

	public function upgrade( $version ) {
		$upgrade = '_upgrade_' . $version;

		if ( method_exists( $this, $upgrade ) ) {
			$this->$upgrade();
		}
		
		$this->set_version();
	}

	public function after_switch_theme( $theme ) {
		$this->flush_rules();

		// If it's set just update version can cut out
		if ( get_option( 'marketify_version' ) ) {
			$this->set_version();

			return;
		}

		$this->set_version();
		$this->redirect();
	}

	public function set_version() {
		update_option( 'marketify_version', $this->theme->version );
	}

	public function flush_rules() {
		flush_rewrite_rules();
	}

	public function redirect() {
		unset( $_GET[ 'action' ] );

		wp_safe_redirect( admin_url( 'themes.php?page=marketify-setup' ) );

		exit();
	}

	private function _upgrade_200() {
		$theme_mods = get_theme_mods();

		foreach ( $theme_mods as $mod => $value ) {
			switch ($mod) {
				case 'general-downloads-label-singular' :
					set_theme_mod( 'download-label-singular', $value );
					set_theme_mod( 'download-label-generate', 'on' );
				case 'general-downloads-label-plural' :
					set_theme_mod( 'download-label-plural', $value );
				case 'grid-height' :
					set_theme_mod( 'downloads-grid-height', $value );
					remove_theme_mod( 'grid-width' );
					remove_theme_mod( 'grid-crop' );
				case 'product-display-columns' :
					set_theme_mod( 'downloads-columns', $value );
				case 'product-display-single-style' :
					set_theme_mod( 'downloads-columns', $value );
				case 'product-display-grid-info' :
					set_theme_mod( 'download-archives-meta', $value );
				case 'product-display-excerpt' :
					set_theme_mod( 'download-archives-excerpt', $value );
				case 'product-display-truncate-title' :
					set_theme_mod( 'download-archives-truncate-title', $value );
					remove_theme_mod( 'product-display-show-buy' );
				case 'footer-contact-address' :
					set_theme_mod( 'footer-contact-us-adddress', $value );
				case 'footer-logo' :
					set_theme_mod( 'footer-copyright-logo', $value );
				case 'header' :
					set_theme_mod( 'color-page-header-background', $value );
				case 'primary' :
					set_theme_mod( 'color-primary', $value );
				case 'accent' :
					set_theme_mod( 'color-accent', $value );
			}

			remove_theme_mod( $mod );
		}
	}

}
