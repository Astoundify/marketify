<?php
/**
 * Features by WooThemes
 *
 * @package Marketify
 */

/**
 * Depending on the settings of the features widgets, apply a filter to
 * the output.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_woothemes_features_item( $widget ) {
	if ( 'widget_woothemes_features' != $widget[ 'classname' ] )
		return $widget;

	$options = get_option( $widget[ 'classname' ] );
	$options = $options[ $widget[ 'params' ][0][ 'number' ] ];

	if ( 25 == $options[ 'size' ] )
		add_filter( 'woothemes_features_item_template', 'marketify_woothemes_features_item_template_mini', 10, 2 );
	else
		add_filter( 'woothemes_features_item_template', 'marketify_woothemes_features_item_template', 10, 2 );
}
add_action( 'dynamic_sidebar', 'marketify_woothemes_features_item' );

/**
 * Standard Features
 *
 * @since Marketify 1.0
 *
 * @return string
 */
function marketify_woothemes_features_item_template( $template, $args ) {
	return '<div class="%%CLASS%% col-lg-4 col-sm-6 col-xs-12 feature-large">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
}

/**
 * Mini Features
 *
 * @since Marketify 1.0
 *
 * @return string
 */
function marketify_woothemes_features_item_template_mini( $template, $args ) {
	return '<div class="%%CLASS%% col-lg-3 col-md-4 col-sm-6 col-xs-12 feature-mini">%%IMAGE%%<h3 class="feature-title">%%TITLE%%</h3><div class="feature-content">%%CONTENT%%</div></div>';
}