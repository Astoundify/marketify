<?php
/**
 * Custom template tags for this theme.
 *
 * If the function is called directly in the theme or via
 * another function, it is wrapped to check if a child theme has
 * redefined it. Otherwise a child theme can unhook what is being attached.
 *
 * @package Marketify
 */

if ( ! function_exists( 'marketify_entry_author_social' ) ) :
/**
 * Social Links
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_entry_author_social( $user_id = null ) {
	global $post;

	$methods = _wp_get_user_contactmethods();
	$social  = array();

	if ( ! $user_id )
		$user_id = get_the_author_meta( 'ID' );

	foreach ( $methods as $key => $method ) {
		$field = get_the_author_meta( $key, $user_id );

		if ( ! $field )
			continue;

		$social[ $key ] = sprintf( '<a href="%1$s" target="_blank"><i class="icon-%2$s"></i></a>', $field, $key );
	}

	$social = implode( ' ', $social );

	return $social;
}
endif;

/**
 * Depending on the type of download, display some featured stuff.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_viewer() {
	global $post;

	$format = get_post_format();

	switch( $format ) {
		case 'audio' :
			marketify_download_audio_player();
			break;
		case 'video' :
			marketify_download_video_player();
			break;
		case false :
			marketify_download_standard_player();
			break;
		default :
			do_action( 'marketify_download_' . $format . '_player', $post );
			break;
	}
}
add_action( 'marketify_download_featured_area', 'marketify_download_viewer' );

if ( ! function_exists( 'marketify_download_standard_player' ) ) :
/**
 * Featured Area: Standard (Images)
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_standard_player() {
	global $post;

	$images = get_attached_media( 'image', $post->ID );
	$before = '<div class="download-image">';
	$after  = '</div>';

	/*
	 * Just one image and it's featured.
	 */
	if ( count( $images ) == 0 && has_post_thumbnail( $post->ID ) ) {
		echo $before;
		echo get_the_post_thumbnail( $post->ID, 'fullsize' );
		echo $after;

		return;
	}

	if ( count( $images ) == 1 ) {
		echo $before;
		echo wp_get_attachment_image( current( $images )->ID, 'fullsize' );
		echo $after;

		return;
	}

	$before = '<div class="download-image flexslider">';

	echo $before;
	?>

	<ul class="slides">
		<?php foreach ( $images as $image ) : ?>
		<li><?php echo wp_get_attachment_image( $image->ID, 'fullsize' ); ?></li>
		<?php endforeach; ?>
	</ul>

	<?php
	echo $after;
}
endif;

