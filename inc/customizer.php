<?php
/**
 * Customize
 *
 * Theme options are lame! Manage any customizations through the Theme
 * Customizer. Expose the customizer in the Appearance panel for easy access.
 *
 * @package Marketify
 * @since Marketify 1.0
 */

/**
 * Get Theme Mod
 *
 * Instead of options, customizations are stored/accessed via Theme Mods
 * (which are still technically settings). This wrapper provides a way to
 * check for an existing mod, or load a default in its place.
 *
 * @since Marketify 1.0
 *
 * @param string $key The key of the theme mod to check. Prefixed with 'marketify_'
 * @return mixed The theme modification setting
 */
function marketify_theme_mod( $section, $key, $_default = false ) {
	$mods = marketify_get_theme_mods();

	$default = $mods[ $section ][ $key ][ 'default' ];

	if ( $_default )
		$mod = $default;
	else
		$mod = get_theme_mod( $key, $default );

	return apply_filters( 'marketify_theme_mod_' . $key, $mod );
}

/** 
 * Register two new sections: General, and Social.
 *
 * @since Marketify 1.0
 *
 * @param object $wp_customize
 * @return void
 */
function marketify_customize_register_sections( $wp_customize ) {
	$wp_customize->add_section( 'general', array(
		'title'      => _x( 'General', 'Theme customizer section title', 'marketify' ),
		'priority'   => 10,
	) );

	$wp_customize->add_section( 'footer', array(
		'title'      => _x( 'Footer', 'Theme customizer section title', 'marketify' ),
		'priority'   => 100,
	) );
}
add_action( 'customize_register', 'marketify_customize_register_sections' );

/**
 * Default theme customizations.
 *
 * @since Marketify 1.0
 *
 * @return $options an array of default theme options
 */
function marketify_get_theme_mods( $args = array() ) {
	$defaults = array(
		'keys_only' => false
	);

	$args = wp_parse_args( $args, $defaults );

	$mods = array(
		'footer' => array(
			'footer-style' => array(
				'title'   => __( 'Style', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					'dark'  => __( 'Dark', 'marketify' ),
					'light' => __( 'Light', 'marketify' )
				),
				'default' => 'dark'
			),
			'footer-contact-address' => array(
				'title'   => __( 'Contact Address', 'marketify' ),
				'type'    => 'Marketify_Customize_Textarea_Control',
				'default' => "393 Bay Street, 2nd Floor\nToronto, Ontario, Canada, L9T8S2"
			),
			'footer-logo' => array(
				'title'   => __( 'Logo', 'marketify' ),
				'type'    => 'WP_Customize_Image_Control',
				'default' => 0
			)
		),
		'colors' => array(
			'header'  => array(
				'title'   => __( 'Page Header Color', 'marketify' ),
				'type'    => 'WP_Customize_Color_Control',
				'default' => '#515a63'
			),
			'primary' => array(
				'title'   => __( 'Button/Primary Color', 'marketify' ),
				'type'    => 'WP_Customize_Color_Control',
				'default' => '#515a63'
			),
			'accent' => array(
				'title'   => __( 'Accent Color', 'marketify' ),
				'type'    => 'WP_Customize_Color_Control',
				'default' => '#4ed0aa'
			)
		),
	);

	$mods = apply_filters( 'marketify_theme_mods', $mods );

	/** Return all keys within all sections (for transport, etc) */
	if ( $args[ 'keys_only' ] ) {
		$keys = array();
		$final = array();

		foreach ( $mods as $section ) {
			$keys = array_merge( $keys, array_keys( $section ) );
		}

		foreach ( $keys as $key ) {
			$final[ $key ] = '';
		}

		return $final;
	}

	return $mods;
}

