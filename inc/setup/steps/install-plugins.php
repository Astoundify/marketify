
					'<p>
						Before you can use Jobify you must first install WP Job Manager and WooCommerce. 
						You can read about <a
						href="http://jobify.astoundify.com/article/260-why-does-this-theme-require-plugins">why this theme requires plugins</a> in our documentation. 
					</p> 
					<p><strong>Note:</strong></strong>
					<ul>
						<li>When installing WP Job Manager and WooCommerce <strong>do not</strong> use the automatic setup and
						install the recommended pages. This will be done automatically when you import the demo XML.</li>
						<li><strong>Only free plugins/add-ons can be installed automatically.</strong> You will need to install any premium
						plugins/add-ons separately.</li>
						<li>It is recommended you install and activate any additional plugins you plan on using before importing
						any XML content.</li>
						<li><strong>Once your plugins are installed</strong> and content is imported please review all plugin
						settings pages to make sure everything has been properly set up.</li>
					</ul>' . 
					sprintf( '<a href="%1$s/images/setup/setup-plugins.gif"><img
					src="%1$s/images/setup/setup-plugins.gif" width="430" alt=""
					/></a>', get_template_directory_uri() ) . 
					'<p>' . sprintf( '<a href="%s" class="button button-primary button-large">%s</a>', admin_url( 'themes.php?page=tgmpa-install-plugins' ), __( 'Install Plugins', 'jobify' ) ) . '</p>',
