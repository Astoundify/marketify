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
		<?php the_post(); ?>
		<h1 class="page-title"><?php the_title(); ?></h1>
			
		<div class="download-actions">
			<?php marketify_purchase_link( get_the_ID() ); ?>
		</div>

		<div class="download-info">
			<?php do_action( 'marketify_download_info' ); ?>
		</div>

		<div class="featured-image container">
			<?php marketify_download_viewer(); ?>
		</div>
		<?php rewind_posts(); ?>
	</header><!-- .page-header -->

	<?php echo 'video' == get_post_format() ? '</div>' : ''; ?>

	<div class="container">
		<div id="content" class="site-content row">

			<section id="primary" class="content-area <?php echo ! is_active_sidebar( 'sidebar-download-single' ) ? 'col-xs-12' : 'col-sm-8 col-xs-12'; ?>">
				<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content-single', 'download' ); ?>
				<?php endwhile; rewind_posts(); ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar( 'single-download' ); ?>

		</div><!-- #content -->

		<div id="comments" class="row">
			<section id="primary" class="content-area col-sm-8 col-xs-12">
				<?php comments_template(); ?>
			</section>

			<div class="col-sm-4">
				Tst
			</div>
		</div>

		<?php do_action( 'marketify_single_download_after' ); ?>
	</div>
	
<?php get_footer(); ?>