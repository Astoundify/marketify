<?php
/**
 * Footer: Copyright Text
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'footer-copyright-text', array(
	'default' => sprintf( '&copy; %1$s %2$s &mdash; All Rights Reserved', date( 'Y' ), get_bloginfo( 'name' ) )
) );

$wp_customize->add_control( 'footer-copyright-text', array(
	'label'   => __( 'Text', 'marketify' ),
	'section' => 'footer-copyright',
	'priority' => 30
) );
