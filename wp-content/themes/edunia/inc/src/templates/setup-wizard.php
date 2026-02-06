<?php

defined( 'ABSPATH' ) || exit;

$license = $this->license();

?>
<div class="preload-overlay"><div class="loading"></div></div>

<div class="garudatheme-wizard-wrap">
	<div class="header-wizard">
		<div class="garudatheme-logo">
			<img src="<?php echo $this->uri;?>/assets/img/garudatheme-logo.png"/>
		</div>
		<div class="skip-button">
			<a href="<?php echo get_admin_url();?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M10 6V8H5V19H16V14H18V20C18 20.5523 17.5523 21 17 21H4C3.44772 21 3 20.5523 3 20V7C3 6.44772 3.44772 6 4 6H10ZM21 3V11H19L18.9999 6.413L11.2071 14.2071L9.79289 12.7929L17.5849 5H13V3H21Z"></path></svg></a>
		</div>
	</div>

	<div id="step-1" class="welcome-page step-wrapper active">
		<div class="step-content">
			<img class="welcome-image" src="<?php echo $this->uri;?>/assets/img/welcome.svg"/>
			<h1><?php echo __( 'Welcome', 'garudatheme' );?>.</h1>
			<p><?php echo __( 'This is the installation wizard. If you are new to using this theme, don\'t worry! It will help you to set up or do the installation in just a few easy steps.', 'garudatheme' );?></p>
			<p>
				<a class="start-installation next-step" href="#"><span><?php echo __( 'Start Installing Now', 'garudatheme' );?></span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg></a>
				<?php
				if ( !empty( $license['key'] ) ) {
				?>
				<a class="skip-installation" href="<?php echo get_admin_url();?>"><span><?php echo __( 'Skip installation Wizard', 'garudatheme' );?></span></a>
				<?php
				}
				?>
			</p>
		</div>
	</div>

	<div id="step-2" class="theme-activation step-wrapper">
		<ul class="step-progress">
			<li class="active"><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Theme Activation', 'garudatheme' );?></span></li>
			<li><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Install Plugins', 'garudatheme' );?></span></li>
			<li><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Completed', 'garudatheme' );?></span></li>
		</ul>

		<div class="step-content">
			<h1><?php echo __( 'Theme Activation', 'garudatheme' );?></h1>
			<p><?php echo __( 'First, you need to activate the theme by entering the license key below. If you don\'t have it, please log in to the member area or my account on the garudatheme website.', 'garudatheme' );?></p>
			<?php
			if ( empty( $license['key'] ) ) {
			?>
				<form class="license-verification">
					<input type="text" name="license_key" autocomplete="off" required>
					<?php wp_nonce_field( 'license_verification_action', 'license_verification_nonce' );?>
					<button type="submit">Submit</button>
				</form>
				<div class="license-notice" style="display:none;"><span class="message"></span></div>
			<?php
			} else {
			?>
				<form class="license-verification">
					<input type="text" name="license_key" value="<?php echo substr( $license['key'], 0, -12 );?>xxx" autocomplete="off" disabled="disabled" required>
					<button type="submit" disabled="disabled">Submit</button>
				</form>
				<div class="license-notice success"><span class="message"><?php echo __( 'Successfully activated', 'garudatheme' );?></span></div>
				<div class="step-navigation">
					<div class="next-step"><span><?php echo __( 'Next', 'garudatheme' );?></span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg></div>
				</div>
			<?php
			}
			?>
		</div>
	</div>

	<div id="step-3" class="install-plugins step-wrapper">
		<ul class="step-progress">
			<li><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Theme Activation', 'garudatheme' );?></span></li>
			<li class="active"><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Install Plugins', 'garudatheme' );?></span></li>
			<li><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Completed', 'garudatheme' );?></span></li>
		</ul>

		<div class="step-content">
			<h1><?php echo __( 'Install Plugins', 'garudatheme' );?></h1>
			<p><?php echo __( 'For the theme to work, please install the required plugins.', 'garudatheme' );?></p>
			<table class="required-plugins">
				<thead>
					<tr><th><?php echo __( 'Plugin', 'garudatheme' );?></th><th><?php echo __( 'Type', 'garudatheme' );?></th><th><?php echo __( 'Status', 'garudatheme' );?></th></tr>
				</thead>
				<tbody>
				<?php
				$required_plugins = $this->required_plugins();

				foreach( $required_plugins as $plugin ) {
					echo '<tr><td>' . $plugin['name'] . '</td><td>' . ( ( $plugin['required'] == true ) ? __( 'Required', 'garudatheme' ) : __( 'Optional', 'garudatheme' ) ) . '</td><td>' . ( ( is_plugin_active( $plugin['slug'] . '/' . $plugin['slug'] . '.php' ) ) ? __( 'Already active', 'garudatheme' ) : __( 'Not active', 'garudatheme' ) ) . '</td></tr>';
				}
				?>
				</tbody>
			</table>
			<p><?php echo sprintf( esc_html__( 'To install the required plugins, please select the Appearance menu then click %s.', 'garudatheme' ), '<a href="' . admin_url( 'themes.php?page=' . $this->theme['slug'] . '-required-plugins' ) . '">' . esc_html__( 'Install Plugins', 'garudatheme' ) . '</a>' );?></p>
			<div class="step-navigation">
				<div class="prev-step"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path></svg><span><?php echo __( 'Previous', 'garudatheme' );?></span></div>
				<div class="next-step"><span><?php echo __( 'Next', 'garudatheme' );?></span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path></svg></div>
			</div>
		</div>
	</div>

	<div id="step-4" class="completed step-wrapper">
		<ul class="step-progress">
			<li><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Theme Activation', 'garudatheme' );?></span></li>
			<li><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Install Plugins', 'garudatheme' );?></span></li>
			<li class="active"><span class="progress-indicator"></span><span class="progress-label"><?php echo __( 'Completed', 'garudatheme' );?></span></li>
		</ul>

		<div class="step-content">
			<h1><?php echo __( 'Completed', 'garudatheme' );?></h1>
			<p><?php echo __( 'At this point, the theme has been successfully installed. Next, you can proceed to the next step as follows:', 'garudatheme' );?></p>
			<ul class="final-steps">
				<li><a href="<?php echo ( ( is_plugin_active( 'one-click-demo-import/one-click-demo-import.php' ) ) ? admin_url( 'themes.php?page=one-click-demo-import' ) : admin_url( 'themes.php?page=' . $this->theme['slug'] . '-required-plugins' ) );?>"><?php echo __( 'Import Demo Content', 'garudatheme' );?></a>: <?php echo __( 'Import the content on the demo web, and customize the theme automatically with just a few clicks using the import demo data tool of the one click demo import plugin.', 'garudatheme' );?></li>
				<li><a href="<?php echo admin_url( 'customize.php?return=%2Fwp-admin%2Findex.php' );?>"><?php echo __( 'Customize Display', 'garudatheme' );?></a>: <?php echo __( 'Customize the appearance such as colors, fonts, sections, and many more in the theme options.', 'garudatheme' );?></li>
				<li><a href="<?php echo admin_url( 'edit.php' );?>"><?php echo __( 'Manage Posts', 'garudatheme' );?></a>: <?php echo __( 'Manage posts by creating, deleting, or editing posts and pages on your website.', 'garudatheme' );?></li>
			</ul>

			<div class="step-navigation">
				<div class="prev-step"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M7.82843 10.9999H20V12.9999H7.82843L13.1924 18.3638L11.7782 19.778L4 11.9999L11.7782 4.22168L13.1924 5.63589L7.82843 10.9999Z"></path></svg><span><?php echo __( 'Previous', 'garudatheme' );?></span></div>
				<a class="finish-step" href="<?php echo get_admin_url();?>"><span><?php echo __( 'Exit', 'garudatheme' );?></span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M5 22C4.44772 22 4 21.5523 4 21V3C4 2.44772 4.44772 2 5 2H19C19.5523 2 20 2.44772 20 3V6H18V4H6V20H18V18H20V21C20 21.5523 19.5523 22 19 22H5ZM18 16V13H11V11H18V8L23 12L18 16Z"></path></svg></a>
			</div>
		</div>
	</div>
</div>