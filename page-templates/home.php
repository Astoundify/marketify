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
					<?php 
						if ( ! dynamic_sidebar( 'home-1' ) ) :
							$args = array(
								'before_widget' => '<aside class="home-widget">',
								'after_widget'  => '</aside>',
								'before_title'  => '<h1 class="home-widget-title"><span>',
								'after_title'   => '</span></h1>',
							);

							the_widget( 'Marketify_Widget_Recent_Downloads', array( 'title' => 'Recent Downloads' ), $args );
						endif;
					?>
				</div>

			</main><!-- #main -->
		</section><!-- #primary -->

	</div><!-- #content -->

<?php get_footer(); ?>
