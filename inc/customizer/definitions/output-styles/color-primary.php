<?php
/**
 * Output "Primary Color" CSS.
 *
 * @since 2.11.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$primary = marketify_theme_color( 'color-primary' );

astoundify_themecustomizer_add_css( array(
	'selectors' => array(
		'.featured-popular-switcher span:hover'
	),
	'declarations' => array(
		'border-color' => esc_attr( $primary ),
		'color' => esc_attr( $primary )
	)
) );

astoundify_themecustomizer_add_css( array(
	'selectors' => array(
		'button',
		'input[type=reset]',
		'input[type=submit]',
		'input[type=radio]:checked',
		'.button',

		// edd
		'#edd-purchase-button',
		'.edd-submit',
		'.edd-submit.button',
		'.edd-submit.button:visited',
		'input[type=submit].edd-submit',
		'.current-cart .cart_item.edd_checkout a',

		// edd wish lists
		'.edd-wl-button',
		'a.edd-wl-button',
		'.edd-wl-button.edd-wl-action',
		'a.edd-wl-button.edd-wl-action'
	),
	'declarations' => array(
		'color' => esc_attr( $primary ),
		'border-color' => esc_attr( $primary ),
		'background' => '#ffffff'
	)
) );

astoundify_themecustomizer_add_css( array(
	'selectors' => array(
		'button:hover',
		'input[type=reset]:hover',
		'input[type=submit]:hover',
		'.button:hover',

		// edd
		'#edd-purchase-button:hover',
		'.edd-submit:hover',
		'.edd-submit.button:hover',
		'input[type=submit].edd-submit:hover',
		'.current-cart .cart_item.edd_checkout a:hover',

		// edd wish lists
		'.edd-wl-button:hover',
		'a.edd-wl-button:hover',
		'.edd-wl-button.edd-wl-action:hover',
		'a.edd-wl-button.edd-wl-action:hover',
	),
	'declarations' => array(
		'color' => '#ffffff',
		'background-color' => esc_attr( $primary ),
		'border-color' => esc_attr( $primary )
	)
) );


astoundify_themecustomizer_add_css( array(
	'selectors' => array(
		'.button.button--color-white:hover',

		// edd
		'.page-header .edd-submit.button.edd-add-to-cart:hover',
		'.page-header .edd-submit.button.edd_go_to_checkout:hover',
		'.page-header a.edd-submit.button:hover',
		'.content-grid-download__actions .button:hover',
		'.content-grid-download__actions .edd-submit:hover',
		'.content-grid-download__actions a.button.edd-submit:hover',
		'.content-grid-download__actions .edd-submit.button.edd-add-to-cart:hover',
		'.content-grid-download__actions .edd-submit.button.edd_go_to_checkout:hover',

		// soliloquy
		'body .marketify_widget_slider_hero .soliloquy-container .soliloquy-caption-outer .button:hover',

		// feature callout
		'.feature-callout-cover .button:hover'
	),
	'declarations' => array(
		'color' => esc_attr( $primary ),
		'background-color' => '#ffffff',
		'border-color' => '#ffffff'
	)
) );