/**
 * Register settings.
 *
 * Take the final list of theme mods, and register all the settings, 
 * and add all of the proper controls.
 *
 * If the type is one of the default supported ones, add it normally. Otherwise
 * Use the type to create a new instance of that control type.
 *
 * @since Marketify 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function marketify_customize_register_settings( $wp_customize ) {
	$mods = marketify_get_theme_mods();

	foreach ( $mods as $section => $settings ) {
		foreach ( $settings as $key => $setting ) {
			$wp_customize->add_setting( $key, array(
				'default'    => marketify_theme_mod( $section, $key, true ),
			) );

			$type = $setting[ 'type' ];

			if ( in_array( $type, array( 'text', 'checkbox', 'radio', 'select', 'dropdown-pages' ) ) ) {
				$wp_customize->add_control( $key, array(
					'label'      => $setting[ 'title' ],
					'section'    => $section,
					'settings'   => $key,
					'type'       => $type,
					'choices'    => isset ( $setting[ 'choices' ] ) ? $setting[ 'choices' ] : null,
					'priority'   => isset ( $setting[ 'priority' ] ) ? $setting[ 'priority' ] : null
				) );
			} else {
				$wp_customize->add_control( new $type( $wp_customize, $key, array(
					'label'      => $setting[ 'title' ],
					'section'    => $section,
					'settings'   => $key,
					'priority'   => isset ( $setting[ 'priority' ] ) ? $setting[ 'priority' ] : null
				) ) );
			}
		}
	}

	do_action( 'marketify_customize_regiser_settings', $wp_customize );

	return $wp_customize;
}
add_action( 'customize_register', 'marketify_customize_register_settings' );

/**
 * Add postMessage support for all default fields, as well
 * as the site title and desceription for the Theme Customizer.
 *
 * @since Marketify 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function marketify_customize_register_transport( $wp_customize ) {
	$built_in = array( 'blogname' => '', 'blogdescription' => '', 'header_textcolor' => '' );
	$marketify   = marketify_get_theme_mods( array( 'keys_only' => true ) );
	
	$transport = array_merge( $built_in, $marketify );
	
	foreach ( $transport as $key => $default ) {
		if ( in_array( $key, array( 'footer-style', 'footer-logo', 'footer-contact-address' ) ) )
			$wp_customize->get_setting( $key )->transport = 'refresh';
		else
			$wp_customize->get_setting( $key )->transport = 'postMessage';
	}
}
add_action( 'customize_register', 'marketify_customize_register_transport' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Marketify 1.0
 */
function marketify_customize_preview_js() {
	wp_enqueue_script( 'marketify-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), 20131001, true );
}
add_action( 'customize_preview_init', 'marketify_customize_preview_js' );

/**
 * Textarea Control
 *
 * Attach the custom textarea control to the `customize_register` action
 * so the WP_Customize_Control class is initiated.
 *
 * @since Marketify 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function marketify_customize_textarea_control( $wp_customize ) {
	/**
	 * Textarea Control
	 *
	 * @since Marketify 1.0
	 */
	class Marketify_Customize_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';

		public function render_content() {
	?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>
	<?php
		}
	} 
}
add_action( 'customize_register', 'marketify_customize_textarea_control', 1, 1 );

function marketify_hex2rgb($hex) {
	$hex = str_replace( '#', '', $hex);

	if ( strlen( $hex ) == 3 ) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}

	$rgb = array($r, $g, $b);
	
	return implode(",", $rgb);
}

/**
 * Output the basic extra CSS for primary and accent colors.
 * Split away from widget colors for brevity. 
 *
 * @since Marketify 1.0
 */
