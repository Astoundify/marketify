<?php
/**
 * Wish List template
*/

// get list ID
$list_id = edd_wl_get_list_id();

// gets the list
$downloads = edd_wl_get_wish_list();
// get list post object
$list = get_post( $list_id );
// title
$title = get_the_title( $list_id );
//status
$privacy = get_post_status( $list_id );

$downloads = wp_list_pluck( $downloads, 'id' );

$downloads = new WP_Query( array(
	'post_type'   => 'download',
	'post_status' => 'publish',
	'post__in'    => $downloads
) );

?>

<?php if ( $list_id ) : ?>
	<p><?php echo $list->post_content; ?></p>
<?php endif; ?>

<?php if ( $downloads ) : ?>

	<?php // All all items in list to cart
		echo edd_wl_add_all_to_cart_link( $list_id );
	?>

	<div class="download-grid-wrapper edd_downloads_list columns-<?php echo marketify_theme_mod( 'product-display', 'product-display-columns' ); ?> row" data-columns>
		<?php while ( $downloads->have_posts() ) : $downloads->the_post(); ?>
			<?php get_template_part( 'content-grid', 'download' ); ?>
		<?php endwhile; ?>
	</div>

	<?php
	/**
	 * Sharing - only shown for public lists
	*/
	if ( 'private' !== get_post_status( $list_id ) && apply_filters( 'edd_wl_display_sharing', true ) ) : ?>
		<div class="edd-wl-sharing">
			<h3><?php _e( 'Share', 'edd-wish-lists' ); ?></h3>
			<p><?php echo wp_get_shortlink( $list_id ); // Shortlink to share ?></p>

			<?php
				// Share via email
				echo edd_wl_share_via_email_link();

				// Social sharing services
				echo edd_wl_sharing_services();
			?>
		</div>
	<?php endif; ?>

<?php endif; ?>

<?php // edit settings
	echo edd_wl_edit_settings_link( $list_id );
?>