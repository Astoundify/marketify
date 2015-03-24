<?php
/**
 * Template Name: Page: Homepage
 *
 * @package Marketify
 */

get_header(); ?>

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
