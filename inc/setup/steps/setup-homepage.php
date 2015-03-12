
					'<p>' . __( 'In order to display custom widgets on your homepage you must first assign your static page in the WordPress settings. You can also set which page will display your blog posts.', 'jobify' ) . '</p>' .
					sprintf( '<a href="%1$s/images/setup/setup-reading.gif"><img src="%1$s/images/setup/setup-reading.gif" width="430" alt="" /></a>', get_template_directory_uri() ) . 
					'<p>' . sprintf( '<a href="%s" class="button button-primary button-large">%s</a>', admin_url( 'options-reading.php' ), __( 'Reading Settings', 'jobify' ) ) . '</p>', 
