<?php
/**
 * Footer: Contact Us Title
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_setting( 'footer-contact-us-title', array(
	'default' => 'Contact Us',
	'sanitize_callback' => 'esc_attr'
) );

$wp_customize->add_control( 'footer-contact-us-title', array(
	'label'   => __( 'Title', 'marketify' ),
	'section' => 'footer-contact-us',
	'priority' => 20
) );
