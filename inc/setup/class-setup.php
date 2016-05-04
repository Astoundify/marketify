<?php
/**
 * Setup Marketify.
 *
 * @see https://github.com/astoundify/setup-guide
 * @see https://github.com/astoundify/content-importer
 * @see https://github.com/astoundify/themeforest-updater
 */
class Marketify_Setup {

	/**
	 * Start things up.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function init() {
		if ( ! is_admin() ) {
			return;
		}

		// UCT self inits so we need to be early
		self::child_theme();

		self::includes();

		self::theme_updater();
		self::content_importer();
		self::setup_guide();
	}

	public static function includes() {
		include_once( dirname( __FILE__ ) . '/_setup-guide/class-astoundify-setup-guide.php' );
		include_once( dirname( __FILE__ ) . '/_importer/class-astoundify-content-importer.php' );
		include_once( dirname( __FILE__ ) . '/_updater/class-astoundify-themeforest-updater.php' );
		include_once( dirname( __FILE__ ) . '/_child_theme/use-child-theme.php' );
	}

	/**
	 * Create a child theme.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function child_theme() {
		add_filter( 'uct_functions_php', array( __CLASS__, 'uct_functions_php' ) );
		add_filter( 'uct_admin_notices_screen_id', array( __CLASS__, 'uct_admin_notices_screen_id' ) );
	}

	/**
	 * Filter the child theme's functions.php contents.
	 *
	 * @since 2.7.0
	 *
	 * @param string $output
	 * @return string $output
	 */
	public static function uct_functions_php( $output ) {
		$output = str_replace( "'child_enqueue_styles' );", "'child_enqueue_styles', 210 );", $output );

		return $output;
	}

	/**
	 * Filter the child theme's notice output
	 *
	 * @since 2.7.0
	 *
	 * @param array $screen_ids
	 * @return array $screen_ids
	 */
	public static function uct_admin_notices_screen_id( $screen_ids ) {
		return array( 'appearance_page_marketify-setup' );
	}

	/**
	 * Create the setup guide.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function setup_guide() {
		add_action( 'astoundify_setup_guide_intro', array( __CLASS__, '_setup_guide_intro' ) );

		$steps = include_once( dirname( __FILE__ ) . '/setup-guide-steps/_step-list.php' );

		Astoundify_Setup_Guide::init( array(
			'steps' => $steps,
			'steps_dir' => get_template_directory() . '/inc/setup/setup-guide-steps',
			'strings' => array(
				'page-title' => __( 'Setup %s', 'marketify' ),
				'menu-title' => __( 'Setup Guide', 'marketify' ),
				'intro-title' => __( 'Welcome to %s', 'marketify' ),
				'step-complete' => __( 'Completed', 'marketify' ),
				'step-incomplete' => __( 'Not Complete', 'marketify' )
			),
			'stylesheet_uri' => get_template_directory_uri() . '/inc/setup/_setup-guide/style.css',
		) );
	}

	/**
	 * The introduction text for the setup guide page.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function _setup_guide_intro() {
?>
<p class="about-text"><?php printf( __( 'Creating a digital marketplace has never been easier with Marketify&mdash;Use the steps below to start setting up your new website. If you have more questions please <a href="%s">review the documentation</a>.', 'marketify' ), 'http://marketify.astoundify.com' ); ?></p>

<div class="setup-guide-theme-badge"><img src="<?php echo get_template_directory_uri(); ?>/inc/setup/images/banner.jpg" width="140" alt="" /></div>

 <p class="helpful-links">
	<a href="http://marketify.astoundify.com" class="button button-primary js-trigger-documentation"><?php _e( 'Search Documentation', 'marketify' ); ?></a>&nbsp;
	<a href="https://astoundify.com/go/astoundify-support/" class="button button-secondary"><?php _e( 'Submit a Support Ticket', 'marketify' ); ?></a>&nbsp;
</p>
<script>
	jQuery(document).ready(function($) {
		$('.js-trigger-documentation').click(function(e) {
			e.preventDefault();
			HS.beacon.open();
		});
	});
</script>
<script>!function(e,o,n){window.HSCW=o,window.HS=n,n.beacon=n.beacon||{};var t=n.beacon;t.userConfig={},t.readyQueue=[],t.config=function(e){this.userConfig=e},t.ready=function(e){this.readyQueue.push(e)},o.config={modal: true, docs:{enabled:!0,baseUrl:"//astoundify-marketify.helpscoutdocs.com/"},contact:{enabled:!1,formId:"b68bfa79-83ce-11e5-8846-0e599dc12a51"}};var r=e.getElementsByTagName("script")[0],c=e.createElement("script");c.type="text/javascript",c.async=!0,c.src="https://djtflbt20bdde.cloudfront.net/",r.parentNode.insertBefore(c,r)}(document,window.HSCW||{},window.HS||{});</script>
<?php
	}

	/**
	 * Create the theme updater.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function theme_updater() {
		// start the updater
		$updater = Astoundify_ThemeForest_Updater::instance();
		$updater::set_strings( array(
			'cheating' => __( 'Cheating?', 'marketify' ),
			'no-token' => __( 'An API token is required.', 'marketify' ),
			'api-error' => __( 'API error.', 'marketify' ),
			'api-connected' => __( 'Connected', 'marketify' ),
			'api-disconnected' => __( 'Disconnected', 'marketify' )
		) );

		// set a filter for the token
		add_filter( 'astoundify_themeforest_updater', array( __CLASS__, '_theme_updater_get_token' ) );

		// init the api so it has a token value
		Astoundify_Envato_Market_API::instance();

		// ajax callback
		add_action( 'wp_ajax_marketify_set_token', array( __CLASS__, '_theme_updater_set_token' ) );
	}

	/**
	 * Filter the Theme Updater token.
	 *
	 * @since 2.7.0
	 *
	 * @return string
	 */
	public static function _theme_updater_get_token() {
		return get_option( 'marketify_themeforest_updater_token', null );
	}

