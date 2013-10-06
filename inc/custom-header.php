<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

	<?php $header_image = get_header_image();
	if ( ! empty( $header_image ) ) { ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="">
		</a>
	<?php } // if ( ! empty( $header_image ) ) ?>

 *
 * @package Marketify
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses marketify_header_style()
 * @uses marketify_admin_header_style()
 * @uses marketify_admin_header_image()
 *
 * @package Marketify
 */
function marketify_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'marketify_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'fff',
		'width'                  => 150,
		'height'                 => 55,
		'flex-height'            => true,
		'flex-width'             => true,
		'wp-head-callback'       => 'marketify_header_style',
		'admin-head-callback'    => 'marketify_admin_header_style',
		'admin-preview-callback' => 'marketify_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'marketify_custom_header_setup' );

if ( ! function_exists( 'marketify_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see marketify_custom_header_setup().
 */
function marketify_header_style() {
	$header_text_color = get_header_textcolor();
?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description,
		.main-navigation a {
			color: #<?php echo $header_text_color; ?>;
		}

		.site-title {
			line-height: <?php echo get_custom_header()->height; ?>px
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // marketify_header_style

if ( ! function_exists( 'marketify_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see marketify_custom_header_setup().
 */
function marketify_admin_header_style() {
	$header_image = get_custom_header();
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			border: none;
			background-color: <?php echo marketify_theme_mod( 'colors', 'primary' ); ?>;
			padding: 40px;
			width: auto;
		}
		
		#headimg h1,
		#desc {
		}

		#headimg h1 {
			margin: 0 0 0 40px;
			font-family: 'Pacifico', cursive;
			font-size: 36px;
			font-weight: normal;
			line-height: <?php echo get_custom_header()->height; ?>px
		}

		#headimg h1 a {
			text-decoration: none;
		}

		#desc {
			display: none;
		}

		#headimg img {
			float: left;
		}
	</style>
<?php
}
endif; // marketify_admin_header_style

if ( ! function_exists( 'marketify_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see marketify_custom_header_setup().
 */
function marketify_admin_header_image() {
	$style        = sprintf( ' style="color:#%s;"', get_header_textcolor() );
	$header_image = get_header_image();
?>
	<div id="headimg">
		<?php if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="">
		<?php endif; ?>

		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php
}
endif; // marketify_admin_header_image
