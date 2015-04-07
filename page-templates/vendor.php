<?php
/**
 * Template Name: Account: Vendor Profile
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); ?>

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">
		<div id="content" class="site-content row">

			<div id="secondary" class="author-widget-area col-md-3 col-sm-5 col-xs-12" role="complementary">
				<div class="download-product-details author-archive">
					<?php dynamic_sidebar( 'sidebar-vendor' ); ?>
				</div>
			</div><!-- #secondary -->

			<section id="primary" class="content-area col-md-9 col-sm-7 col-xs-12">
				<main id="main" class="site-main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
					<?php endwhile; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>
