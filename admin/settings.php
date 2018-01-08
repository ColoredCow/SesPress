<?php
/**
 * Main file for SesPress WP Admin Page
 *
 * @package SesPress
 */

/**
 * Function to create the settings page for our plugin
 */
function sespress_init() {
	add_options_page( 'SES Settings', 'SesPress', 'manage_options', 'sespress', 'sespress_menu_init' );
}
add_action( 'admin_menu', 'sespress_init' );

/**
 * Function to register plugin settings
 */
function sespress_register_settings() {
	add_option( 'sespress_region' );
	add_option( 'sespress_default_sender' );
	add_option( 'sespress_enable_mails' );
	add_option( 'sespress_aws_access_key_id' );
	add_option( 'sespress_aws_secret_access_key' );
	add_option( 'sespress_test_mode' );
	add_option( 'sespress_test_mode_recipient_name' );
	add_option( 'sespress_test_mode_recipient_email' );
	register_setting( 'sespress_options_group', 'sespress_region' );
	register_setting( 'sespress_options_group', 'sespress_default_sender' );
	register_setting( 'sespress_options_group', 'sespress_enable_mails' );
	register_setting( 'sespress_options_group', 'sespress_aws_access_key_id' );
	register_setting( 'sespress_options_group', 'sespress_aws_secret_access_key' );
	register_setting( 'sespress_options_group', 'sespress_test_mode' );
	register_setting( 'sespress_options_group', 'sespress_test_mode_recipient_name' );
	register_setting( 'sespress_options_group', 'sespress_test_mode_recipient_email' );
}
add_action( 'admin_init', 'sespress_register_settings' );

/**
 * Function to print plugin page
 */
function sespress_menu_init() {
?>
	<div class="wrap">
	<h2>AWS SES configurations</h2>
	<br>
	<form action="options.php" method="POST">
		<?php settings_fields( 'sespress_options_group' ); ?>
		<div>
			<label for="sespress_enable_mails">
				Enable functionality:
				<input type="checkbox" name="sespress_enable_mails" id="sespress_enable_mails" <?php echo 'on' === get_option( 'sespress_enable_mails' ) ? 'checked' : ''; ?>>
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_aws_access_key_id">
				AWS ACCESS KEY ID:
				<input type="password" name="sespress_aws_access_key_id" id="sespress_aws_access_key_id" value="<?php echo esc_attr( get_option( 'sespress_aws_access_key_id' ) ); ?>" placeholder="ACCESS_KEY_ID">
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_aws_secret_access_key">
				AWS SECRET ACCESS KEY:
				<input type="password" name="sespress_aws_secret_access_key" id="sespress_aws_secret_access_key" value="<?php echo esc_attr( get_option( 'sespress_aws_secret_access_key' ) ); ?>" placeholder="SECRET_ACCESS_KEY">
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_region">
				Select your SES Region:
				<select name="sespress_region" id="sespress_region">
					<option value="eu-west-1" <?php echo 'eu-west-1' === get_option( 'sespress_region' ) ? 'selected' : ''; ?> >EU (Ireland)</option>
					<option value="us-east-1" <?php echo 'us-east-1' === get_option( 'sespress_region' ) ? 'selected' : ''; ?> >US EAST (N.Virginia)</option>
					<option value="us-west-2" <?php echo 'us-west-2' === get_option( 'sespress_region' ) ? 'selected' : ''; ?> >US WEST (Oregon)</option>
				</select>
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_default_sender">
				Default sender (can be overridden from code):
				<input type="text" name="sespress_default_sender" id="sespress_default_sender" value="<?php echo esc_attr( get_option( 'sespress_default_sender' ) ); ?>" placeholder="admin@example.com">
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_test_mode">
				Enable Test Mode:
				<input type="checkbox" name="sespress_test_mode" id="sespress_test_mode" <?php echo 'on' === get_option( 'sespress_test_mode' ) ? 'checked' : ''; ?>>
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_test_mode_recipient_name">
				Test mode recipient (will override all recipients set from code):
				<input type="text" name="sespress_test_mode_recipient_name" id="sespress_test_mode_recipient_name" value="<?php echo esc_attr( get_option( 'sespress_test_mode_recipient_name' ) ); ?>" placeholder="Admin">
			</label>
		</div>
		<br>
		<div>
			<label for="sespress_test_mode_recipient_email">
				Test mode recipient (will override all recipients set from code):
				<input type="email" name="sespress_test_mode_recipient_email" id="sespress_test_mode_recipient_email" value="<?php echo esc_attr( get_option( 'sespress_test_mode_recipient_email' ) ); ?>" placeholder="admin@example.com">
			</label>
		</div>
		<br>
		<?php submit_button( 'Update settings' ); ?>
	</form>
	</div>
<?php
}
