<?php
/**
 * The template for displaying search forms in Marketify
 *
 * @package Marketify
 */

$type = 'post';

if ( is_post_type_archive( 'download' ) || is_tax( array( 'download_tag', 'download_category' ) ) )
	$type = 'download';
?>

<form role="search" method="get" class="search-form<?php echo '' != get_search_query() ? ' active' : ''; ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<button type="submit" class="search-submit"><i class="icon-search"></i></button>
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'marketify' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'marketify' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'marketify' ); ?>">
	</label>

	<input type="hidden" name="post_type" value="<?php echo esc_attr( $type ); ?>" />
</form>
