<?php
/*
Plugin Name: Wp Ajax Actions
Plugin URI: https://github.com/4selin/wp-ajax-actions
Description:
Author: Алексей Селин
Author URI:
Version: 0.1.1
 */

// 15c1e510f282aebdff00670c96f762710e4c89a1

define( 'WPAA_VER', '0.1.1' );

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! is_admin() ) {
	return;
}

define( 'WPAA_PLUGIN_FILE', __FILE__ );

if ( ! class_exists( 'WpAjaxActions' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wpajaxactions.php';
}

function wpaa() {
	return WPAA::instance();
}

wpaa();