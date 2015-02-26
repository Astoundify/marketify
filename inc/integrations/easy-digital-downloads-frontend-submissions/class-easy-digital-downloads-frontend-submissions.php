<?php

class Marketify_Easy_Digital_Downloads_Frontend_Submissions extends Marketify_Integration {

	public function __construct() {
		$this->files = array(
			'class-easy-digital-downloads-frontend-submissions-vendors.php'
		);

		parent::__construct( dirname( __FILE__ ) );
	}

	public function init() {
		$this->vendors = new Marketify_Easy_Digital_Downloads_Frontend_Submissions_Vendors();
	}

	public function setup_actions() {
		add_action( 'wp_enqueue_scripts', 'dequeue_styles', 11 );
		add_action( 'wp_head', 'recaptcha_style' );
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
