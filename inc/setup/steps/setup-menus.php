
					'<p>' . __( 'Make sure you create and assign your menus to the menu locations found in the theme.', 'jobify' ) . '</p>' .
					sprintf( '<a href="%1$s/images/setup/setup-menus.gif"><img src="%1$s/images/setup/setup-menus.gif" width="430" alt="" /></a>', get_template_directory_uri() ) . 
					'<p>' . sprintf( '<a href="%s" class="button button-primary button-large">%s</a>', admin_url( 'nav-menus.php' ), __( 'Manage Menus', 'jobify' ) ) . '</p>' ,
