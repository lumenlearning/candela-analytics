<?php

/**
 * @wordpress-plugin
 * Plugin Name: Candela Analytics
 * Description: Adds Google Analyics tracking code to the theme header. This plugin assumes that you will set CANDELA_ANALYTICS_WEB_PROPERTY_ID and CANDELA_ANALYTICS_COOKIE_DOMAIN in wp-config.php.
 * Version: 1.0.0
 * Author: Lumen Learning
 * Author URI: https://lumenlearning.com
 * Text Domain: lumen
 * License: GPLv2 or later
 * GitHub Plugin URI: https://github.com/lumenlearning/candela-analytics
 */

use Candela\Analytics;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -----------------------------------------------------------------------------
// Setup
// -----------------------------------------------------------------------------

if ( ! defined( 'CANDELA_ANALYTICS_PLUGIN_DIR' ) ) {
	define( 'CANDELA_ANALYTICS_PLUGIN_DIR', ( is_link( WP_PLUGIN_DIR . '/candela-analytics' ) ? trailingslashit( WP_PLUGIN_DIR . '/candela-analytics' ) : trailingslashit( __DIR__ ) ) );
}

if ( ! defined( 'CANDELA_ANALYTICS_USERMETA_UUID' ) ) {
	define('CANDELA_ANALYTICS_USERMETA_UUID', 'candela_analytics_uuid');
}

if ( ! defined( 'CANDELA_ANALYTICS_UUID_LENGTH' ) ) {
	define('CANDELA_ANALYTICS_UUID_LENGTH', 32);
}

// -----------------------------------------------------------------------------
// Autoloader
// -----------------------------------------------------------------------------

require CANDELA_ANALYTICS_PLUGIN_DIR . 'autoloader.php';

// Do our necessary plugin setup and add_action routines.
Analytics::init();
