<?php

class Marketify_Setup {

    public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		$menus = get_theme_mod( 'nav_menu_locations' );
		$this->theme = Marketify::$activation->theme;

		$has_downloads = new WP_Query( array( 'post_type' => 'downloads', 'fields' => 'ids', 'posts_per_page' => 1 ) );

		$this->steps = array(
			'install-plugins' => array(
				'title' => __( 'Install Required &amp; Recommended Plugins', 'jobify' ),
				'completed' => class_exists( 'Easy_Digital_Downloads' ),
				'documentation' => array(
					'WP Job Manager' => 'http://listify.astoundify.com/article/228-wp-job-manager',
					'WooCommerce' => 'http://listify.astoundify.com/article/229-woocommerce',
					'Jetpack' => 'http://listify.astoundify.com/article/230-jetpack',
					'Bulk Install' => 'http://listify.astoundify.com/article/320-bulk-install-required-and-recommended-plugins'
				)
			),
			'import-content' => array(
				'title' => __( 'Import Demo Content', 'jobify' ),
				'completed' => $has_downloads->have_posts(),
				'documentation' => array(
					'Install Demo Content' => 'http://listify.astoundify.com/article/236-installing-demo-content',
					'Manually Add a Listing' => 'http://listify.astoundify.com/article/245-adding-a-listing',
					'Importing Content (Codex)' => 'http://codex.wordpress.org/Importing_Content',
					'WordPress Importer' => 'https://wordpress.org/plugins/wordpress-importer/'
				)
			),
			'import-widgets' => array(
				'title' => __( 'Import Widgets', 'jobify' ),
				'completed' => is_active_sidebar( 'widget-area-front-page' ),
				'documentation' => array(
					'Widget Areas' => 'http://listify.astoundify.com/category/352-widget-areas',
					'Widgets' => 'http://listify.astoundify.com/category/199-widgets' 
				)
			),
			'setup-menus' => array(
				'title' => __( 'Setup Menus', 'jobify' ),
				'completed' => isset( $menus[ 'primary' ] ),
				'documentation' => array(
					'Primary Menu' => 'http://listify.astoundify.com/article/250-manage-the-primary-menu',
					'Add a Dropdown' => 'http://listify.astoundify.com/article/252-add-a-dropdown-menu',
					'Show/Hide Items' => 'http://listify.astoundify.com/article/256-show-different-menus-for-logged-in-or-logged-out',
				)
			),
			'setup-homepage' => array(
				'title' => __( 'Setup Static Homepage', 'jobify' ),
				'completed' => (bool) get_option( 'page_on_front', false ),
				'documentation' => array(
					'Create Your Homepage' => 'http://listify.astoundify.com/article/261-creating-your-homepage',
					'Reading Settings (codex)' => 'http://codex.wordpress.org/Settings_Reading_Screen'
				)
			),
			'setup-widgets' => array(
				'title' => __( 'Setup Widgets', 'jobify' ),
				'completed' => is_active_sidebar( 'widget-area-front-page' ),
				'documentation' => array(
					'Widget Areas' => 'http://listify.astoundify.com/category/352-widget-areas',
					'Widgets' => 'http://listify.astoundify.com/category/199-widgets' 
				)
			),
			'customize-theme' => array(
				'title' => __( 'Customize', 'jobify' ),
				'completed' => get_option( 'theme_mods_listify' ),
				'documentation' => array(
					'Appearance' => 'http://listify.astoundify.com/category/334-appearance',
					'Child Themes' => 'http://listify.astoundify.com/category/209-child-themes',
					'Translations' => 'http://listify.astoundify.com/category/210-translations'
				)
			),
			'support-us' => array(
				'title' => __( 'Get Involved', 'jobify' ),
				'completed' => 'na',
				'documentation' => array(
					'Leave a Positive Review' => 'http://bit.ly/rate-jobify',
					'Contribute Your Translation' => 'http://bit.ly/translate-jobify'
				)
			)
		);

		add_action( 'admin_menu', array( $this, 'add_page' ), 100 );
		add_action( 'admin_menu', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
    }

    public function add_page() {
		add_submenu_page( 'themes.php', __( 'Marketify Setup', 'jobify' ), __( 'Setup Guide', 'marketify' ), 'manage_options', 'marketify-setup', array( $this, 'output' ) );
    }

    public function admin_css() {
		$screen = get_current_screen();

		if ( 'appearance_page_marketify-setup' != $screen->id ) {
			return;
		}

		wp_enqueue_style( 'marketify-setup', get_template_directory_uri() . '/inc/setup/style.css' );
    }

    public function add_meta_boxes() {
		foreach ( $this->steps as $step => $info ) {
			$info = array_merge( array( 'step' => $step ), $info );
			add_meta_box( $step , $info[ 'title' ], array( $this, 'step_box' ), 'marketify_setup_steps', 'normal', 'high', $info );
		}
    }

    public function step_box( $object, $metabox ) {
		$args = $metabox[ 'args' ];
    ?>
		<?php if ( $args[ 'completed' ] === true ) { ?>
			<div class="is-completed"><?php _e( 'Completed!', 'jobify' ); ?></div>
		<?php } elseif ( $args[ 'completed' ] === false || $args[ 'completed' ] == '' ) { ?>
			<div class="not-completed"><?php _e( 'Incomplete', 'jobify' ); ?></div>
		<?php } ?>

		<?php include ( get_template_directory() . '/inc/setup/steps/' . $args[ 'step' ] . '.php' ); ?>

		<?php if ( 'Get Involved' != $args[ 'title' ] ) : ?> 
			<hr />
			<p><?php _e( 'You can read more and watch helpful video tutorials below:', 'jobify' ); ?></p>
		<?php endif; ?>

		<p>
			<?php foreach ( $args[ 'documentation' ] as $title => $url ) { ?>
			<a href="<?php echo esc_url( $url ); ?>" class="button button-secondary"><?php echo esc_attr( $title ); ?></a>&nbsp;
			<?php } ?>
		</p>
    <?php
    }

    public function output() {
    ?>
		<div class="wrap about-wrap jobify-setup">
			<?php $this->welcome(); ?>
			<?php $this->links(); ?>
		</div>

		<div id="poststuff" class="wrap jobify-steps" style="margin: 25px 40px 0 20px">
			<?php $this->steps(); ?>
		</div>
    <?php  
    }

    public function welcome() {
    ?>
		<h1><?php printf( __( 'Welcome to %s %s', 'jobify' ), $this->theme->Name, $this->theme->Version ); ?></h1>
		<p class="about-text"><?php printf( __( 'Creating a job listing website has never been easier with Jobify — the easiest to use job board theme available. Use the steps below to finish setting up your new website. If you have more questions please <a href="%s">review the documentation</a>.', 'jobify' ), 'http://jobify.astoundify.com' ); ?></p>
		<div class="jobify-badge"><img src="<?php echo get_template_directory_uri(); ?>/inc/setup/images/banner.jpg" width="140" alt="" /></div>
    <?php
    }

    public function links() {
    ?>
		<p class="helpful-links">
			<a href="http://marketify.astoundify.com" class="button button-primary"><?php _e( 'Documentation', 'jobify' ); ?></a>&nbsp;
			<a href="http://support.astoundify.com" class="button button-secondary"><?php _e( 'Submit a Support Ticket', 'jobify' ); ?></a>&nbsp;
		</p>
    <?php
    }

    public function steps() {
		do_accordion_sections( 'marketify_setup_steps', 'normal', null );
    }
}
