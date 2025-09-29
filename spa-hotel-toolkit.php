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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'SPA_HOTEL_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPA_HOTEL_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
define( 'SPA_HOTEL_TOOLKIT_ASSETS', SPA_HOTEL_TOOLKIT_URL . 'assets/' );
define( 'SPA_HOTEL_TOOLKIT_TEMPLATES', SPA_HOTEL_TOOLKIT_PATH . 'inc/templates/' );
define( 'SPA_HOTEL_TOOLKIT_VERSION', '1.0.0' );

// Load files
require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/class-spa-hotel-toolkit.php';
require_once SPA_HOTEL_TOOLKIT_PATH . 'inc/metabox/spa-metabox.php';

// Initialize
function spa_hotel_toolkit_init() {
    new Spa_Hotel_Toolkit();
}
add_action( 'plugins_loaded', 'spa_hotel_toolkit_init' );
