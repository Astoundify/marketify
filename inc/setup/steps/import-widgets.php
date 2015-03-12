
					'<p>' . __( 'Importing the demo widgets is not required to use this theme. It simply allows you to quickly match the same settings found on our theme demo. If you do not import the widgets you can manually manage your widgets just like a standard WordPress widget area.', 'jobify' ). '</p>' .
					sprintf( '<a href="%1$s/images/setup/setup-widgets.gif"><img src="%1$s/images/setup/setup-widgets.gif" width="430" alt="" /></a>', get_template_directory_uri() ) . 
					'<p>' . sprintf( '<a href="%s" class="button button-primary button-large">%s</a>', admin_url(
					'tools.php?page=widget-importer-exporter' ), __( 'Begin Importing Widgets', 'jobify' ) ) . '</p>',
