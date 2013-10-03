<?php
/**
 * Template Name: Homepage
 *
 * @package Marketify
 */

get_header(); ?>

		<header class="page-header">
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="container">
					<h1 class="page-title"><?php the_title(); ?></h1>

					<?php the_content(); ?>
				</div>

				<!--<div class="hero-image">
					<?php the_post_thumbnail( 'fullsize' ); ?>
				</div>-->
			<?php endwhile; ?>
		</header><!-- .page-header -->

	</div>

	<div id="content" class="site-content">

		<section id="primary" class="content-area full">
			<main id="main" class="site-main" role="main">

				<div class="container">
					<?php dynamic_sidebar( 'home-1' ); ?>
				</div>

			</main><!-- #main -->
		</section><!-- #primary -->

	</div><!-- #content -->	
	
<?php get_footer(); ?>
