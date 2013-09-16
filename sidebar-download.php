<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Marketify
 */
?>
	<div id="secondary" class="download-widget-area col-sm-3" role="complementary">
		<?php do_action( 'before_sidebar' ); ?>
		<?php if ( ! dynamic_sidebar( 'sidebar-download' ) ) : ?>

			<aside id="download_categories" class="download-widget">
				<h1 class="download-widget-title"><?php _e( 'Categories', 'marketify' ); ?></h1>
				<ul>
					<?php wp_list_categories( array( 
						'taxonomy' => 'download_category', 
						'title_li' => '', 
						'hide_empty' => false
					) ); ?>
				</ul>
			</aside>

			<aside id="search" class="download-widget widget_search">
				<?php get_search_form(); ?>
			</aside>

		<?php endif; // end sidebar widget area ?>
	</div><!-- #secondary -->
