<?php
/**
 * Main file for Ses_Press class
 *
 * @package ColoredCow
 * @subpackage SES
 */

define( 'CHARSET', 'UTF-8' );

require_once dirname( plugin_dir_path( __FILE__ ) ) . '/vendor/autoload.php';

use Aws\Ses\Exception\SesException;
use Aws\Ses\SesClient;

/**
 * Class Ses_Press
 * Primary wrapper around Amazon SESClient to instantiate and trigger mails
 */
class Ses_Press {
	protected $recipients, $subject, $message, $from;

	/**
	 * Method to send mail using SES
	 *
	 * @return array
	 */
	public function send_mail() {

		if ( ! self::are_mails_enabled() ) {
			return array(
				'success' => false,
				'data' => 'mails_disabled',
			);
		}

		$client = SesClient::factory(array(
			'version' => 'latest',
			'region' => get_option( 'ses_press_region' ),
			'credentials' => array(
				'key'    => get_option( 'ses_press_aws_access_key_id' ),
				'secret' => get_option( 'ses_press_aws_secret_access_key' ),
			),
		));

		try {
			$result = $client->sendEmail([
				'Destination' => [
					'ToAddresses' => $this->recipients,
				],
				'Message' => [
					'Body' => [
						'Html' => [
							'Charset' => CHARSET,
							'Data' => ( isset( $this->message['html'] ) && $this->message['html'] ) ? $this->message['html'] : '',
						],
						'Text' => [
							'Charset' => CHARSET,
							'Data' => ( isset( $this->message['text'] ) && $this->message['text'] ) ? $this->message['text'] : '',
						],
					],
					'Subject' => [
						'Charset' => CHARSET,
						'Data' => $this->subject,
					],
				],
				'Source' => $this->from ? $this->from : get_option( 'ses_press_default_sender' ),
			]);
			$message_id = $result->get( 'MessageId' );
			return array(
				'success' => true,
				'data' => $message_id,
			);
		} catch ( SesException $error ) {
			return array(
				'success' => false,
				'data' => $error->getAwsErrorMessage(),
			);
		} catch ( Exception $error ) {
			return array(
				'success' => false,
				'data' => $error->getMessage(),
			);
		}
	}

	/**
	 * Static method to check if mails are enabled
	 *
	 * @return boolean
	 */
	protected static function are_mails_enabled() {
		return 'on' === get_option( 'ses_press_enable_mails' );
	}

	/**
	 * Static method to format address using name and email
	 *
	 * @param string $name    Name of recipient/sender.
	 * @param string $email    Email of recipient/sender.
	 * @return string
	 */
	public static function get_formatted_address( $name, $email ) {
		return $name . ' <' . $email . '>';
	}

	/**
	 * Static method to check if test mode is enabled
	 *
	 * @return boolean
	 */
	protected static function is_test_mode() {
		return 'on' === get_option( 'ses_press_test_mode' );
	}

	/**
	 * Static method to fetch test mode recipient name configured from WP Dashboard.
	 *
	 * @return string
	 */
	protected static function get_test_mode_recipient_name() {
		return get_option( 'ses_press_test_mode_recipient_name' );
	}

	/**
	 * Static method to fetch test mode recipient email configured from WP Dashboard.
	 *
	 * @return string
	 */
	protected static function get_test_mode_recipient_email() {
		return get_option( 'ses_press_test_mode_recipient_email' );
	}

	/**
	 * Method to get subject of current instance
	 *
	 * @return string
	 */
	public function get_subject() {
		return $this->subject;
	}

	/**
	 * Method to set subject of current instance
	 *
	 * @param string $subject    Subject string.
	 * @return void
	 */
	public function set_subject( $subject ) {
		if ( self::is_test_mode() ) {
			$this->subject = 'Test - ' . $subject;
			return;
		}
		$this->subject = $subject;
	}

	/**
	 * Method to get message body of current instance based on message type.
	 *
	 * @param string $type    Type of message to return Possible values: html (default) or text.
	 * @return string
	 */
	public function get_message( $type = 'html' ) {
		return $this->message[ $type ];
	}

	/**
	 * Method to set message for current instance.
	 *
	 * @param string $message    Message string.
	 * @param string $type     Message type. Possible values: html (default) or type.
	 * @return void
	 */
	public function set_message( $message, $type = 'html' ) {
		if ( 'text' === strtolower( $type ) ) {
			$this->message['text'] = $message;
		} else {
			$this->message['html'] = $message;
		}
	}

	/**
	 * Method to get an array of recipients set for current instance
	 *
	 * @return array
	 */
	public function get_recipients() {
		return $this->recipients;
	}

	/**
	 * Method to set recipients for current instance
	 *
	 * @param array $recipients    Recipients to set.
	 * @return void
	 */
	public function set_recipients( $recipients ) {
		if ( self::is_test_mode() ) {
			$this->recipients = array(
				self::get_formatted_address( self::get_test_mode_recipient_name(), self::get_test_mode_recipient_email() ),
			);
			return;
		}
		$this->recipients = $recipients;
	}

	/**
	 * Method to set sender of current instance
	 *
	 * @return array
	 */
	public function get_sender() {
		return $this->recipients;
	}

	/**
	 * Method to set sender of current mail instance
	 *
	 * @param string $sender    Sender string. Should be formatted before.
	 * @return void
	 */
	public function set_sender( $sender ) {
		$this->from = $sender;
	}

	/**
	 * Method to set mail template
	 *
	 * @param string $template_name    Path of the template.
	 * @param array  $args    Dynamic values to be inserted in the mail template.
	 * @return boolean
	 */
	public function set_mail_template( $template_name, $args = [] ) {

		$template_path = locate_template( array( $template_name ), false, true );
		if ( ! $template_path ) {
			return false;
		}

		$template = array();
		foreach ( $args as $variable => $value ) {
			$variable = preg_replace( '/\s+/', '_', trim( $variable ) );
			$template[ $variable ] = $value;
		}

		try {
			ob_start();
			require_once( $template_path );
			$this->message['html'] = ob_get_contents();
			ob_end_clean();
			return true;
		} catch ( Exception $error ) {
			return $error->getMessage();
		}
	}
}
