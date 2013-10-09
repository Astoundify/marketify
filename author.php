<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

if ( ! get_query_var( 'author_ptype' ) )
	return locate_template( array( 'archive.php' ), true );

get_header(); ?>

	<header class="page-header">
		<?php the_post(); ?>
		<h1 class="page-title"><?php the_author(); ?></h1>
	</header><!-- .page-header -->

	<div class="container">
		<div id="content" class="site-content row">

			<div id="secondary" class="author-widget-area col-sm-3 col-xs-12" role="complementary">
				<h1 class="section-title"><span>Author Bio</span></h1>
			</div><!-- #secondary -->

			<section id="primary" class="content-area col-sm-9 col-xs-12">
				<main id="main" class="site-main" role="main">

				<div class="section-title"><span>
					<?php _e( 'Recent', 'marketify' ); ?>
				</span></div>

				<?php rewind_posts(); if ( have_posts() ) : ?>

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