function marketify_header_css() {
	$css = "
		.site-header,
		.site-footer,
		.page-header,
		body.page-template-page-templatesminimal-php,
		body.custom-background.page-template-page-templatesminimal-php,
		.header-outer,
		.minimal .entry-content .edd-slg-social-container span legend {
			background-color: " . marketify_theme_mod( 'colors', 'header' ) . ";
		}

		.page-header .edd-submit.button:hover, 
		.page-header a.edd-submit.button:hover, 
		.page-header a.edd-submit.button:visited:hover,
		.page-header .edd-submit.button.gray:hover,
		.page-header .button:hover,
		.soliloquy-caption-wrap .button:hover {
			color: " . marketify_theme_mod( 'colors', 'header' ) . ";
		}

		button:hover,
		html input[type=button]:not(.ed_button):hover,
		input[type=reset]:hover,
		input[type=submit]:hover,
		.button:hover,
		.widget .cart_item.edd_checkout a:hover,
		.edd-submit.button:hover, 
		a.edd-submit.button:hover,
		.edd-submit.button.gray:hover,
		.content-grid-download .entry-image .overlay,
		.widget .cart_item.edd_checkout a:hover,
		.search-form .search-submit,
		.marketify_widget_featured_popular .flex-control-nav .flex-active,
		#edd_checkout_form_wrap fieldset#edd_cc_fields > span:after,
		div.fes-form fieldset .fes-fields a.file-selector:hover,
		div.fes-form .fes-submit input[type=submit]:hover,
		#edd-purchase-button:hover,
		.fes-button:hover,
		div.fes-form fieldset .fes-fields #fes-insert-image-container a#fes-insert-image:hover {
			background-color: " . marketify_theme_mod( 'colors', 'primary' ) . ";
			background-image: none;
		}

		.widget .cart_item.edd_checkout a,
		.site-footer.light .footer-social a,
		.site-footer.light .site-title a,
		button,
		html input[type=button]:not(.ed_button),
		input[type=reset],
		input[type=submit],
		.button,
		.button:visited,
		a.button,
		.widget .cart_item.edd_checkout a,
		.edd-submit.button, 
		a.edd-submit.button, 
		a.edd-submit.button:visited,
		.edd-submit.button.gray,
		.edd-add-to-cart.button.edd-submit:hover,
		.edd_go_to_checkout.button.edd-submit,
		.edd-add-to-cart.edd-submit.button:hover, 
		a.edd-add-to-cart.edd-submit.button:hover,
		.edd_go_to_checkout.edd-submit.button,
		a.edd_go_to_checkout.edd-submit.button,
		.entry-image .button:hover,
		.entry-image .edd-submit.button:hover, 
		.entry-image a.edd-submit.button:hover, 
		.entry-image a.edd-submit.button:visited:hover,
		.entry-image .edd-submit.button.gray:hover,
		#edd_checkout_form_wrap fieldset#edd_cc_fields > span legend,
		.minimal .edd_cart_actions a,
		#fes-wraper .fes-menu nav ul li.active a,
		#fes-wraper .fes-menu nav ul li:hover a,
		.whistles-tabs .whistles-tabs-nav li[aria-selected='true'] a,
		div.fes-form .fes-submit input[type=submit],
		#edd-purchase-button,
		.entry-content div[itemprop=description] .download-variable .edd-add-to-cart.button.edd-submit:hover,
		.entry-content div[itemprop=description] .download-variable .edd_go_to_checkout.button.edd-submit,
		.marketify_widget_featured_popular .home-widget-title span:hover,
		.marketify-edd-rating .review-title-text,
		#fes-image_upload-pickfiles,
		div.fes-form fieldset .fes-fields a.file-selector,
		#fes-avatar-pickfiles,
		div.fes-form fieldset .fes-fields #fes-insert-image-container a#fes-insert-image,
		.edd-cart-added-alert {
			color: " . marketify_theme_mod( 'colors', 'primary' ) . ";
		}

		button,
		html input[type=button]:not(.ed_button),
		input[type=reset],
		input[type=submit],
		.button,
		.button:visited,
		.widget .cart_item.edd_checkout a,
		.edd-submit.button, 
		a.edd-submit.button, 
		a.edd-submit.button:visited,
		.edd-submit.button.gray,
		.widget .cart_item.edd_checkout a,
		.edd_price_options input:checked,
		.edd-add-to-cart.button.edd-submit:hover,
		.edd_go_to_checkout.button.edd-submit,
		.edd-add-to-cart.edd-submit.button:hover, 
		a.edd-add-to-cart.edd-submit.button:hover,
		.edd_go_to_checkout.edd-submit.button, 
		a.edd_go_to_checkout.edd-submit.button,
		#edd_checkout_form_wrap fieldset#edd_cc_fields > span legend,
		.entry-content div.fes-form fieldset .fes-fields a.file-selector:hover,
		div.fes-form .fes-submit input[type=submit],
		#edd-purchase-button,
		.entry-content .download-variable .edd-add-to-cart.button.edd-submit:hover,
		.entry-content .download-variable .edd_go_to_checkout.button.edd-submit,
		.marketify_widget_featured_popular .home-widget-title span:hover,
		.fes-button:hover,
		#fes-image_upload-pickfiles,
		div.fes-form fieldset .fes-fields a.file-selector,
		#fes-avatar-pickfiles,
		div.fes-form fieldset .fes-fields #fes-insert-image-container a#fes-insert-image {
			border-color: " . marketify_theme_mod( 'colors', 'primary' ) . ";
		}

		.main-navigation .search-form.active .search-submit,
		#edd_checkout_cart a.edd-cart-saving-button:hover,
		.edd-fes-adf-submission-add-option-button:hover,
		#edd_checkout_cart input[type=submit].edd-submit:hover,
		.minimal #edd-purchase-button,
		.main-navigation.toggled .search-form .search-submit,
		.minimal input[type=submit],
		.minimal input[type=submit]:hover,
		.popup .edd_go_to_checkout.button.edd-submit,
		.edd-reviews-voting-buttons a:hover {
			background-color: " . marketify_theme_mod( 'colors', 'accent' ) . ";
		}

		#edd_checkout_cart a.edd-cart-saving-button,
		#edd_checkout_cart input[type=submit].edd-submit,
		a.edd-fes-adf-submission-add-option-button,
		.popup .edd_go_to_checkout.button.edd-submit:hover,
		.popup .edd-add-to-cart.button.edd-submit:hover,
		.edd-reviews-voting-buttons a {
			color: " . marketify_theme_mod( 'colors', 'accent' ) . ";
		}

		#edd_checkout_cart a.edd-cart-saving-button,
		#edd_checkout_cart input[type=submit].edd-submit,
		.edd-fes-adf-submission-add-option-button,
		.minimal #edd-purchase-button,
		.minimal input[type=submit],
		.popup .edd_go_to_checkout.button.edd-submit,
		.popup .edd-add-to-cart.button.edd-submit:hover,
		.edd-reviews-voting-buttons a {
			border-color: " . marketify_theme_mod( 'colors', 'accent' ) . ";
		}

		.site-footer.light, {
			background-color: #" . get_theme_mod( 'background_color' ) . ";
		}

		.content-grid-download .entry-image:hover .overlay {
			background: rgba( " . marketify_hex2rgb( marketify_theme_mod( 'colors', 'primary' ) ) . ", .80 );
			border: 1px solid rgba( " . marketify_hex2rgb( marketify_theme_mod( 'colors', 'primary' ) ) . ", .80 );
		}

		.edd-add-to-cart.edd-submit.button, 
		a.edd-add-to-cart.edd-submit.button {
			border-color: #bcc3c8;
		}

		.edd-add-to-cart.edd-submit.button, 
		a.edd-add-to-cart.edd-submit.button {
			color: #bcc3c8;
		}

		.page-header .button,
		.entry-image .button,
		.entry-image .edd-submit.button, 
		.entry-image a.edd-submit.button, 
		.entry-image a.edd-submit.button:visited,
		.entry-image .edd-submit.button.gray,
		.page-header .edd-submit.button, 
		.page-header a.edd-submit.button, 
		.page-header a.edd-submit.button:visited,
		.page-header .edd-submit.button.gray,
		.soliloquy-caption-wrap .button {
			color: #fff;
			border-color: #fff !important;
		}

		.page-header .button:hover,
		.entry-image .button:hover,
		.entry-image .edd-submit.button:hover, 
		.entry-image a.edd-submit.button:hover, 
		.entry-image a.edd-submit.button:visited:hover,
		.entry-image .edd-submit.button.gray:hover,
		.page-header .edd-submit.button:hover, 
		.page-header a.edd-submit.button:hover, 
		.page-header a.edd-submit.button:visited:hover,
		.page-header .edd-submit.button.gray:hover,
		.page-header .edd-add-to-cart.button.edd-submit:hover,
		.soliloquy-caption-wrap .button:hover,
		.popup .edd_go_to_checkout.button.edd-submit:hover,
		.popup .edd-submit.button:hover, 
		.popup a.edd-submit.button:hover, 
		.popup .edd-submit.button.gray:hover {
			background-color: #fff;
		}

		.page-template-page-templatesminimal-php,
		.page-template-page-templatesminimal-php label,
		.page-template-page-templatesminimal-php a,
		.entry-content div[itemprop=description] .edd-add-to-cart.button.edd-submit:hover,
		.entry-content div[itemprop=description] .edd_download_inner .edd-add-to-cart.button.edd-submit:hover,
		.entry-content div[itemprop=description] .download-variable .edd-add-to-cart.button.edd-submit:hover,
		.entry-content div[itemprop=description] .download-variable .edd_download_inner .edd-add-to-cart.button.edd-submit:hover,
		.entry-content div[itemprop=description] .download-variable .edd_go_to_checkout.button.edd-submit:hover,
		#edd_checkout_cart a.edd-cart-saving-button:hover,
		div.fes-form fieldset .fes-fields #fes-insert-image-container a#fes-insert-image:hover,
		a.edd-fes-adf-submission-add-option-button:hover,
		#edd_checkout_cart input[type=submit].edd-submit:hover,
		div.fes-form .fes-submit input[type=submit]:hover,
		#edd-purchase-button:hover,
		.minimal #edd-purchase-button,
		#fes-image_upload-pickfiles:hover,
		div.fes-form fieldset .fes-fields a.file-selector:hover,
		#fes-avatar-pickfiles:hover,
		.minimal input[type=submit],
		.popup .edd_go_to_checkout.button.edd-submit,
		.edd-reviews-voting-buttons a:hover {
			color: #fff;
		}";

	wp_add_inline_style( 'marketify-base', $css );
}
add_action( 'wp_enqueue_scripts', 'marketify_header_css' );