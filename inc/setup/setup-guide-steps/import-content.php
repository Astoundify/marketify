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

	<div class="import-summary" style="display: none;">
		<p><strong id="import-status"><?php _e( 'Importing...', 'marketify' ); ?></strong></p>

		<p class="import-type import-type-theme-mod">
			<span class="dashicons dashicons-admin-appearance"></span>&nbsp;
			<strong class="process-type">Theme Settings:</strong>
			<span class="process-count">
				<span id="theme-mod-processed">0</span> / <span id="theme-mod-total">0</span>
			</span>
			<span id="theme-mod-spinner" class="spinner"></span>
		</p>
		<p class="import-type import-type-nav-menu">
			<span class="dashicons dashicons-menu"></span>&nbsp;
			<strong class="process-type">Navigation Menus:</strong>
			<span class="process-count">
				<span id="nav-menu-processed">0</span> / <span id="nav-menu-total">0</span>
			</span>
			<span id="nav-menu-spinner" class="spinner"></span>
		</p>
		<p class="import-type import-type-term">
			<span class="dashicons dashicons-tag"></span>&nbsp;
			<strong class="process-type">Terms:</strong>
			<span class="process-count">
				<span id="term-processed">0</span> / <span id="term-total">0</span>
			</span>
			<span id="term-spinner" class="spinner"></span>
		</p>
		<p class="import-type import-type-object">
			<span class="dashicons dashicons-admin-post"></span>&nbsp;
			<strong class="process-type">Objects:</strong>
			<span class="process-count">
				<span id="object-processed">0</span> / <span id="object-total">0</span>
			</span>
			<span id="object-spinner" class="spinner"></span>
		</p>
		<p class="import-type import-type-nav-menu-item">
			<span class="dashicons dashicons-editor-ul"></span>&nbsp;
			<strong class="process-type">Menu Items:</strong>
			<span class="process-count">
				<span id="nav-menu-item-processed">0</span> / <span id="nav-menu-item-total">0</span>
			</span>
			<span id="nav-menu-item-spinner" class="spinner"></span>
		</p>
		<p class="import-type import-type-widget">
			<span class="dashicons dashicons-welcome-widgets-menus"></span>&nbsp;
			<strong class="process-type">Widgets:</strong>
			<span class="process-count">
				<span id="widget-processed">0</span> / <span id="widget-total">0</span>
			</span>
			<span id="widget-spinner" class="spinner"></span>
		</p>
	</div>

	<div class="plugins-to-import">
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
