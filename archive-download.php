<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); ?>

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">

		<?php get_template_part( 'content-grid-download', 'popular' ); ?>

		<div id="content" class="site-content row">

			<section id="primary" class="content-area col-xs-12 <?php echo is_active_sidebar( 'sidebar-download' ) ? 'col-sm-7 col-md-9' : ''; ?>">
				<main id="main" class="site-main" role="main">
				
				<?php do_action( 'marketify_downloads_before' ); ?>

				<?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>

				<?php do_action( 'marketify_downloads_after' ); ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar( 'archive-download' ); ?>

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>
