<?php

class Marketify_Easy_Digital_Downloads extends Marketify_Integration {

	public function __construct() {
		$this->includes = array(
			'class-easy-digital-downloads-template.php',
			'class-easy-digital-downloads-widgets.php',
			'class-easy-digital-downloads-navigation.php',
			'class-easy-digital-downloads-shortcode.php',
			'class-easy-digital-downloads-purchase-form.php',
			'class-easy-digital-downloads-query.php',
			'class-easy-digital-downloads-metaboxes.php',

			'widgets/class-widget-downloads-curated.php',
			'widgets/class-widget-downloads-recent.php',
			'widgets/class-widget-downloads-taxonomy.php',
			'widgets/class-widget-downloads-taxonomy-stylized.php',
			'widgets/class-widget-downloads-featured-popular.php',

			'widgets/class-widget-download-archive-sorting.php',

			'widgets/class-widget-download-details.php',
			'widgets/class-widget-download-share.php'
		);

		parent::__construct( dirname( __FILE__) );
	}

	public function init_props() {
		$this->template = new Marketify_Easy_Digital_Downloads_Template();
		$this->query = new Marketify_Easy_Digital_Downloads_Query();
		$this->widgets = new Marketify_Easy_Digital_Downloads_Widgets();
		$this->navigation = new Marketify_Easy_Digital_Downloads_Navigation();
		$this->shortcode = new Marketify_Easy_Digital_Downloads_Navigation();
		$this->purchase_form = new Marketify_Easy_Digital_Downloads_Purchase_Form();
		$this->metaboxes = new Marketify_Easy_Digital_Downloads_Metaboxes();
	}

	public function setup_actions() {
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );
		add_filter( 'edd_default_downloads_name', array( marketify()->strings, 'get_labels' ) );
	}

	public function add_theme_support() {
		add_theme_support( 'post-formats', array( 'audio', 'video' ) );
		add_post_type_support( 'download', 'post-formats' );

		add_post_type_support( 'download', 'comments' );
	}

	public function theme_support() {
		print_r( marketify() );
	}

}

