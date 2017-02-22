<?php
/**
 * Download: Singular Label
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'download-label-singular', array(
	'default' => 'Download'
) );

$wp_customize->add_control( 'download-label-singular', array(
	'label'   => __( 'Singular Label', 'marketify' ),
	'section' => 'download-label',
	'priority' => 10
) );
