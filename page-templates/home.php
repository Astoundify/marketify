<?php
/**
 * Template Name: Page: Home
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div id="content" class="site-content">
        <div role="main" class="content-area full">

            <?php
                if ( ! dynamic_sidebar( 'home-1' ) ) :
                    $args = array(
                        'before_widget' => '<aside class="home-widget container">',
                        'after_widget'  => '</aside>',
                        'before_title'  => '<h1 class="home-widget-title"><span>',
                        'after_title'   => '</span></h1>',
                    );

                    the_widget( 'Marketify_Widget_Recent_Downloads', array( 'title' => 'Recent Downloads' ), $args );
                endif;
            ?>

        </div>
    </div><!-- #content -->

<?php get_footer(); ?>
