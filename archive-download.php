<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div id="content" class="site-content container">

        <?php get_template_part( 'content-grid-download', 'popular' ); ?>

        <div class="marketify-archive-download row">
            <div role="main" class="content-area col-xs-12 <?php echo is_active_sidebar( 'sidebar-download' ) ? 'col-sm-7 col-md-9' : ''; ?>">

                <?php do_action( 'marketify_downloads_before' ); ?>

                <?php echo do_shortcode( sprintf( '[downloads number="%s"]', marketify_theme_mod( 'downloads-archives-per-page' ) ) ); ?>

                <?php do_action( 'marketify_downloads_after' ); ?>

            </div #primary -->

            <?php get_sidebar( 'archive-download' ); ?>
        </div>

    </div>

<?php get_footer(); ?>
