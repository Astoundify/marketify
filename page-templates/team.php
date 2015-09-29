<?php
/**
 * Template Name: Page: Meet the Team
 *
 * @package Marketify
 */

get_header(); ?>

    <?php do_action( 'marketify_entry_before' ); ?>

    <div id="content" class="site-content container">

        <div role="main" class="content-area">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'content', 'page' ); ?>

            <?php endwhile; ?>

            <div class="the-team row" data-columns>
                <?php
                    $users = array();
                    $roles = apply_filters( 'marketify_the_team_roles', array( 'author', 'shop_worker', 'shop_manager', 'editor', 'administrator' ) );

                    foreach ( $roles as $role ) {
                        $user_ids = get_users( array(
                            'role'    => $role,
                            'fields'  => 'IDs'
                        ) );

                        foreach( $user_ids as $user_id ) {
                            $users[] = $user_id;
                        }
                    }

                    foreach ( $users as $user_id ) :
                ?>

                <div class="entry-author">
                    <?php
                        // $social = marketify_entry_author_social( $user_id );
                        $social = false;

                        printf( '<div class="gravatar">%1$s %2$s</div>',
                            sprintf( '<div class="author-social">%1$s</div>', $social ),
                            get_avatar( $user_id, 400 )
                        );
                    ?>
                    <?php
                        printf( '<span class="byline"><span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span></span>',
                            esc_url( get_author_posts_url( $user_id ) ),
                            esc_attr( sprintf( __( 'View all posts by %s', 'marketify' ), get_the_author_meta( 'display_name', $user_id ) ) ),
                            esc_html( get_the_author_meta( 'display_name', $user_id ) )
                        );
                    ?>
                    <?php echo wpautop( get_the_author_meta( 'description', $user_id ) ); ?>
                </div>

                <?php endforeach; ?>
            </div>

        <?php else : ?>

            <?php get_template_part( 'no-results', 'index' ); ?>

        <?php endif; ?>

        </div><!-- #primary -->

    </div>

<?php get_footer(); ?>
