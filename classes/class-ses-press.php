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

	protected static function are_mails_enabled() {
		return 'on' === get_option( 'ses_press_enable_mails' );
	}

	public static function get_formatted_address( $name, $email ) {
		return $name . ' <' . $email . '>';
	}

	protected static function is_test_mode() {
		return 'on' === get_option( 'ses_press_test_mode' );
	}

	protected static function get_test_mode_recipient_name() {
		return get_option( 'ses_press_test_mode_recipient_name' );
	}

	protected static function get_test_mode_recipient_email() {
		return get_option( 'ses_press_test_mode_recipient_email' );
	}

	public function get_subject() {
		return $this->subject;
	}

	public function set_subject( $subject ) {
		if ( self::is_test_mode() ) {
			$this->subject = 'Test - ' . $subject;
			return;
		}
		$this->subject = $subject;
	}

	public function get_message( $type = 'html' ) {
		return $this->message[ $type ];
	}

	public function set_message( $message, $type = 'html' ) {
		if ( 'text' === strtolower( $type ) ) {
			$this->message['text'] = $message;
		} else {
			$this->message['html'] = $message;
		}
	}

	public function get_recipients() {
		return $this->recipients;
	}

	public function set_recipients( array $recipients ) {
		if ( self::is_test_mode() ) {
			$this->recipients = array(
				self::get_formatted_address( self::get_test_mode_recipient_name(), self::get_test_mode_recipient_email() ),
			);
			return;
		}
		$this->recipients = $recipients;
	}

	public function get_sender() {
		return $this->recipients;
	}

	public function set_sender( $sender ) {
		$this->from = $sender;
	}

	public function set_mail_template( $template_name, array $args = [] ) {

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
