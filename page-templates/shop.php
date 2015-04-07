<?php
/**
 * Template Name: Page: Shop
 *
 * Load the [downloads] shortcode.
 *
 * @package Marketify
 */

get_header(); ?>

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">

		<?php if ( ! is_paged() && ! get_query_var( 'm-orderby' ) && ! is_page_template( 'page-templates/popular.php' ) ) : ?>
			<?php get_template_part( 'content-grid-download', 'popular' ); ?>
		<?php endif; ?>

		<div id="content" class="site-content row">

			<section id="primary" class="content-area col-md-<?php echo is_active_sidebar( 'sidebar-download' ) ? '9' : '12'; ?> col-sm-7 col-xs-12">
				<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php if ( ! has_shortcode( get_the_content(), 'downloads' ) ) : ?>
						<?php echo do_shortcode( '[downloads]' ); ?>
					<?php else : ?>
						<?php the_content(); ?>
					<?php endif; ?>

				<?php endwhile; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar( 'archive-download' ); ?>

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>
