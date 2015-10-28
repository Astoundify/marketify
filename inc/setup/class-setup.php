<?php
class Marketify_Setup {
    public function __construct() {
        if ( ! is_admin() ) {
            return;
        }
        add_action( 'after_setup_theme', array( $this, 'init' ), 0 );
    }
    public function init() {
        $menus = get_theme_mod( 'nav_menu_locations' );
        $this->theme = marketify()->activation->theme;
        $has_downloads = new WP_Query( array( 'post_type' => 'downloads', 'fields' => 'ids', 'posts_per_page' => 1 ) );
        $this->steps = array(
            'install-plugins' => array(
                'title' => __( 'Install Required &amp; Recommended Plugins', 'marketify' ),
                'completed' => class_exists( 'Easy_Digital_Downloads' ),
                'documentation' => array(
                    'Easy Digital Downloads' => 'http://marketify.astoundify.com/article/713-easy-digital-downloads',
                    'Easy Digital Downloads - Marketplace Bundle' => 'https://astoundify.com/go/marketplace-bundle/',
                    'Bulk Install' => 'http://marketify.astoundify.com/article/712-bulk-install-required-and-recommended-plugins-recommended'
                )
            ),
            'import-content' => array(
                'title' => __( 'Import Demo Content', 'marketify' ),
                'completed' => $has_downloads->have_posts(),
                'documentation' => array(
                    'Install Demo Content' => 'http://marketify.astoundify.com/article/789-installing-demo-content',
                    'Importing Content (Codex)' => 'http://codex.wordpress.org/Importing_Content',
                    'WordPress Importer' => 'https://wordpress.org/plugins/wordpress-importer/'
                )
            ),
            'import-widgets' => array(
                'title' => __( 'Import Widgets', 'marketify' ),
                'completed' => is_active_sidebar( 'home-1' ),
                'documentation' => array(
                    'Widget Areas' => 'http://marketify.astoundify.com/category/692-widget-areas',
                    'Widgets' => 'http://marketify.astoundify.com/category/585-widgets' 
                )
            ),
            'setup-menus' => array(
                'title' => __( 'Setup Menus', 'marketify' ),
                'completed' => isset( $menus[ 'primary' ] ),
                'documentation' => array(
                    'Primary Menu' => 'http://marketify.astoundify.com/article/700-manage-the-primary-menu',
                    'Show/Hide Items' => 'http://marketify.astoundify.com/article/702-show-hide-links-depending-on-the-user',
                )
            ),
            'setup-homepage' => array(
                'title' => __( 'Setup Static Homepage', 'marketify' ),
                'completed' => (bool) get_option( 'page_on_front', false ),
                'documentation' => array(
                    'Create Your Homepage' => 'http://marketify.astoundify.com/article/581-creating-your-homepage',
                    'Reading Settings (codex)' => 'http://codex.wordpress.org/Settings_Reading_Screen'
                )
            ),
            'setup-widgets' => array(
                'title' => __( 'Setup Widgets', 'marketify' ),
                'completed' => is_active_sidebar( 'widget-area-front-page' ),
                'documentation' => array(
                    'Widget Areas' => 'http://marketify.astoundify.com/category/692-widget-areas',
                    'Widgets' => 'http://marketify.astoundify.com/category/585-widgets' 
                )
            ),
            'customize-theme' => array(
                'title' => __( 'Customize', 'marketify' ),
                'completed' => get_option( 'theme_mods_marketify' ),
                'documentation' => array(
                    'Appearance' => 'http://marketify.astoundify.com/collection/463-customization',
                    'Child Themes' => 'http://marketify.astoundify.com/category/719-child-themes',
                    'Translations' => 'http://marketify.astoundify.com/category/720-translations'
                )
            ),
            'support-us' => array(
                'title' => __( 'Get Involved', 'marketify' ),
                'completed' => 'na',
                'documentation' => array(
                    'Leave a Positive Review' => 'https://astoundify.com/go/rate-theme/',
                    'Contribute Your Translation' => 'https://astoundify.com/go/translate-marketify/'
                )
            )
        );
        add_action( 'admin_menu', array( $this, 'add_page' ), 100 );
        add_action( 'admin_menu', array( $this, 'add_meta_boxes' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
    }
    public function add_page() {
        add_submenu_page( 'themes.php', __( 'Marketify Setup', 'marketify' ), __( 'Setup Guide', 'marketify' ), 'manage_options', 'marketify-setup', array( $this, 'output' ) );
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
            <div class="is-completed"><?php _e( 'Completed!', 'marketify' ); ?></div>
        <?php } elseif ( $args[ 'completed' ] === false || $args[ 'completed' ] == '' ) { ?>
            <div class="not-completed"><?php _e( 'Incomplete', 'marketify' ); ?></div>
        <?php } ?>

        <?php include ( get_template_directory() . '/inc/setup/steps/' . $args[ 'step' ] . '.php' ); ?>

        <?php if ( 'Get Involved' != $args[ 'title' ] ) : ?> 
            <hr />
            <p><?php _e( 'You can read more and watch helpful video tutorials below:', 'marketify' ); ?></p>
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
        <div class="wrap about-wrap marketify-setup">
            <?php $this->welcome(); ?>
            <?php $this->links(); ?>
        </div>

        <div id="poststuff" class="wrap marketify-steps" style="margin: 25px 40px 0 20px">
            <?php $this->steps(); ?>
        </div>
    <?php  
    }
    public function welcome() {
    ?>
        <h1><?php printf( __( 'Welcome to %s %s', 'marketify' ), $this->theme->Name, $this->theme->Version ); ?></h1>
        <p class="about-text"><?php printf( __( 'Creating a digital marketplace has never been easier with Marketify&mdash;Use the steps below to start setting up your new website. If you have more questions please <a href="%s">review the documentation</a>.', 'marketify' ), 'http://marketify.astoundify.com' ); ?></p>
        <div class="marketify-badge"><img src="<?php echo get_template_directory_uri(); ?>/inc/setup/images/banner.jpg" width="140" alt="" /></div>
    <?php
    }
    public function links() {
    ?>
        <p class="helpful-links">
            <a href="http://marketify.astoundify.com" class="button button-primary"><?php _e( 'Documentation', 'marketify' ); ?></a>&nbsp;
            <a href="http://support.astoundify.com" class="button button-secondary"><?php _e( 'Submit a Support Ticket', 'marketify' ); ?></a>&nbsp;
        </p>
    <?php
    }
    public function steps() {
        do_accordion_sections( 'marketify_setup_steps', 'normal', null );
    }
}