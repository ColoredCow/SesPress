<?php
/**
 * Plugin Name: WordPress SES
 * Plugin URI: https://coloredcow.com/wordpress
 * description: A plugin to send emails from Amazon SES
 * Version: 1.0
 * Author: ColoredCow
 * Author URI: https://coloredcow.com
 *
 * @package ColoredCow
 * @subpackage WordPress SES
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'includes/class-wordpress-ses.php';
require_once 'admin/settings.php';
