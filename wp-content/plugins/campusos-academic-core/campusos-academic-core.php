<?php
/**
 * Plugin Name: CampusOS Academic Core
 * Description: Core data and functionality for CampusOS Academic theme
 * Version: 1.0.0
 * Author: CampusOS Team
 * Text Domain: campusos-academic
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CAMPUSOS_CORE_VERSION', '1.0.0' );
define( 'CAMPUSOS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAMPUSOS_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once CAMPUSOS_CORE_PATH . 'includes/class-plugin.php';

register_activation_hook( __FILE__, function() {
    require_once CAMPUSOS_CORE_PATH . 'includes/security/class-file-integrity.php';
    require_once CAMPUSOS_CORE_PATH . 'includes/security/class-activity-log.php';

    ( new CampusOS\Core\Security\File_Integrity() )->generate_hashes();
    ( new CampusOS\Core\Security\Activity_Log() )->maybe_create_table();
    flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function() {
    wp_clear_scheduled_hook( 'campusos_content_scan' );
    wp_clear_scheduled_hook( 'campusos_file_integrity_check' );
    wp_clear_scheduled_hook( 'campusos_activity_log_cleanup' );
} );

function campusos_core() {
    return CampusOS\Core\Plugin::instance();
}

campusos_core();
