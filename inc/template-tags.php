<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Marketify
 */

function marketify_purchase_link( $download_id ) {
	global $post, $edd_options;

	$variable = edd_has_variable_prices( $download_id );

	if ( ! $variable ) {
		echo edd_get_purchase_link( array( 'download_id' => $download_id, 'price' => false ) );
	} else {
		$button = ! empty( $edd_options[ 'add_to_cart_text' ] ) ? $edd_options[ 'add_to_cart_text' ] : __( 'Purchase', 'marketify' );

		printf( '<a href="#buy-now-%s" class="button buy-now popup-trigger">%s</a>', $post->ID, $button );
	}
}

function marketify_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
					<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
				</div><!-- .comment-author -->
			</footer><!-- .comment-meta -->

			<div class="comment-content">
				<div class="comment-metadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
						<time datetime="<?php comment_time( 'c' ); ?>">
							<?php printf( _x( '%1$s at %2$s', '1: date, 2: time', '_s' ), get_comment_date(), get_comment_time() ); ?>
						</time>
					</a>
					<?php edit_comment_link( __( 'Edit', 'marketify' ), ' &mdash; <span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-metadata -->

				<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'marketify' ); ?></p>
				<?php endif; ?>

				<?php comment_text(); ?>

				<?php
					comment_reply_link( array_merge( $args, array(
						'add_below' => 'div-comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<div class="reply">',
						'after'     => '</div>',
					) ) );
				?>
			</div><!-- .comment-content -->

		</article><!-- .comment-body -->

	<?php
}

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
		return $defalt;
 
	if( ! isset( $menu_obj->name ) ) 
		return $default;
 
	return $menu_obj->name;
}

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

	<?php if ( is_single() && apply_filters( 'marketify_single_content_nav', false ) ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<i class="icon-left-open-mini"></i> <span class="nav-title">%title</span>' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '<span class="nav-title">%title</span> <i class="icon-right-open-mini"></i>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( '<i class="icon-left-open-mini"></i>' ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( '<i class="icon-right-open-mini"></i>' ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
	<?php
}
endif; // marketify_content_nav