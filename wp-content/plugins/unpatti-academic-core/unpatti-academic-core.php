<?php
/**
 * Plugin Name: UNPATTI Academic Core
 * Description: Core data and functionality for UNPATTI Academic theme
 * Version: 1.0.0
 * Author: UNPATTI Developer Team
 * Text Domain: unpatti-academic
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'UNPATTI_CORE_VERSION', '1.0.0' );
define( 'UNPATTI_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'UNPATTI_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once UNPATTI_CORE_PATH . 'includes/class-plugin.php';

function unpatti_core() {
    return UNPATTI\Core\Plugin::instance();
}

unpatti_core();
