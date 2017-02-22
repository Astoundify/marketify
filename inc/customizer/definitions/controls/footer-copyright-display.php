<?php
/**
 * Footer: Copyright Display
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'footer-copyright-display', array(
	'default' => 'on',
	'sanitize_callback' => 'esc_attr'
) );

$wp_customize->add_control( 'footer-copyright-display', array(
	'label' => __( 'Enable', 'marketify' ),
	'type' => 'checkbox',
	'section' => 'footer-copyright',
	'priority' => 10
) );
