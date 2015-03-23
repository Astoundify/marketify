<?php
/**
 * Template Name: Account: Vendor Profile
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

$fes = marketify()->get( 'easy-digital-downloads-frontend-submissions' );
$vendor = $fes->vendor();

if ( ! $vendor->obj ) {
	wp_redirect( $vendor->url( get_current_user_id() ) );
	exit();
}

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

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

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

				</main><!-- #main -->
			</section><!-- #primary -->

		</div><!-- #content -->
	</div>

	<?php endwhile; ?>

<?php get_footer(); ?>
