<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Marketify
 */

get_header(); ?>

	<?php the_post(); ?>

		<?php get_template_part( 'content', 'author' ); ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>

	</div><!-- .header-outer -->
	<?php rewind_posts(); ?>

	<div class="container">
		<div id="content" class="site-content row">

			<div id="primary" class="content-area col-sm-8">
				<main id="main" class="site-main" role="main">

				<?php if ( have_posts() ) : ?>

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; ?>

					<?php marketify_content_nav( 'nav-below' ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'index' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</div><!-- #primary -->

			<?php get_sidebar(); ?>
			
		</div>
	</div>
	
<?php get_footer(); ?>