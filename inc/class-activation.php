<?php

class Jobify_Activation {

	public function __construct() {
		$this->theme = wp_get_theme( 'jobify' );

		if ( ! isset( $this->theme->Version ) ) {
			$this->theme = wp_get_theme();
		}

		$this->version = get_option( 'jobify_version' );

		if ( $this->version && version_compare( $this->version, $this->theme->Version, '<' ) ) {
			$version = str_replace( '.', '', $this->theme->Version );

			$this->upgrade( $version );
		}

		add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ), 10, 2 );
		add_action( 'add_option_job_manager_installed_terms', array( $this, 'enable_categories' ) );
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
		if ( get_option( 'jobify_version' ) ) {
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

		wp_safe_redirect( admin_url( 'themes.php?page=jobify-setup' ) );

		exit();
	}

	public function enable_categories() {
		update_option( 'job_manager_enable_categories', 1 );
	}

	private function _upgrade_205() {
		$theme_mods = get_theme_mods();
		
		foreach ( $theme_mods as $mod => $value ) {
			switch ($mod) {
				case 'jobify_listings_display_area' :
					set_theme_mod( 'job-display-sidebar', $value );
					set_theme_mod( 'resume-display-sidebar', $value );
					remove_theme_mod( 'jobify_listings_display_area' );
				case 'jobify_listings_topbar_columns' :
					set_theme_mod( 'job-display-sidebar-columns', $value );
					set_theme_mod( 'resume-display-sidebar-columns', $value );
					remove_theme_mod( 'jobify_listings_topbar_columns' );
				case 'header_background' :
					set_theme_mod( 'color-header-background', $value );
					remove_theme_mod( 'header_background' );
				case 'navigation' :
					set_theme_mod( 'color-navigation-background', $value );
					remove_theme_mod( 'navigation' );
				case 'primary' :
					set_theme_mod( 'color-primary', $value );
					remove_theme_mod( 'primary' );
				case 'jobify_cta_display' :
					set_theme_mod( 'cta-display', $value );
					remove_theme_mod( 'jobify_cta_display' );
				case 'jobify_cta_text' :
					set_theme_mod( 'cta-text', $value );
					remove_theme_mod( 'jobify_cta_text' );
				case 'jobify_cta_text_color' :
					set_theme_mod( 'cta-text-color', $value );
					remove_theme_mod( 'jobify_cta_text_color' );
				case 'jobify_cta_background_color' :
					set_theme_mod( 'cta-background-color', $value );
					remove_theme_mod( 'jobify_cta_background_color' );
				case 'clusters' :
					set_theme_mod( 'map-behavior-clusters', $value );
					remove_theme_mod( 'clusters' );
				case 'grid-size' :
					set_theme_mod( 'map-behavior-grid-size', $value );
					remove_theme_mod( 'grid-size' );
				case 'autofit' :
					set_theme_mod( 'map-behavior-autofit', $value );
					remove_theme_mod( 'autofit' );
				case 'center' :
					set_theme_mod( 'map-behavior-center', $value );
					remove_theme_mod( 'center' );
				case 'zoom' :
					set_theme_mod( 'map-behavior-zoom', $value );
					remove_theme_mod( 'zoom' );
				case 'max-zoom' :
					set_theme_mod( 'map-behavior-max-zoom', $value );
					remove_theme_mod( 'max-zoomg' );
			}
		}
	}

}