if ( ! function_exists( 'marketify_download_video_player' ) ) :
/**
 * Download Video Player
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_video_player() {
	global $post;

	$field = apply_filters( 'marketify_video_field', 'video' );
	$video = get_post_meta( $post->ID, $field, true );

	if ( ! $video )
		return;

	$oembed = wp_oembed_get( $video );

	if ( $oembed ) {
		global $wp_embed;

		$output = $wp_embed->run_shortcode( '[embed]' . $video . '[/embed]' );
	} else {
		$file = wp_get_attachment_url( $video );
		$info = wp_check_filetype( $file );

		$output = do_shortcode( sprintf( '[video %s="%s"]', $info[ 'ext' ], $video ) );
	}
	?>
		<div class="download-video"><?php echo $output; ?></div>
	<?php
}
endif;

if ( ! function_exists( 'marketify_download_audio_player' ) ) :
/**
 * Download Audio Player
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_audio_player() {
	global $post;

	$download_id = $post->ID;

	wp_enqueue_style( 'jplayer', get_template_directory_uri() . '/css/jplayer.css' );
	wp_enqueue_script( 'jplayer', get_template_directory_uri() . '/js/jquery.jplayer.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jplaylist', get_template_directory_uri() . '/js/jplayer.playlist.min.js' );

	$attachments = get_attached_media( 'audio', $download_id );
	$audio       = array();
	$exts        = array();

	foreach ( $attachments as $attachment ) {
		$file = wp_get_attachment_url( $attachment->ID );
		$info = wp_check_filetype( $file );

		if ( ! in_array( $info[ 'ext' ], $exts ) )
			$exts[] = $info[ 'ext' ];

		$audio[] = array(
			'title'          => get_the_title( $attachment->ID ),
			$info[ 'ext' ]   => $file
		);
	}
	?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready(function($){
			new jPlayerPlaylist({
				jPlayer: "#jplayer_<?php echo $download_id; ?>",
				cssSelectorAncestor: "#jp_container_<?php echo $download_id; ?>"
			}, <?php echo json_encode( $audio ); ?>, {
				swfPath        : "<?php echo get_template_directory_uri(); ?>/js",
				supplied       : "<?php echo implode( ', ', $exts ); ?>",
				wmode          : "window",
				smoothPlayBar  : true,
				keyEnabled     : true
			});
		});
		//]]>
		</script>

	<div id="jplayer_<?php echo $download_id; ?>" class="jp-jplayer"></div>

	<div id="jp_container_<?php echo $download_id; ?>" class="jp-audio">
		<div class="jp-type-playlist">
			<div class="jp-playlist">
				<ul>
					<li></li>
				</ul>
			</div>
			<div class="jp-gui jp-interface">
				<ul class="jp-controls">
					<li><a href="javascript:;" class="jp-previous" tabindex="1"><i class="icon-fast-backward"></i></a></li>
					<li><a href="javascript:;" class="jp-play" tabindex="1"><i class="icon-play"></i></a></li>
					<li><a href="javascript:;" class="jp-pause" tabindex="1"><i class="icon-pause"></i></a></li>
					<li><a href="javascript:;" class="jp-next" tabindex="1"><i class="icon-fast-forward"></i></a></li>
				</ul>
				<div class="jp-progress">
					<div class="jp-seek-bar">
						<div class="jp-play-bar"></div>
					</div>
				</div>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>
			<div class="jp-no-solution">
				<span><?php _e( 'Update Required', 'marketify' ); ?></span>
				<?php _e( 'To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.', 'marketify' ); ?>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'marketify_download_entry_meta_before_audio', 'marketify_download_audio_player' );
endif;

if ( ! function_exists( 'marketify_purchase_link' ) ) :
/**
 * Download Purchase Link
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_purchase_link( $download_id = null ) {
	global $post, $edd_options;

	if ( ! $download_id )
		$download_id = $post->ID;

	$variable = edd_has_variable_prices( $download_id );

	if ( ! $variable ) {
		echo edd_get_purchase_link( array( 'download_id' => $download_id, 'price' => false ) );
	} else {
		$button = ! empty( $edd_options[ 'add_to_cart_text' ] ) ? $edd_options[ 'add_to_cart_text' ] : __( 'Purchase', 'marketify' );

		printf( '<a href="#buy-now-%s" class="button buy-now popup-trigger">%s</a>', $post->ID, $button );
	}
}
add_action( 'marketify_download_actions', 'marketify_purchase_link' );
endif;

/**
 * Add the Price to the download information at the top of the page.
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_download_price() {
	global $post;

	edd_price( $post->ID );
}
add_action( 'marketify_download_info', 'marketify_download_price', 5 );

if ( ! function_exists( 'marketify_demo_link' ) ) :
/**
 * Download Purchase Link
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_demo_link( $download_id = null ) {
	global $post, $edd_options;

	if ( ! $download_id )
		$download_id = $post->ID;

	$field = apply_filters( 'marketify_demo_field', 'demo' );
	$demo  = get_post_meta( $download_id, $field, true );

	if ( ! $demo )
		return;

	$label = apply_filters( 'marketify_demo_button_label', __( 'Demo', 'marketify' ) );

	echo apply_filters( 'marketify_demo_link', sprintf( '<a href="%s" class="button" target="_blank">%s</a>', esc_url( $demo ), $label ) );
}
add_action( 'marketify_download_actions', 'marketify_demo_link' );
endif;

if ( ! function_exists( 'marketify_comment' ) ) :
/**
 * Comments
 *
 * @since Marketify 1.0
 *
 * @return void
 */
