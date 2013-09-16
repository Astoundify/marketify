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

			<?php get_sidebar( 'download' ); ?>

			<section id="primary" class="content-area col-sm-9">
				<main id="main" class="site-main" role="main">

				<div class="section-title"><span>
					<?php if ( is_tax() ) : ?>
						<?php single_term_title(); ?>
					<?php else : ?>
						<?php _e( 'Recent', 'marketify' ); ?>
					<?php endif; ?>
				</span></div>

				<?php if ( have_posts() ) : ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
							/* Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );
						?>

					<?php endwhile; ?>

					<?php marketify_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'archive' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

		</div><!-- #content -->
	</div>
	
<?php get_footer(); ?>
