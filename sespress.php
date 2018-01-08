<?php

/**
 * Plugin Name: SesPress
 * Plugin URI: https://coloredcow.com/wordpress
 * description: A plugin to send emails from Amazon SES
 * Version: 1.0
 * Author: ColoredCow
 * Author URI: https://coloredcow.com
 * License:           GPL-3.0+
 * License URI:    http://gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       sespress
 *
 * @since 0.1
 *
 * @package SesPress
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'classes/class-sespress.php';
require_once 'admin/admin.php';