function marketify_comment( $comment, $args, $depth ) {
	global $post;

	$GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>

					<?php if ( $depth == 1 ) : ?>
						<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>

						<?php
							if ( get_option( 'comment_registration' ) && edd_has_user_purchased( $comment->user_id, $post->ID ) ) :
						?>
							<a class="button purchased"><?php _e( 'Purchased', 'marketify' ); ?></a>
						<?php endif; ?>
					<?php endif; ?>
				</div><!-- .comment-author -->
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<div class="comment-metadata">
					<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>

					<?php if ( get_comment_meta( $comment->comment_ID, 'edd_rating', true ) ) : ?>
						<?php do_action( 'marketify_edd_rating', $comment ); ?>
					<?php endif; ?>

					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', 'marketify' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>

					<?php
						comment_reply_link( array_merge( $args, array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<span class="reply-link"> &mdash; ',
							'after'     => '</span>',
						) ) );
					?>

					<?php edit_comment_link( __( 'Edit', 'marketify' ), ' &mdash; <span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'marketify' ); ?></p>
				<?php endif; ?>

				<?php comment_text(); ?>
			</div><!-- .comment-content -->

		</article><!-- .comment-body -->

	<?php
}
endif;

if ( ! function_exists( 'marketify_get_theme_menu' ) ) :
/**
 * Get a nav menu object.
 *
 * @uses get_nav_menu_locations To get all available locations
 * @uses get_term To get the specific theme location
 *
 * @since Marketify 1.0
 *
 * @param string $theme_location The slug of the theme location
 * @return object $menu_obj The found menu object
 */
function marketify_get_theme_menu( $theme_location ) {
	$theme_locations = get_nav_menu_locations();

	if( ! isset( $theme_locations[$theme_location] ) )
		return false;

	$menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );

	if( ! $menu_obj )
		return false;

	return $menu_obj;
}
endif;

if ( ! function_exists( 'marketify_get_theme_menu_name' ) ) :
/**
 * Get a nav menu name
 *
 * @uses marketify_get_theme_menu To get the menu object
 *
 * @since Marketify 1.0
 *
 * @param string $theme_location The slug of the theme location
 * @return string The name of the nav menu location
 */
function marketify_get_theme_menu_name( $theme_location ) {
	$menu_obj = marketify_get_theme_menu( $theme_location );
	$default  = _x( 'Menu', 'noun', 'marketify' );

	if( ! $menu_obj )
		return $default;

	if( ! isset( $menu_obj->name ) )
		return $default;

	return $menu_obj->name;
}
endif;

if ( ! function_exists( 'marketify_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function marketify_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

	?>
	<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'marketify' ); ?></h1>

		<?php
			$big = 999999999;

			echo paginate_links( array(
				'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'  => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total'   => $wp_query->max_num_pages
			) );
		?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif;

function marketify_download_archive_popular( $args = array() ) {
	$defaults = array(
		'posts_per_page'         => 9,
		'meta_key'               => '_edd_download_sales',
		'orderby'                => 'meta_value',
		'timeframe'              => 'month' // 'week'
	);

	$args = apply_filters( 'marketify_download_archive_popular', wp_parse_args( $args, $defaults ) );

	$query_args = array(
		'post_type'              => 'download',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'cache_results'          => false,
	);

	$query_args = wp_parse_args( $query_args, $args );

	// Date
	if ( 'day' == $args[ 'timeframe' ] ) {
		$frame = date( 'd' );
	} else if ( 'week' == $args[ 'timeframe' ] ) {
		$frame = date( 'W' );
	} else if ( 'month' == $args[ 'timeframe' ] ) {
		$frame = date( 'm' );
	} else {
		$frame = date( 'Y' );
	}

	$query_args[  'date_query' ] = array( array( $args[ 'timeframe' ] => $frame ) );

	// Taxonomy
	if ( is_tax( array( 'download_category', 'download_tag' ) ) ) {
		$obj = get_queried_object();

		$query_args[ 'tax_query' ] = array(
			array(
				'taxonomy' => $obj->taxonomy,
				'field'    => 'ids',
				'terms'    => array( $obj->term_id )
			)
		);
	}

	// Search
	if ( is_search() ) {
		$query_args[ 's' ] = esc_attr( get_search_query() );
	}

	$popular  = new WP_Query( $query_args );

	return $popular;
}