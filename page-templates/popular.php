<?php
/**
 * Template Name: Popular Items
 *
 * @package Marketify
 */

get_header(); ?>

	<?php do_action( 'marketify_entry_before' ); ?>

	<div class="container">
		<div id="content" class="site-content row">

			<section id="primary" class="content-area col-sm-9 col-xs-12">
				<main id="main" class="site-main" role="main">

				<div class="section-title"><span>
					<?php if ( get_query_var( 'popular_cat' ) ) : ?>
						<?php $term = get_term( get_query_var( 'popular_cat' ), 'download_category' ); ?>
						<?php echo esc_attr( $term->name ); ?>
					<?php else : ?>
						<?php printf( __( 'All %s', 'marketify' ), edd_get_label_plural() ); ?>
					<?php endif; ?>
				</span></div>

				<?php
					$args = array(
						'post_type' => 'download',
						'meta_key'  => '_edd_download_sales',
						'orderby'   => 'meta_value'
					);

					if ( get_query_var( 'popular_cat' ) ) {
						$args[ 'tax_query' ] = array(
							array(
								'taxonomy' => 'download_category',
								'field'    => 'id',
								'terms'    => explode( ',', get_query_var( 'popular_cat' ) )
							)
						);
					}

					$popular = new WP_Query( $args );
				?>

				<?php if ( $popular->have_posts() ) : ?>

					<div class="row">
						<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>

							<div class="col-lg-4 col-md-6 col-sm-12">
								<?php get_template_part( 'content-grid', 'download' ); ?>
							</div>

						<?php endwhile; ?>
					</div>

					<?php marketify_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<?php get_template_part( 'no-results', 'download' ); ?>

				<?php endif; ?>

				</main><!-- #main -->
			</section><!-- #primary -->

			<div id="secondary" class="download-archive-widget-area col-sm-3 col-xs-12" role="complementary">
				<aside class="widget download-archive-widget widget_categories">
					<h1 class="download-archive-widget-title"><?php _e( 'Categories', 'marketify' ); ?></h1>
					<ul>
						<?php
							wp_list_categories( array(
								'title_li' => '',
								'taxonomy' => 'download_category',
								'hide_empty' => false
							) );
						?>
					</ul>
				</aside>
			</div><!-- #secondary -->

		</div><!-- #content -->
	</div>

<?php get_footer(); ?>