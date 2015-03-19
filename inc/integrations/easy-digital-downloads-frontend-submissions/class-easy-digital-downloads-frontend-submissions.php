<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions extends Marketify_Integration {

	public function __construct() {
		$this->includes = array(
			'class-easy-digital-downloads-frontend-submissions-vendor-widget.php',
			'class-easy-digital-downloads-frontend-submissions-vendors.php',
			'class-easy-digital-downloads-frontend-submissions-vendor.php',
			'class-easy-digital-downloads-frontend-submissions-widgets.php',

			'widgets/class-widget-vendor.php',
			'widgets/class-widget-vendor-description.php',
			'widgets/class-widget-product-details.php'
		);

		parent::__construct( dirname( __FILE__ ) );
	}

	public function init() {
		$this->vendors = new Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendors();
		$this->widgets = new Marketify_Easy_Digital_Downloads_Frontend_Submissions_Widgets();
	}

	public function setup_actions() {
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_styles' ), 11 );
		add_action( 'wp_head', array( $this, 'recaptcha_style' ) );
	}

	public function vendor( $author = false ) {
		return new Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendor( $author );
	}

	function recaptcha_style() {
		if ( ! EDD_FES()->helper->get_option( 'fes-recaptcha-public-key' ) ) {
			return;
		}
	?>
		<script type="text/javascript">
			var RecaptchaOptions = {
				theme : 'clean',
			};
		</script>
	<?php
	}

	function dequeue_styles() {
		wp_dequeue_style( 'fes-css' );
	}


}
