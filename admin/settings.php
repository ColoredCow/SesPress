<?php
/**
 * Main file for SesPress WP Admin Page
 *
 * @package ColoredCow
 * @subpackage SES
 */

/**
 * Function to create the settings page for our plugin
 */
function ses_press_init() {
	add_options_page( 'SES Settings', 'SesPress', 'manage_options', 'ses-press', 'ses_press_menu_init' );
}
add_action( 'admin_menu', 'ses_press_init' );

/**
 * Function to register plugin settings
 */
function ses_press_register_settings() {
	add_option( 'ses_press_region' );
	add_option( 'ses_press_default_sender' );
	add_option( 'ses_press_enable_mails' );
	add_option( 'ses_press_aws_access_key_id' );
	add_option( 'ses_press_aws_secret_access_key' );
	add_option( 'ses_press_test_mode' );
	add_option( 'ses_press_test_mode_recipient_name' );
	add_option( 'ses_press_test_mode_recipient_email' );
	register_setting( 'ses_press_options_group', 'ses_press_region' );
	register_setting( 'ses_press_options_group', 'ses_press_default_sender' );
	register_setting( 'ses_press_options_group', 'ses_press_enable_mails' );
	register_setting( 'ses_press_options_group', 'ses_press_aws_access_key_id' );
	register_setting( 'ses_press_options_group', 'ses_press_aws_secret_access_key' );
	register_setting( 'ses_press_options_group', 'ses_press_test_mode' );
	register_setting( 'ses_press_options_group', 'ses_press_test_mode_recipient_name' );
	register_setting( 'ses_press_options_group', 'ses_press_test_mode_recipient_email' );
}
add_action( 'admin_init', 'ses_press_register_settings' );

/**
 * Function to print plugin page
 */
function ses_press_menu_init() {
?>
	<div class="wrap">
	<h2>AWS SES configurations</h2>
	<br>
	<form action="options.php" method="POST">
		<?php settings_fields( 'ses_press_options_group' ); ?>
		<div>
			<label for="ses_press_enable_mails">
				Enable functionality:
				<input type="checkbox" name="ses_press_enable_mails" id="ses_press_enable_mails" <?php echo 'on' === get_option( 'ses_press_enable_mails' ) ? 'checked' : ''; ?>>
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_aws_access_key_id">
				AWS ACCESS KEY ID:
				<input type="password" name="ses_press_aws_access_key_id" id="ses_press_aws_access_key_id" value="<?php echo esc_attr( get_option( 'ses_press_aws_access_key_id' ) ); ?>" placeholder="ACCESS_KEY_ID">
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_aws_secret_access_key">
				AWS SECRET ACCESS KEY:
				<input type="password" name="ses_press_aws_secret_access_key" id="ses_press_aws_secret_access_key" value="<?php echo esc_attr( get_option( 'ses_press_aws_secret_access_key' ) ); ?>" placeholder="SECRET_ACCESS_KEY">
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_region">
				Select your SES Region:
				<select name="ses_press_region" id="ses_press_region">
					<option value="eu-west-1" <?php echo 'eu-west-1' === get_option( 'ses_press_region' ) ? 'selected' : ''; ?> >EU (Ireland)</option>
					<option value="us-east-1" <?php echo 'us-east-1' === get_option( 'ses_press_region' ) ? 'selected' : ''; ?> >US EAST (N.Virginia)</option>
					<option value="us-west-2" <?php echo 'us-west-2' === get_option( 'ses_press_region' ) ? 'selected' : ''; ?> >US WEST (Oregon)</option>
				</select>
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_default_sender">
				Default sender (can be overridden from code):
				<input type="text" name="ses_press_default_sender" id="ses_press_default_sender" value="<?php echo esc_attr( get_option( 'ses_press_default_sender' ) ); ?>" placeholder="admin@example.com">
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_test_mode">
				Enable Test Mode:
				<input type="checkbox" name="ses_press_test_mode" id="ses_press_test_mode" <?php echo 'on' === get_option( 'ses_press_test_mode' ) ? 'checked' : ''; ?>>
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_test_mode_recipient_name">
				Test mode recipient (will override all recipients set from code):
				<input type="text" name="ses_press_test_mode_recipient_name" id="ses_press_test_mode_recipient_name" value="<?php echo esc_attr( get_option( 'ses_press_test_mode_recipient_name' ) ); ?>" placeholder="Admin">
			</label>
		</div>
		<br>
		<div>
			<label for="ses_press_test_mode_recipient_email">
				Test mode recipient (will override all recipients set from code):
				<input type="email" name="ses_press_test_mode_recipient_email" id="ses_press_test_mode_recipient_email" value="<?php echo esc_attr( get_option( 'ses_press_test_mode_recipient_email' ) ); ?>" placeholder="admin@example.com">
			</label>
		</div>
		<br>
		<?php submit_button( 'Update settings' ); ?>
	</form>
	</div>
<?php
}
