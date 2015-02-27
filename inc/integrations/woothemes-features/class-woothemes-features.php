<?php

class Marketify_WooThemes_Features extends Marketify_Integration {

	public function __construct() {
		parent::__construct( dirname( __FILE__ ) );
	}


	public function setup_actions() {
		add_filter( 'woothemes_features_default_args', array( $this, 'default_args' ) );
		add_action( 'dynamic_sidebar', array( $this, 'choose_template' ) );
		add_filter( 'woothemes_features_html', array( $this, 'add_columns' ) );
	}

	public function default_args( $args ) {
		$args[ 'link_title' ] = false;

		return $args;
	}

	public function choose_template( $widget ) {
		if ( 'widget_woothemes_features' != $widget[ 'classname' ] ) {
			return $widget;
		}

		$options = get_option( $widget[ 'classname' ] );
		$options = $options[ $widget[ 'params' ][0][ 'number' ] ];

		if ( 25 == $options[ 'size' ] ) {
			add_filter( 'woothemes_features_item_template', array( $this, 'template_mini' ), 10, 2 );
		} else {
			add_filter( 'woothemes_features_item_template', array( $this, 'template_standard' ), 10, 2 );
		}
	}

	public function template_standard( $template, $args ) {
		return '<div class="%%CLASS%% feature-large">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
	}

	public function template_mini( $template, $args ) {
		return '<div class="%%CLASS%% feature-mini">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
	}

	public function add_columns( $html ) {
		$html = str_replace( '<div class="features', '<div data-columns class="features', $html );

		return $html;
	}

}
