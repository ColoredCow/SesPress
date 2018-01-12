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

	$options = array(
		'region',
		'default_sender',
		'enable_emails',
		'aws_access_key_id',
		'aws_secret_access_key',
		'test_mode',
		'test_mode_recipient_name',
		'test_mode_recipient_email',
	);
	foreach ( $options as $option ) {
		$option = 'sespress_' . $option;
		add_option( $option );
		register_setting( 'sespress_options_group', $option );
	}
}
add_action( 'admin_init', 'sespress_register_settings' );

/**
 * Function to print plugin page
 */
function sespress_menu_init() {
	include_once 'views/settings.php';
}

/**
 * Function to enqueue custom stylesheet
 */
function sespress_load_styles() {
	wp_enqueue_style( 'sespress-css', plugin_dir_url( __FILE__ ) . 'style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'style.css' ), 'all' );
}
add_action( 'admin_enqueue_scripts', 'sespress_load_styles' );

