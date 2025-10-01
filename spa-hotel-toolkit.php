<?php

/**
 * Plugin Name: Spa Hotel Toolkit
 * Description: Custom functions and elementor widgets for Spa Hotel website.
 * Plugin URI:  https://themefic.com/
 * Author:      Themefic
 * Author URI:  https://themefic.com/
 * Version:     1.0.0
 * Text Domain: spa-hotel-toolkit
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly

define('SHT_HOTEL_TOOLKIT_PATH', plugin_dir_path(__FILE__));
define('SHT_HOTEL_TOOLKIT_URL', plugin_dir_url(__FILE__));
define('SHT_HOTEL_TOOLKIT_ASSETS', SHT_HOTEL_TOOLKIT_URL . 'assets/');
define('SHT_HOTEL_TOOLKIT_TEMPLATES', SHT_HOTEL_TOOLKIT_PATH . 'inc/templates/');
define('SHT_HOTEL_TOOLKIT_VERSION', '1.0.0');

// Load files
require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/class-spa-hotel-toolkit.php';
require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/metabox/spa-metabox.php';
require_once SHT_HOTEL_TOOLKIT_PATH . 'inc/spa-rator-hotel-rating.php';

// Initialize
function sht_hotel_toolkit_init()
{
    new Sht_Hotel_Toolkit();
}
add_action('plugins_loaded', 'sht_hotel_toolkit_init');

