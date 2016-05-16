<?php
/**
 */
?>

<form id="marketify-oneclick-setup" action="" method="">

	<p>
		<strong><?php _e( 'Content Pack:', 'marketify' ); ?></strong>
	</p>

	<p>
		<label for="default">
			<input type="radio" value="default" name="demo_style" id="default" checked="checked">
			<?php _e( 'Default', 'marketify' ); ?>
		</label><br />
		<label for="audio">
			<input type="radio" value="audio" name="demo_style" id="audio">
			<?php _e( 'Audio Marketplace', 'marketify' ); ?>
		</label>
	</p>

	<div id="import-summary" style="display: none;">
		<p><strong id="import-status"><?php _e( 'Importing...', 'marketify' ); ?></strong></p>

		<?php foreach ( Marketify_Setup::$content_importer_strings[ 'type_labels' ] as $key => $labels ) : ?>
		<p id="import-type-<?php echo esc_attr( $key ); ?>" class="import-type">
			<span class="dashicons import-type-<?php echo esc_attr( $key ); ?>"></span>&nbsp;
			<strong class="process-type"><?php echo esc_attr( $labels[1] ); ?>:</strong>
			<span class="process-count">
				<span id="<?php echo esc_attr( $key ); ?>-processed">0</span> / <span id="<?php echo esc_attr( $key ); ?>-total">0</span>
			</span>
			<span id="<?php echo esc_attr( $key ); ?>-spinner" class="spinner"></span>
		</p>
		<?php endforeach; ?>
	</div>

	<ul id="import-errors"></ul>

	<div id="plugins-to-import">
		<p><?php _e( 'Marketify can import content for recommend plugins, but only if they are active. Please review the list of plugins below and activate any plugins you would like the content imported for.', 'marketify' ); ?></p>

		<ul>
		<?php foreach ( Marketify_Setup::get_importable_plugins() as $key => $plugin ) : ?>
		<li>
			<?php if ( $plugin[ 'condition' ] ) : ?>
				<span class="active"><span class="dashicons dashicons-yes"></span></span>
			<?php else : ?>
				<span class="inactive"><span class="dashicons dashicons-no"></span></span>
			<?php endif; ?>
			<?php echo esc_attr( $plugin[ 'label' ] ); ?>
		</li>
		<?php endforeach; ?>
		</ul>
	</div>

	<?php submit_button( __( 'Import Content', 'marketify' ), 'primary', 'import', false ); ?>
	&nbsp;
	<?php submit_button( __( 'Reset Content', 'marketify' ), 'secondary', 'reset', false ); ?>

</form>

<style>
#marketify-oneclick-setup .spinner {
    float: none;
    display: inline-block;
    margin-top: -5px;
    vertical-align: middle;
}

#plugins-to-import ul {
	padding: 0;
}

#plugins-to-import li {
	list-style: none;
	margin-left: 0;
}

.dashicons.import-type-setting:before {
	content: '\f108';
}

.dashicons.import-type-theme-mod:before {
	content: '\f100';
}

.dashicons.import-type-theme-mod:before {
	content: '\f100';
}

.dashicons.import-type-nav-menu:before {
	content: '\f214';
}

.dashicons.import-type-nav-menu-item:before {
	content: '\f203';
}

.dashicons.import-type-term:before {
	content: '\f323';
}

.dashicons.import-type-object:before {
	content: '\f109';
}

.dashicons.import-type-widget:before {
	content: '\f116';
}

.active {
	color: green;
}

.inactive {
	color: red;
}
</style>
