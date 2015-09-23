<?php
/**
 *
 */

global $post;
?>

<header class="content-grid-download__entry-header">
    <h1 class="entry-title <?php if ( 'on' == marketify_theme_mod( 'downloads-archives-truncate-title' ) ) : ?> entry-title--truncated<?php endif; ?>"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

	<?php if ( marketify_theme_mod( 'downloads-archives-excerpt' ) && $post->post_excerpt ) : ?>
        <div class="entry-excerpt"><?php echo esc_attr( wp_trim_words( $post->post_excerpt ) ); ?></div>
	<?php endif; ?>

	<div class="entry-meta">
		<?php do_action( 'marketify_download_entry_meta_before_' . get_post_format() ); ?>

		<?php do_action( 'marketify_download_entry_meta' ); ?>

		<?php do_action( 'marketify_download_entry_meta_after_' . get_post_format() ); ?>
	</div>
</header><!-- .entry-header -->
