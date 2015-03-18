<?php

class Marketify_Activation {

	public function __construct() {
		$this->theme = wp_get_theme( 'marketify' );

		if ( ! isset( $this->theme->Version ) ) {
			$this->theme = wp_get_theme();
		}

		$this->version = get_option( 'marketify_version' );

		if ( $this->version && version_compare( $this->version, $this->theme->Version, '<' ) ) {
			$version = str_replace( '.', '', $this->theme->Version );

			$this->upgrade( $version );
		}

		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ), 10, 2 );
	}

	public function upgrade( $version ) {
		$upgrade = '_upgrade_' . $version;

		if ( method_exists( $this, $upgrade ) ) {
			$this->$upgrade();
		}
		
		$this->set_version();
	}

	public function after_switch_theme( $theme, $old ) {
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
		update_option( 'jobify_version', $this->theme->version );
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
				case 'jobify_listings_display_area' :
					set_theme_mod( 'job-display-sidebar', $value );
					set_theme_mod( 'resume-display-sidebar', $value );
					remove_theme_mod( 'jobify_listings_display_area' );
			}
		}
	}

}
