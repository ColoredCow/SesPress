<?php
/**
 * Plugin Name: SesPress
 * Plugin URI: https://coloredcow.com/wordpress
 * description: A plugin to send emails from Amazon SES
 * Version: 1.0
 * Author: ColoredCow
 * Author URI: https://coloredcow.com
 *
 * @package ColoredCow
 * @subpackage SesPress
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once 'classes/class-sespress.php';
require_once 'admin/settings.php';
