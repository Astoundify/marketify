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
			<?php if ( is_tax() ) : ?>
				<?php single_term_title(); ?>
			<?php elseif ( is_search() ) : ?>
				<?php echo esc_attr( get_search_query() ); ?>
			<?php else : ?>
				<?php echo apply_filters( 'marketify_downloads_archive_title', edd_get_label_plural() ); ?>
			<?php endif; ?>
		</h1>
	</header><!-- .page-header -->

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">

		<?php if ( ! is_paged() ) : ?>
			<?php get_template_part( 'content-grid-download', 'popular' ); ?>
		<?php endif; ?>

		<div id="content" class="site-content row">

			<section id="primary" class="content-area col-sm-12">
				<main id="main" class="site-main" role="main">

				<div class="section-title"><span>
					<?php if ( is_search() ) : ?>
						<?php printf( __( 'All %s', 'marketify' ), esc_attr( get_search_query() ) ); ?>
					<?php elseif ( is_tax() ) : ?>
						<?php printf( __( 'All %s', 'marketify' ), single_term_title( '', false ) ); ?>
					<?php else : ?>
						<?php printf( __( 'All %s', 'marketify' ), edd_get_label_plural() ); ?>
					<?php endif; ?>
				</span></div>

				<?php if ( have_posts() ) : ?>

					<div class="row">
						<?php while ( have_posts() ) : the_post(); ?>

							<div class="col-lg-4 col-sm-6 col-xs-12">
								<?php get_template_part( 'content-grid', 'download' ); ?>
							</div>

						<?php endwhile; ?>
					</div>

					<?php marketify_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'download' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar( 'archive-download' ); ?>

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>