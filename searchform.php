<?php
/**
 * The template for displaying search forms in Marketify
 *
 * @package Marketify
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<button type="submit" class="search-submit"><i class="icon-search"></i></button>
	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'marketify' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'marketify' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'marketify' ); ?>">
		<?php if ( is_post_type_archive( 'download' ) || is_tax( array( 'download_tag', 'download_category' ) ) ) : ?>
		<input type="hidden" name="post_type" value="download" />
		<?php endif; ?>
	</label>
</form>
