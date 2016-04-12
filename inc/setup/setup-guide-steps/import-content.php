<?php
/**
 */

$plugin_files = array(
	get_template_directory() . '/inc/setup/import-content/{style}/plugin_easy_digital_downloads.json'
);

$to_import = array(
	'nav_menus' => array(
		'label' => __( 'Navigation Menus' ),
		'files' => array(
			get_template_directory() . '/inc/setup/import-content/{style}/nav_menus.json'
		)
	),
	'posts_pages' => array(
		'label' => __( 'Posts and Pages' ),
		'files' => array(
			get_template_directory() . '/inc/setup/{style}/import-content/terms.json',
			get_template_directory() . '/inc/setup/{style}/import-content/posts.json',
			get_template_directory() . '/inc/setup/{style}/import-content/pages.json'
		)
	),
	'widgets' => array(
		'label' => __( 'Widgets' ),
		'files' => array(
			get_template_directory() . '/inc/setup/{style}/import-content/widgets.json',
		)
	),
	'plugins' => array(
		'label' => __( 'Plugin Content' ),
		'files' => $plugin_files,
		'plugins' => array(
			'Easy Digital Downloads' => class_exists( 'Easy_Digital_Downloads' ),
			'Frontend Submissions' => class_exists( 'EDD_Front_End_Submissions' )
		)
	)
);
?>

<form id="marketify-oneclick-setup" action="" method="">

	<p>
		<strong><label for="demo_content"><?php _e( 'Demo Style', 'marketify' ); ?>:</label></strong>
		<select name="demo_content" id="demo_content">
			<option value="default">Default</option>
		</select>
	</p>

	<p><strong><?php _e( 'Items to Import', 'marketify' ); ?>:</strong></p>

	<ul class="import-list" style="list-style: none;">

		<?php foreach ( $to_import as $import_key => $import ) : ?>
		<li>
			<label for="<?php echo esc_attr( $import_key ); ?>">
				<input 
					type="checkbox" 
					name="to_import[]" 
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
				<?php foreach ( $import[ 'plugins' ] as $label => $is_importing ) : ?>
				<li>
					<strong><?php echo $label; ?></strong> &mdash; 
					<?php if ( $is_importing ) : ?>
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
			var $to_process = $form.find( 'input:checked' );

			var dfd = $.Deferred().resolve();

			// style to use
			var style = $( 'select[name=demo_content]' ).val();

			// loop through each selected item
			$.each( $to_process, function(key, item) {
				// wait until the previous item is completed
				dfd = dfd.then(function() {
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

					return $.post( ajaxurl, args, function(response) {
						$spinner.removeClass( 'is-active' );

						if ( 'process' == process_action ) {
							if ( response.success ) {
								$label.addClass( 'previously-imported' );
							} else {
								$label.addClass( 'failed' );
							}
						} else {
							if ( response.success ) {
								$label.removeClass( 'previously-imported' );
								$label.children( '.previously-imported' ).hide();
							} else {
								$label.addClass( 'failed' );
							}
						}
					}, 'json' );
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
