<p>When Easy Digital Downloads - Frontend Submissions is activated it will automatically create pages for your store. You need to do a few things to complete this setup:</p>

<ol>
<li><a href="<?php echo admin_url( 'nav-menus.php' ); ?>">Add the Frontend Submissino pages to your Menu</a></li>
<li><a href="<?php echo admin_url( 'admin.php?page=fes-settings' ); ?>">Configure your Frontend Submission settings</a></li>
<li><a href="<?php echo admin_url( 'post.php?post=' . EDD_FES()->helper->get_option( 'fes-submission-form', false ) ); ?>">Configure your Submission form</a></li>
<li><a href="<?php echo admin_url( 'post.php?post=' . EDD_FES()->helper->get_option( 'fes-registration-form', false ) ); ?>">Configure your Registration form</a></li>
<li><a href="<?php echo admin_url( 'post.php?post=' . EDD_FES()->helper->get_option( 'fes-profile-form', false ) ); ?>">Configure your Profile form</a></li>
</ol>

<p></p>
