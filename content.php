<?php
/**
 * @package Marketify
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'row' ); ?>>
	<div class="col-md-3 col-sm-4 col-xs-12">
		<?php the_post_thumbnail( 'medium' ); ?>
	</div>

	<div class="col-md-9 col-sm-8 col-xs-12">
		<header class="entry-header">
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

			<div class="entry-meta">
				<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
					<?php
						/* translators: used between list items, there is a space after the comma */
						$categories_list = get_the_category_list( __( ', ', 'marketify' ) );
						if ( $categories_list ) :
					?>
					<span class="cat-links">
						<?php printf( __( 'Posted in %1$s', 'marketify' ), $categories_list ); ?>
					</span>
					<?php endif; // End if categories ?>

					<?php
						/* translators: used between list items, there is a space after the comma */
						$tags_list = get_the_tag_list( '', __( ', ', 'marketify' ) );
						if ( $tags_list ) :
					?>
					<span class="tags-links">
						<?php printf( __( 'Tagged %1$s', 'marketify' ), $tags_list ); ?>
					</span>
					<?php endif; // End if $tags_list ?>
				<?php endif; // End if 'post' == get_post_type() ?>

				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'marketify' ), __( '1 Comment', 'marketify' ), __( '% Comments', 'marketify' ) ); ?></span>
				<?php endif; ?>

				<?php edit_post_link( __( 'Edit', 'marketify' ), '<span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>
</article><!-- #post-## -->
