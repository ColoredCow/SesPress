<?php
/**
 * Sespress admin settings page view
 *
 * @since 0.1
 * @package @Sespress
 */

?>

<div class="wrap sespress-settings">
	<h2 class="header">AWS SES configurations</h2>
	<form action="options.php" method="POST">
		<?php settings_fields( 'sespress_options_group' ); ?>
		<label for="sespress_enable_emails">
			Enable functionality:
			<input type="checkbox" name="sespress_enable_emails" id="sespress_enable_emails" <?php echo 'on' === get_option( 'sespress_enable_emails' ) ? 'checked' : ''; ?>>
		</label>
		<label for="sespress_aws_access_key_id">
			AWS ACCESS KEY ID:
			<input type="password" name="sespress_aws_access_key_id" id="sespress_aws_access_key_id" value="<?php echo esc_attr( get_option( 'sespress_aws_access_key_id' ) ); ?>" placeholder="ACCESS_KEY_ID">
		</label>
		<label for="sespress_aws_secret_access_key">
			AWS SECRET ACCESS KEY:
			<input type="password" name="sespress_aws_secret_access_key" id="sespress_aws_secret_access_key" value="<?php echo esc_attr( get_option( 'sespress_aws_secret_access_key' ) ); ?>" placeholder="SECRET_ACCESS_KEY">
		</label>
		<label for="sespress_region">
			Select your SES Region:
			<select name="sespress_region" id="sespress_region">
				<option value="eu-west-1" <?php echo 'eu-west-1' === get_option( 'sespress_region' ) ? 'selected' : ''; ?> >EU (Ireland)</option>
				<option value="us-east-1" <?php echo 'us-east-1' === get_option( 'sespress_region' ) ? 'selected' : ''; ?> >US EAST (N.Virginia)</option>
				<option value="us-west-2" <?php echo 'us-west-2' === get_option( 'sespress_region' ) ? 'selected' : ''; ?> >US WEST (Oregon)</option>
			</select>
		</label>
		<label for="sespress_default_sender">
			Default sender (can be overridden from code):
			<input type="text" name="sespress_default_sender" id="sespress_default_sender" value="<?php echo esc_attr( get_option( 'sespress_default_sender' ) ); ?>" placeholder="admin@example.com">
		</label>
		<label for="sespress_test_mode">
			Enable Test Mode:
			<input type="checkbox" name="sespress_test_mode" id="sespress_test_mode" <?php echo 'on' === get_option( 'sespress_test_mode' ) ? 'checked' : ''; ?>>
		</label>
		<label for="sespress_test_mode_recipient_name">
			Test mode recipient (will override all recipients set from code):
			<input type="text" name="sespress_test_mode_recipient_name" id="sespress_test_mode_recipient_name" value="<?php echo esc_attr( get_option( 'sespress_test_mode_recipient_name' ) ); ?>" placeholder="Admin">
		</label>
		<label for="sespress_test_mode_recipient_email">
			Test mode recipient (will override all recipients set from code):
			<input type="email" name="sespress_test_mode_recipient_email" id="sespress_test_mode_recipient_email" value="<?php echo esc_attr( get_option( 'sespress_test_mode_recipient_email' ) ); ?>" placeholder="admin@example.com">
		</label>
		<?php submit_button( 'Update settings' ); ?>
	</form>
</div>
