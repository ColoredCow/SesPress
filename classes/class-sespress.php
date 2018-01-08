<?php
/**
 * Main file for SesPress class
 *
 * @package SesPress
 */

define( 'CHARSET', 'UTF-8' );

require_once dirname( plugin_dir_path( __FILE__ ) ) . '/vendor/autoload.php';

use Aws\Ses\Exception\SesException;
use Aws\Ses\SesClient;

/**
 * Class SesPress
 * Primary wrapper around Amazon SESClient to instantiate and trigger mails
 */
class SesPress {
	protected $recipients, $subject, $message, $from;

	/**
	 * Method to send mail using SES
	 *
	 * @param array $args    Mail configurations.
	 * @return array
	 */
	public function send( $args ) {

		if ( ! self::are_mails_enabled() ) {
			return array(
				'success' => false,
				'data' => 'mails_disabled',
			);
		}

		$this->set_configurations( $args );

		$client = SesClient::factory(array(
			'version' => 'latest',
			'region' => get_option( 'sespress_region' ),
			'credentials' => array(
				'key'    => get_option( 'sespress_aws_access_key_id' ),
				'secret' => get_option( 'sespress_aws_secret_access_key' ),
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
				'Source' => $this->from ? $this->from : get_option( 'sespress_default_sender' ),
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
	 * Method to set configurations for current instance
	 *
	 * @param array $args    Array of configurations to set.
	 * @return void
	 */
	protected function set_configurations( $args ) {
		$this->set_subject( $args['subject'] );
		$this->set_sender( self::get_formatted_address( $args['sender']['name'], $args['sender']['email'] ) );

		$recipients = [];
		foreach ( $args['recipients'] as $recipient ) {
			array_push( $recipients, self::get_formatted_address( $recipient['name'], $recipient['email'] ) );
		}
		$this->set_recipients( $recipients );

		if ( array_key_exists( 'message', $args ) ) {
			if ( array_key_exists( 'html', $args['message'] ) ) {
				$this->set_message( $args['message']['html'] );
			}
			if ( array_key_exists( 'text', $args['message'] ) ) {
				$this->set_message( $args['message']['text'], 'text' );
			}
		}

		if ( array_key_exists( 'template', $args ) ) {
			$template = $args['template'];
			if ( array_key_exists( 'path', $args['template'] ) ) {
				$template['meta'] = array_key_exists( 'meta', $template ) ? $template['meta'] : [];
				$this->set_mail_template( $template['path'], $template['meta'] );
			}
		}
	}

	/**
	 * Static method to check if mails are enabled
	 *
	 * @return boolean
	 */
	protected static function are_mails_enabled() {
		return 'on' === get_option( 'sespress_enable_mails' );
	}

	/**
	 * Static method to format address using name and email
	 *
	 * @param string $name    Name of recipient/sender.
	 * @param string $email    Email of recipient/sender.
	 * @return string
	 */
	protected static function get_formatted_address( $name, $email ) {
		return $name . ' <' . $email . '>';
	}

	/**
	 * Static method to check if test mode is enabled
	 *
	 * @return boolean
	 */
	protected static function is_test_mode() {
		return 'on' === get_option( 'sespress_test_mode' );
	}

	/**
	 * Static method to fetch test mode recipient name configured from WP Dashboard.
	 *
	 * @return string
	 */
	protected static function get_test_mode_recipient_name() {
		return get_option( 'sespress_test_mode_recipient_name' );
	}

	/**
	 * Static method to fetch test mode recipient email configured from WP Dashboard.
	 *
	 * @return string
	 */
	protected static function get_test_mode_recipient_email() {
		return get_option( 'sespress_test_mode_recipient_email' );
	}

	/**
	 * Method to get subject of current instance
	 *
	 * @return string
	 */
	protected function get_subject() {
		return $this->subject;
	}

	/**
	 * Method to set subject of current instance
	 *
	 * @param string $subject    Subject string.
	 * @return void
	 */
	protected function set_subject( $subject ) {
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
	protected function get_message( $type = 'html' ) {
		return $this->message[ $type ];
	}

	/**
	 * Method to set message for current instance.
	 *
	 * @param string $message    Message string.
	 * @param string $type     Message type. Possible values: html (default) or type.
	 * @return void
	 */
	protected function set_message( $message, $type = 'html' ) {
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
	protected function get_recipients() {
		return $this->recipients;
	}

	/**
	 * Method to set recipients for current instance
	 *
	 * @param array $recipients    Recipients to set.
	 * @return void
	 */
	protected function set_recipients( $recipients ) {
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
	protected function get_sender() {
		return $this->recipients;
	}

	/**
	 * Method to set sender of current mail instance
	 *
	 * @param string $sender    Sender string. Should be formatted before.
	 * @return void
	 */
	protected function set_sender( $sender ) {
		$this->from = $sender;
	}

	/**
	 * Method to set mail template
	 *
	 * @param string $template_name    Path of the template.
	 * @param array  $args    Dynamic values to be inserted in the mail template.
	 * @return boolean
	 */
	protected function set_mail_template( $template_name, $args = [] ) {

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
