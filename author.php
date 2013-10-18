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
				<div class="download-product-details author-archive">
					<div class="download-author">
						<?php do_action( 'marketify_download_author_before' ); ?>
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 50 ); ?>
						<?php printf( '<a class="author-link" href="%s" rel="author">%s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() ); ?>
						<span class="author-joined"><?php printf( __( 'Author since: %s', 'marketify' ), date_i18n( 'Y', strtotime( get_the_author_meta( 'user_registered' ) ) ) ); ?></span>
						<?php do_action( 'marketify_download_author_after' ); ?>
					</div>
					
					<div class="download-author-sales">
						<strong><?php global $wp_query; echo $wp_query->found_posts; ?></strong>
						<?php echo _n( 'Product', 'Products', $wp_query->found_posts, 'marketify' ); ?>
					</div>

					<div class="download-author-bio">
						<?php echo get_the_author_meta( 'description' ); ?>
					</div>

					<div class="download-author-social">
						<?php echo marketify_entry_author_social( get_the_author_meta( 'ID' ) ); ?>
					</div>
				</div>
			</div><!-- #secondary -->

			<section id="primary" class="content-area col-sm-9 col-xs-12">
				<main id="main" class="site-main" role="main">

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