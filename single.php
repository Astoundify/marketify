<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div class="container">
        <div id="content" class="site-content row">

            <div id="primary" class="content-area col-md-8 col-xs-12">
                <main id="main" class="site-main" role="main">

                <?php if ( have_posts() ) : ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'content', 'single' ); ?>
                        <?php comments_template(); ?>

                    <?php endwhile; ?>

                <?php else : ?>

                    <?php get_template_part( 'no-results', 'index' ); ?>

                <?php endif; ?>

                </main><!-- #main -->
            </div><!-- #primary -->

            <?php get_sidebar(); ?>

        </div>
    </div>

<?php get_footer(); ?>
