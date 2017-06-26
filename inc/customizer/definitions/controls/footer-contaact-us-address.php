<?php
/**
 * Footer: Contact Us Address
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'footer-contact-us-address', array(
	'default' => '393 Bay Street, 2nd Floor Toronto, Ontario, Canada, L9T8S2',
	'sanitize_callback' => 'wp_kses_post'
) );

$wp_customize->add_control( 'footer-contact-us-address', array(
	'label'   => __( 'Text', 'marketify' ),
	'type' => 'textarea',
	'section' => 'footer-contact-us',
	'priority' => 30
) );
