<?php
/**
 * @package Marketify
 */

// Are we on a homepage widget?
$is_home = is_page_template( 'page-templates/home.php' ) || is_page_template( 'page-templates/home-search.php' );

global $more;

$more = 0;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header entry-header--hentry">
        <h3 class="entry-title entry-title--hentry"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>

        <div class="entry-meta entry-meta--hentry">
            <?php
                printf(
                    __( '<span class="byline">%1$s</span>', 'marketify' ),
                    sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s %4$s</a></span>',
                        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                        esc_attr( sprintf( __( 'View all posts by %s', 'marketify' ), get_the_author() ) ),
                        get_avatar( get_the_author_meta( 'ID' ), 50, apply_filters( 'marketify_default_avatar', null ) ),
                        esc_html( get_the_author_meta( 'display_name' ) )
                    )
                );
            ?>

            <span class="entry-date"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo get_the_date(); ?></a></span>

            <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
            <span class="comments-link"><?php comments_popup_link( __( '0 Comments', 'marketify' ), __( '1 Comment', 'marketify' ), __( '% Comments', 'marketify' ) ); ?></span>
            <?php endif; ?>

            <?php edit_post_link( __( 'Edit', 'marketify' ), '<span class="edit-link">', '</span>' ); ?>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->
</article><!-- #post-## -->
