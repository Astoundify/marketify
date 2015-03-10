<?php
/**
 * Template Name: Homepage
 *
 * @package Marketify
 */

get_header(); ?>

		<?php the_post(); ?>
		<header class="page-header">
			<div class="container">
				<h1 class="page-title"><?php the_title(); ?></h1>

				<?php add_filter( 'wp_video_shortcode_library', '__return_false' ); ?>
				<?php the_content(); ?>
				<?php remove_filter( 'wp_video_shortcode_library', '__return_false' ); ?>
			</div>
		</header><!-- .page-header -->
		<?php rewind_posts(); ?>

	<?php do_action( 'marketify_entry_before' ); ?>

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
