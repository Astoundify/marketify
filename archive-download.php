<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); ?>

		<header class="page-header">
			<h1 class="page-title"><?php echo apply_filters( 'marketify_archive_title', edd_get_label_plural() ); ?></h1>
		</header><!-- .page-header -->

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">

		<?php get_template_part( 'content-grid-download', 'popular' ); ?>

		<div id="content" class="site-content row">

			<section id="primary" class="content-area col-xs-12 <?php echo is_active_sidebar( 'sidebar-download' ) ? 'col-sm-7 col-md-9' : ''; ?>">
				<main id="main" class="site-main" role="main">

				<div class="section-title"><span>
					<?php echo apply_filters( 'marketify_archive_section_title', '' ); ?>
				</span></div>

				<?php echo do_shortcode( sprintf( '[downloads number="%s"]', get_option( 'posts_per_page' ) ) ); ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar( 'archive-download' ); ?>

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>
