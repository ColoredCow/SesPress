<?php
/**
 * Main file for CCSES WP Admin Page
 *
 * @package ColoredCow
 * @subpackage SES
 */

/**
 * Function to create the settings page for our plugin
 */
function cc_ses_init() {
	add_options_page( 'ColoredCow SES Settings', 'ColoredCow SES', 'manage_options', 'cc-ses', 'cc_ses_menu_init' );
}
add_action( 'admin_menu', 'cc_ses_init' );

/**
 * Function to register plugin settings
 */
function ccses_register_settings() {
	add_option( 'ccses_region' );
	add_option( 'ccses_default_sender' );
	add_option( 'ccses_enable_mails' );
	add_option( 'ccses_aws_access_key_id' );
	add_option( 'ccses_aws_secret_access_key' );
	add_option( 'ccses_test_mode' );
	add_option( 'ccses_test_mode_recipient_name' );
	add_option( 'ccses_test_mode_recipient_email' );
	register_setting( 'ccses_options_group', 'ccses_region' );
	register_setting( 'ccses_options_group', 'ccses_default_sender' );
	register_setting( 'ccses_options_group', 'ccses_enable_mails' );
	register_setting( 'ccses_options_group', 'ccses_aws_access_key_id' );
	register_setting( 'ccses_options_group', 'ccses_aws_secret_access_key' );
	register_setting( 'ccses_options_group', 'ccses_test_mode' );
	register_setting( 'ccses_options_group', 'ccses_test_mode_recipient_name' );
	register_setting( 'ccses_options_group', 'ccses_test_mode_recipient_email' );
}
add_action( 'admin_init', 'ccses_register_settings' );

/**
 * Function to print plugin page
 */
function cc_ses_menu_init() {
?>
	<div class="wrap">
	<h2>SES configurations</h2>
	<br>
	<form action="options.php" method="POST">
		<?php settings_fields( 'ccses_options_group' ); ?>
		<div>
			<label for="ccses_enable_mails">
				Enable functionality:
				<input type="checkbox" name="ccses_enable_mails" id="ccses_enable_mails" <?php echo 'on' === get_option( 'ccses_enable_mails' ) ? 'checked' : ''; ?>>
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_aws_access_key_id">
				AWS ACCESS KEY ID:
				<input type="password" name="ccses_aws_access_key_id" id="ccses_aws_access_key_id" value="<?php echo esc_attr( get_option( 'ccses_aws_access_key_id' ) ); ?>" placeholder="ACCESS_KEY_ID">
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_aws_secret_access_key">
				AWS SECRET ACCESS KEY:
				<input type="password" name="ccses_aws_secret_access_key" id="ccses_aws_secret_access_key" value="<?php echo esc_attr( get_option( 'ccses_aws_secret_access_key' ) ); ?>" placeholder="SECRET_ACCESS_KEY">
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_region">
				Select your SES Region:
				<select name="ccses_region" id="ccses_region">
					<option value="eu-west-1" <?php echo 'eu-west-1' === get_option( 'ccses_region' ) ? 'selected' : ''; ?> >EU (Ireland)</option>
					<option value="us-east-1" <?php echo 'us-east-1' === get_option( 'ccses_region' ) ? 'selected' : ''; ?> >US EAST (N.Virginia)</option>
					<option value="us-west-2" <?php echo 'us-west-2' === get_option( 'ccses_region' ) ? 'selected' : ''; ?> >US WEST (Oregon)</option>
				</select>
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_default_sender">
				Default sender (can be overridden from code):
				<input type="text" name="ccses_default_sender" id="ccses_default_sender" value="<?php echo esc_attr( get_option( 'ccses_default_sender' ) ); ?>" placeholder="admin@example.com">
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_test_mode">
				Enable Test Mode:
				<input type="checkbox" name="ccses_test_mode" id="ccses_test_mode" <?php echo 'on' === get_option( 'ccses_test_mode' ) ? 'checked' : ''; ?>>
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_test_mode_recipient_name">
				Test mode recipient (will override all recipients set from code):
				<input type="text" name="ccses_test_mode_recipient_name" id="ccses_test_mode_recipient_name" value="<?php echo esc_attr( get_option( 'ccses_test_mode_recipient_name' ) ); ?>" placeholder="Admin">
			</label>
		</div>
		<br>
		<div>
			<label for="ccses_test_mode_recipient_email">
				Test mode recipient (will override all recipients set from code):
				<input type="email" name="ccses_test_mode_recipient_email" id="ccses_test_mode_recipient_email" value="<?php echo esc_attr( get_option( 'ccses_test_mode_recipient_email' ) ); ?>" placeholder="admin@example.com">
			</label>
		</div>
		<br>
		<?php submit_button( 'Update settings' ); ?>
	</form>
	</div>
<?php
}
