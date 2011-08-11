<?php

// Create custom plugin settings menu
add_action('admin_menu', 'ca_create_menu');
function ca_create_menu() {

	// Create a submenu page in the 'Settings' menu
	add_submenu_page( 'options-general.php', 'Customize Admin', 'Customize Admin', 'manage_options', 'customize-admin/customize-admin-options.php', 'ca_settings_page');

	// Call register settings function
	add_action( 'admin_init', 'ca_register_settings' );
}

// Register the settings
function ca_register_settings() {
	register_setting( 'customize-admin-settings-group', 'ca_logo_file' );
	register_setting( 'customize-admin-settings-group', 'ca_logo_url' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_shadow' );
	register_setting( 'customize-admin-settings-group', 'ca_remove_generator' );
}

// Include files for media uploader
function ca_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', WP_PLUGIN_URL.'/customize-admin/customize-admin.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}

function ca_admin_styles() {
	wp_enqueue_style('thickbox');
}

// Only include media uploader scripts and styles on custmize options page
if (isset($_GET['page']) && $_GET['page'] == 'customize-admin/customize-admin-options.php') {
	add_action('admin_print_scripts', 'ca_admin_scripts');
	add_action('admin_print_styles', 'ca_admin_styles');
}

function ca_settings_page() { ?>
	<div class="wrap">
	<h2><?php _e('Customize Admin Options') ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'customize-admin-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Custom Logo Link') ?></th>
				<td><label for="ca_logo_url">
					<input type="text" id="ca_logo_url" name="ca_logo_url" value="<?php echo get_option('ca_logo_url'); ?>" />
					<br /><?php _e('If not specified, clicking on the logo will return you to the homepage.') ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Custom Logo') ?></th>
				<td><label for="upload_image">
					<input id="upload_image" type="text" size="36" name="ca_logo_file" value="<?php echo get_option('ca_logo_file'); ?>" />
					<input id="upload_image_button" type="button" value="Upload Image" />
					<br /><?php _e('Enter a URL or upload an image for the image. Maximum height: 70px, width: 310px.') ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Remove Admin Menu Shadow') ?></th>
				<td><label for="ca_remove_shadow">
					<input id="ca_remove_shadow" type="checkbox" name="ca_remove_shadow" value="1" <?php checked( '1', get_option( 'ca_remove_shadow' ) ); ?> />
					<br /><?php _e('Selecting this option removes the shadow from the admin menu on the left.') ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Remove Generator Meta Tag') ?></th>
				<td><label for="ca_remove_generator">
					<input id="ca_remove_generator" type="checkbox" name="ca_remove_generator" value="1" <?php checked( '1', get_option( 'ca_remove_generator' ) ); ?> />
					<br /><?php _e('Selecting this option removes the generator meta tag from the html source.') ?>
					</label>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
	</div>
<?php } ?>