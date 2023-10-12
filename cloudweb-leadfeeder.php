<?php
/*
* Plugin Name: cloudWEB LeadFeeder and OneSimpleApi
* Plugin URI: https://www.tudorache.me/
* Description: Connects LeadFeeder and OneSimpleApi into a custom workflow used on cloudweb.ch
* Version: 1.0.0
* Author: Adrian Emil Tudorache
* Author URI: https://www.tudorache.me
* Text Domain: cw-lf-osa
* Domain Path: /languages
* License: GPLv2 or later
*/

/**
* @package cloudWEB LeadFeeder and OneSimpleApi
* @author  Adrian Emil Tudorache
* @license GPL-2.0+
* @link    https://www.tudorache.me/
**/

namespace CW_LF_OSA;

if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

define( 'CWLFOSAVER', '1.0' );
define( 'CWLFOSAPATH', plugin_dir_path( __FILE__ ) );
define( 'CWLFOSABASE', plugin_basename( __FILE__ ) );
define( 'CWLFOSAURL', plugin_dir_url( __FILE__ ) );

load_plugin_textdomain( 'cw-lf-osa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

require_once( __DIR__ . '/inc/init.php' );
require_once( __DIR__ . '/inc/install.php' );
require_once( __DIR__ . '/inc/uninstall.php' );

function cwlfosa_activate_install() {
    cwlfosa_install();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\cwlfosa_activate_install' );

function cwlfosa_deactivate_uninstall() {
    cwlfosa_uninstall();
    flush_rewrite_rules();
}
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\cwlfosa_deactivate_uninstall' );
?>
