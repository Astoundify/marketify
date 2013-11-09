<div id="fes-vendor-dashboard">
	<div id="fes-vendor-store-link">
		<a href="<?php echo Marketify_Author::url(); ?>" class="button"><?php _e( 'Visit Portfolio', 'marketify' ); ?></a>
	</div>
	
	<br />
	
	<?php echo apply_filters( 'the_content', EDD_FES()->fes_options->get_option( 'dashboard-page-template' ) ); ?>
</div>	