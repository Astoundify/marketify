<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Marketify
 */

if ( ! is_active_sidebar( 'sidebar-download' ) )
	return;
?>
	<div id="secondary" class="download-archive-widget-area col-sm-3 col-xs-12" role="complementary">
		<?php do_action( 'before_sidebar' ); ?>

		<?php dynamic_sidebar( 'sidebar-download' ); ?>
	</div><!-- #secondary -->
