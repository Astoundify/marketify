<?php
/**
 * Footer: Contact Us
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) || ! $wp_customize instanceof WP_Customize_Manager ) {
	exit; // Exit if accessed directly.
}

$wp_customize->add_section( 'footer-contact-us', array(
	'title' => _x( 'Contact Us', 'customizer section title (colors)', 'marketify' ),
	'panel' => 'footer',
	'priority' => 10
) );
