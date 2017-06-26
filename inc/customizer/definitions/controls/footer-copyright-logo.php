<?php
/**
 * Footer: Copyright Logo
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'footer-copyright-logo', array(
	'default' => false,
	'sanitize_callback' => 'esc_url'
) );

$wp_customize->add_control( new WP_Customize_Image_Control( 
	$wp_customize,
	'footer-copyright-logo', 
	array(
		'label'   => __( 'Logo', 'marketify' ),
		'section' => 'footer-copyright',
		'priority' => 20
	) 
) );
