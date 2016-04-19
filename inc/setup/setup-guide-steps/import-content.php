<?php
/**
 */

$plugins = array(
	'edd' => array(
		'label' => 'Easy Digital Downloads',
		'condition' => class_exists( 'Easy_Digital_Downloads' ),
		'file' => get_template_directory() . '/inc/setup/import-content/{style}/plugin_easy_digital_downloads.json'
	),
	'fes' => array(
		'label' => 'Frontend Submissions',
		'condition' => class_exists( 'EDD_Front_End_Submissions' ),
		'file' => get_template_directory() . '/inc/setup/import-content/{style}/plugin_frontend_submissions.json'
	),
	// 'features' => array(
	// 	'label' => 'Features by WooThemes',
	// 	'condition' => class_exists( 'WooThemes_Features' ),
	// 	'file' => get_template_directory() . '/inc/setup/import-content/{style}/plugin_woothemes_features.json'
	// ),
	// 'testimonials' => array(
	// 	'label' => 'Testimonials by WooThemes',
	// 	'condition' => class_exists( 'WooThemes_Testimonials' ),
	// 	'file' => get_template_directory() . '/inc/setup/import-content/{style}/plugin_woothemes_testimonials.json'
	// )
);

$to_import = array(
	'nav_menus' => array(
		'label' => __( 'Theme Settings' ),
		'files' => array(
			get_template_directory() . '/inc/setup/import-content/{style}/theme_mods.json',
			get_template_directory() . '/inc/setup/import-content/{style}/nav_menus.json',
			get_template_directory() . '/inc/setup/import-content/{style}/nav_menu_items.json'
		)
	),
	'posts_pages' => array(
		'label' => __( 'Posts and Pages' ),
		'files' => array(
			get_template_directory() . '/inc/setup/import-content/{style}/terms.json',
			get_template_directory() . '/inc/setup/import-content/{style}/posts.json',
			get_template_directory() . '/inc/setup/import-content/{style}/pages.json'
		)
	),
	'widgets' => array(
		'label' => __( 'Widgets' ),
		'files' => array(
			get_template_directory() . '/inc/setup/import-content/{style}/widgets.json',
		)
	),
	'plugins' => array(
		'label' => __( 'Plugin Content' ),
		'files' => wp_list_pluck( $plugins, 'file' ),
		'plugins' => $plugins
	)
);
?>

<form id="marketify-oneclick-setup" action="" method="">

	<p><?php _e( 'Please do not navigate away from this page while content is importing.', 'marketify' ); ?></p>

	<p>
		<strong><?php _e( 'Demo Style', 'marketify' ); ?></strong>
	</p>

	<div class="demo-style-selector">
		<p>
			<label for="default">
				<input type="radio" value="default" name="demo_style" id="default" checked="checked">
				<?php _e( 'Default', 'marketify' ); ?>
			</label>
		</p>
	</div>

	<p><strong><?php _e( 'Import Summary', 'marketify' ); ?>:</strong></p>

	<ul class="import-list" style="list-style: none;">

		<?php foreach ( $to_import as $import_key => $import ) : ?>
		<li>
			<label for="<?php echo esc_attr( $import_key ); ?>">
				<input 
					type="checkbox" 
					name="to_import" 
					id="<?php echo esc_attr( $import_key ); ?>"
					value="<?php echo esc_attr( $import_key ); ?>" 
					data-files="<?php echo implode( ',', $import[ 'files' ] ); ?>" 
					<?php checked( false, Astoundify_Importer_Manager::has_previously_imported( $import_key ) ); ?> 
				/>
				<?php echo esc_attr( $import[ 'label' ] ); ?>

				<?php if ( Astoundify_Importer_Manager::has_previously_imported( $import_key ) ) : ?>
					&nbsp;<em class="previously-imported"><small><?php _e( 'Previously Imported' ); ?></small></em>
				<?php endif; ?>

				<div class="spinner"></div>
			</label>

			<?php if ( 'plugins' == $import_key ) : ?>
				<div class="plugins-to-import">
					<p><?php _e( 'Please review your active plugins before importing content. Only active plugins can have content imported.', 'marketify' ); ?></p>

					<ul>
					<?php foreach ( $import[ 'plugins' ] as $key => $plugin ) : ?>
					<li>
						<strong><?php echo esc_attr( $plugin[ 'label' ] ); ?></strong> &mdash; 
						<?php if ( $plugin[ 'condition' ] ) : ?>
							<span class="active"><?php _e( 'Active', 'marketify' ); ?></span>
						<?php else : ?>
							<span class="inactive"><?php _e( 'Inactive', 'marketify' ); ?></span>
						<?php endif; ?>
					</li>
					<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>

	</ul>

	<?php submit_button( __( 'Import Selected', 'marketify' ), 'primary', 'process', false ); ?>
	&nbsp;
	<?php submit_button( __( 'Reset Selected', 'marketify' ), 'secondary', 'reset', false ); ?>

