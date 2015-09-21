<?php
/**
 * Template Name: Page: Pricing Table
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div class="container">
        <div id="content" class="site-content row">

            <div id="primary" class="content-area col-sm-12">
                <main id="main" class="site-main" role="main">

                <?php if ( have_posts() ) : ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php
                            the_widget( 
                                'Marketify_Widget_Price_Table', 
                                array(
                                    'title'       => null,
                                    'description' => null
                                ),
                                array(
                                    'widget_id'     => 'widget_price_table_page',
                                    'before_widget' => '<aside id="%1$s" class="widget widget--home %2$s">',
                                    'after_widget'  => '</aside>',
                                    'before_title'  => '<h3 class="widget-title--home"><span>',
                                    'after_title'   => '</span></h3>',
                                ) 
                            );
                        ?>

                        <?php get_template_part( 'content', 'page' ); ?>

                        <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if ( comments_open() || '0' != get_comments_number() )
                                comments_template();
                        ?>

                    <?php endwhile; ?>

                    <?php marketify_content_nav( 'nav-below' ); ?>

                <?php else : ?>

                    <?php get_template_part( 'no-results', 'index' ); ?>

                <?php endif; ?>

                </main><!-- #main -->
            </div><!-- #primary -->

        </div>
    </div>

<?php get_footer(); ?>