	/**
	 * AJAX response when a token is set in the Setup Guide.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function _theme_updater_set_token() {
		check_ajax_referer( 'marketify-add-token', 'security' );

		$token = isset( $_POST[ 'token' ] ) ? esc_attr( $_POST[ 'token' ] ) : false;

		if ( ! $token ) {
			wp_send_json_error();
		}

		$api = Astoundify_Envato_Market_API::instance();

		update_option( 'marketify_themeforest_updater_token', $token );
		delete_transient( 'atu_can_make_request' );

		// hotswap the token
		$api->token = $token;

		wp_send_json_success( array(
			'token' => $token,
			'can_request' => $api->can_make_request_with_token(),
			'request_label' => $api->connection_status_label()
		) );

		exit();
	}

	/**
	 * Create the content importer.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function content_importer() {
		Astoundify_Content_Importer::instance();

		// ajax callback
		add_action( 'wp_ajax_marketify_oneclick_setup', array( __CLASS__, '_content_importer_import' ) );
	}
	
	/**
	 * AJAX response when content is imported in the setup guide.
	 *
	 * @since 2.7.0
	 *
	 * @return void
	 */
	public static function _content_importer_import() {
		check_ajax_referer( 'marketify-oneclick-setup', 'security' );

		// the style to use
		$style = isset( $_POST[ 'style' ] ) ? $_POST[ 'style' ] : false;

		// the files to use
		$files = isset( $_POST[ 'files' ] ) ? $_POST[ 'files' ] : false;

		// the import key
		$import_key = isset( $_POST[ 'import_key' ] ) ? esc_attr( $_POST[ 'import_key' ] ) : false;

		// what are we doing
		$process_action = isset( $_POST[ 'process_action' ] ) ? esc_attr( $_POST[ 'process_action' ] ) : false;

		if ( ! $files || ! $import_key || ! $process_action || ! $style ) {
			wp_send_json_error();
		}

		// update directory path
		foreach ( $files as $key => $file ) {
			$files[ $key ] = str_replace( '{style}', $style, $file );
		}

		Astoundify_Importer_Manager::enqueue( $files );
		$process = Astoundify_Importer_Manager::process( $process_action, $import_key );

		return $process ? wp_send_json_success() : wp_send_json_error();

		exit();
	}
	
}

Marketify_Setup::init();
