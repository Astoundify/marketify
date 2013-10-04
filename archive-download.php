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
		<h1 class="page-title">
			<?php echo apply_filters( 'marketify_downloads_archive_title', edd_get_label_plural() ); ?></h1>
	</header><!-- .page-header -->

	<div class="container">
		<div id="content" class="site-content row">

			<?php get_sidebar( 'archive-download' ); ?>

			<section id="primary" class="content-area col-sm-9 col-xs-12">
				<main id="main" class="site-main" role="main">

				<div class="section-title"><span>
					<?php if ( is_tax() ) : ?>
						<?php single_term_title(); ?>
					<?php elseif ( is_search() ) : ?>
						<?php echo esc_attr( get_search_query() ); ?>
					<?php else : ?>
						<?php _e( 'Recent', 'marketify' ); ?>
					<?php endif; ?>
				</span></div>

				<?php if ( have_posts() ) : ?>

					<div class="row">
						<?php while ( have_posts() ) : the_post(); ?>

							<div class="col-lg-4 col-md-6 col-s-12">
								<?php get_template_part( 'content-grid', 'download' ); ?>
							</div>

						<?php endwhile; ?>
					</div>

					<?php marketify_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'archive' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

		</div><!-- #content -->
	</div>
	
<?php get_footer(); ?>