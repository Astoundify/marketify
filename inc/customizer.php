<?php
/**
 * Customize
 *
 * Theme options are lame! Manage any customizations through the Theme
 * Customizer. Expose the customizer in the Appearance panel for easy access.
 *
 * @package Jobify
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
	$wp_customize->add_section( 'marketify_general', array(
		'title'      => _x( 'General', 'Theme customizer section title', 'marketify' ),
		'priority'   => 10,
	) );

	$wp_customize->add_section( 'marketify_footer', array(
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
		'marketify_general' => array(
			'responsive' => array(
				'title'   => __( 'Enable Responsive Design', 'marketify' ),
				'type'    => 'checkbox',
				'default' => 1
			),
		),
		'marketify_footer' => array(
			'style' => array(
				'title'   => __( 'Style', 'marketify' ),
				'type'    => 'select',
				'choices' => array(
					0 => __( 'Dark', 'marketify' ),
					1 => __( 'Light', 'marketify' )
				),
				'default' => 0
			),
			'contact-address' => array(
				'title'   => __( 'Contact Address', 'marketify' ),
				'type'    => 'Marketify_Customize_Textarea_Control',
				'default' => "393 Bay Street, 2nd Floor\nToronto, Ontario, Canada, L9T8S2"
			),
			'logo' => array(
				'title'   => __( 'Logo', 'marketify' ),
				'type'    => 'WP_Customize_Image_Control',
				'default' => 0
			)
		),
		'colors' => array(
			'primary' => array(
				'title'   => __( 'Primary Color', 'marketify' ),
				'type'    => 'WP_Customize_Color_Control',
				'default' => '#515a63'
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
	wp_enqueue_script( 'marketify-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), 20130704, true );
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
		.content-grid-download .entry-image .overlay {
			background: #515a63;
		}

		a,
		a:hover,
		.button:hover,
		.widget .cart_item.edd_checkout a {
			color: #515a63;
		}

		.widget .cart_item.edd_checkout a {
			border-color: #515a63;
		}";

	wp_add_inline_style( 'marketify-base', $css );
}
add_action( 'wp_enqueue_scripts', 'marketify_header_css' );