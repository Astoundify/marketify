
					'<p>' . __( 'Installing the demo content is not required to use this theme. It is simply meant to provide a way to get a feel for the theme without having to manually set up all of your own content. <strong>If you choose not to import the demo content you need to make sure you manually create all necessary page templates for your website.</strong>', 'jobify' ). '</p>' .
					'<p>' . __( 'The Jobify theme package includes multiple demo content .XML files. This is what you will
					upload to the WordPress importer. Depending on the plugins you have activated or the intended use of your
					website you may not need to upload all .XML files.', 'jobify' ) . '</p>' . 
					sprintf( '<a href="%1$s/images/setup/setup-content.gif"><img src="%1$s/images/setup/setup-content.gif" width="430" alt=""

					/></a>', get_template_directory_uri() ) . 
					'<p>' . sprintf( '<a href="%s" class="button button-primary button-large">%s</a>', admin_url( 'import.php' ), __( 'Begin Importing Content', 'jobify' ) ) . '</p>',
