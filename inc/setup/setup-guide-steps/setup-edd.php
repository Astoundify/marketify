<p><?php printf( __( 'Visiting <a href="%s"><strong>Downloads &rarr; Settings</strong></a> allows you to configure and customize the way Easy Digital Downloads behaves. Setup tax options, email settings, and more.', 'marketify' ), admin_url( 'edit.php?post_type=download&page=edd-settings' ) ); ?></p>

<ol>
<li><a href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-settings' ); ?>"><?php _e( 'Configure your Easy Digital Downloads settings', 'marketify' ); ?></a></li>
<li><a href="<?php echo admin_url( 'post-new.php?post_type=download' ); ?>"><?php _e( 'Add your first download', 'marketify' ); ?></a></li>
</ol>