</form>

<script>
	jQuery(document).ready(function($) {
		$form = $( '#marketify-oneclick-setup' );

		$form.on( 'submit', function(e) {
			return false;
		});

		$( '.plugins-to-import' ).toggle( $( 'input[value=plugins]' ).is( ':checked' ) );

		$( 'input[value=plugins]' ).on( 'change', function(e) {
			var checked = $(this).attr( 'checked' );

			$( '.plugins-to-import' ).toggle( 'checked' == checked );
		});

		$form.find( 'input[type=submit]' ).on( 'click', function(e) {
			e.preventDefault();

			var $button = $(this);

			// what process action are we taking?
			var process_action = $button.attr( 'name' );

			// set some basic args
			var args = {
				action: 'marketify_oneclick_setup',
				process_action: process_action,
				security: '<?php echo wp_create_nonce( 'marketify-oneclick-setup' ); ?>'
			};

			// find the items to perform the action on 
			var $to_process = $form.find( 'input[name=to_import]:checked' );

			var dfd = $.Deferred().resolve();

			// style to use
			var style = $( 'input[name=demo_style]:checked' ).val();

			// loop through each selected item
			$.each( $to_process, function(key, item) {
				// wait until the previous item is completed
				dfd = dfd.then(function() {
					$(item).attr( 'disabled', 'disabled' );

					// waiting
					var $spinner = $(item).siblings( '.spinner' )
					$spinner.addClass( 'is-active' );

					// files to process
					var files = $(item).data( 'files' );
					files = files.split( ',' );

					// import_key of current item
					var import_key = $(item).val();

					// label for manip
					var $label = $(item).parent( 'label' );

					// add our current data
					args.files = files;
					args.import_key = import_key;
					args.style = style;

					return $.ajax({
						type: 'POST',
						url: ajaxurl, 
						data: args, 
						dataType: 'json',
						success: function(response) {
							$spinner.removeClass( 'is-active' );
							$(item).attr( 'disabled', false );

							if ( 'process' == process_action ) {
								if ( response.success ) {
									$(item).attr( 'checked', false );
									$label.addClass( 'previously-imported' );
								} else {
									$label.addClass( 'failed' );
								}
							} else {
								if ( response.success ) {
									$(item).attr( 'checked', false );
									$label.removeClass( 'previously-imported' );
									$label.children( '.previously-imported' ).hide();
								} else {
									$label.addClass( 'failed' );
								}
							}
						},
						error: function(response) {
							$spinner.removeClass( 'is-active' );
							$(item).attr( 'disabled', false );
						}
					});
				});
			});
		});
	});
</script>

<style>
#marketify-oneclick-setup .spinner {
    float: none;
    display: inline-block;
    margin-top: -2px;
    vertical-align: middle;
}

#marketify-oneclick-setup li {
	margin: 0 0 0.5em;
	padding: 0;
	list-style: none;
}

.active,
.previously-imported {
	color: green;
}

.inactive,
.failed {
	color: red;
}
</style>
