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

register_activation_hook( __FILE__, function() {
    require_once UNPATTI_CORE_PATH . 'includes/security/class-file-integrity.php';
    require_once UNPATTI_CORE_PATH . 'includes/security/class-activity-log.php';

    ( new UNPATTI\Core\Security\File_Integrity() )->generate_hashes();
    ( new UNPATTI\Core\Security\Activity_Log() )->maybe_create_table();
} );

register_deactivation_hook( __FILE__, function() {
    wp_clear_scheduled_hook( 'unpatti_content_scan' );
    wp_clear_scheduled_hook( 'unpatti_file_integrity_check' );
    wp_clear_scheduled_hook( 'unpatti_activity_log_cleanup' );
} );

function unpatti_core() {
    return UNPATTI\Core\Plugin::instance();
}

unpatti_core();
