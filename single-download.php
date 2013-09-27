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
			
		<p class="download-actions">
			<a href="#" class="button">Buy Now</a> <a href="#" class="button">Try the Demo</a>
		</p>

		<div class="featured-image container">
			<?php the_post_thumbnail( 'fullsize' ); ?>
		</div>
		<?php rewind_posts(); ?>
	</header><!-- .page-header -->

	<div class="container">
		<div id="content" class="site-content row">

			<section id="primary" class="content-area <?php echo ! is_active_sidebar( 'sidebar-download-single' ) ? 'col-xs-12' : 'col-sm-8 col-xs-12'; ?>">
				<main id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<h2 class="section-title"><span>About the Product</span></h2>

					<?php get_template_part( 'content-single', 'download' ); ?>

					<?php comments_template(); ?>
				<?php endwhile; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<?php get_sidebar( 'single-download' ); ?>

		</div><!-- #content -->
	</div>
	
<?php get_footer(); ?>