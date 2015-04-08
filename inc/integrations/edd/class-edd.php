<?php

class Marketify_EDD extends Marketify_Integration {

	public function __construct() {
		$this->includes = array(
			'class-edd-template.php',
			'class-edd-template-purchase-form.php',
			'class-edd-template-navigation.php',
			'class-edd-template-download.php',
			'class-edd-widgets.php',
			'class-edd-sorting.php',
			'class-edd-shortcode.php',
			'class-edd-query.php',
			'class-edd-metaboxes.php',

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

	public function init() {
		$this->template = new Marketify_EDD_Template();
		$this->sorting = new Marketify_EDD_Sorting();
		$this->query = new Marketify_EDD_Query();
		$this->widgets = new Marketify_EDD_Widgets();
		$this->shortcode = new Marketify_EDD_Shortcode();
		$this->metaboxes = new Marketify_EDD_Metaboxes();
	}

	public function setup_actions() {
		add_action( 'after_setup_theme', array( $this, 'theme_support' ) );
		add_filter( 'edd_default_downloads_name', array( marketify()->strings, 'get_labels' ) );
	}

	public function theme_support() {
		add_theme_support( 'post-formats', array( 'audio', 'video' ) );
		add_post_type_support( 'download', 'post-formats' );

		add_post_type_support( 'download', 'comments' );
	}

}

