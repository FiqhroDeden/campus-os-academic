<?php
/**
 * Plugin Name: CampusOS Academic Core
 * Description: Core data and functionality for CampusOS Academic theme
 * Version: 1.2.2
 * Author: CampusOS Team
 * Text Domain: campusos-academic
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CAMPUSOS_CORE_VERSION', '1.2.2' );
define( 'CAMPUSOS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CAMPUSOS_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once CAMPUSOS_CORE_PATH . 'includes/class-plugin.php';

register_activation_hook( __FILE__, function() {
    require_once CAMPUSOS_CORE_PATH . 'includes/security/class-file-integrity.php';
    require_once CAMPUSOS_CORE_PATH . 'includes/security/class-activity-log.php';

    campusos_maybe_migrate_from_unpatti();

    ( new CampusOS\Core\Security\File_Integrity() )->generate_hashes();
    ( new CampusOS\Core\Security\Activity_Log() )->maybe_create_table();
    flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function() {
    wp_clear_scheduled_hook( 'campusos_content_scan' );
    wp_clear_scheduled_hook( 'campusos_file_integrity_check' );
    wp_clear_scheduled_hook( 'campusos_activity_log_cleanup' );
} );

/**
 * Migrate data from old UNPATTI Academic installation.
 * Runs on activation and admin_init (once).
 */
function campusos_maybe_migrate_from_unpatti() {
    if ( get_option( 'campusos_migrated_from_unpatti' ) ) {
        return;
    }

    // Check if old data exists
    $old_settings = get_option( 'unpatti_settings' );
    if ( false === $old_settings && ! get_option( 'unpatti_pimpinan_settings' ) ) {
        // No old data — fresh install, skip migration
        return;
    }

    global $wpdb;

    // 1. Options
    $option_map = [
        'unpatti_settings'              => 'campusos_settings',
        'unpatti_pimpinan_settings'     => 'campusos_pimpinan_settings',
        'unpatti_file_hashes'           => 'campusos_file_hashes',
        'unpatti_activity_log_db_version' => 'campusos_activity_log_db_version',
        'unpatti_last_scan'             => 'campusos_last_scan',
    ];

    foreach ( $option_map as $old_key => $new_key ) {
        $old_val = get_option( $old_key );
        if ( false !== $old_val && false === get_option( $new_key ) ) {
            update_option( $new_key, $old_val );
            delete_option( $old_key );
        }
    }

    // 2. DB table rename
    $old_table = $wpdb->prefix . 'unpatti_activity_log';
    $new_table = $wpdb->prefix . 'campusos_activity_log';
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $table_exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $old_table ) );
    if ( $table_exists ) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        $wpdb->query( "RENAME TABLE `{$old_table}` TO `{$new_table}`" );
    }

    // 3. Theme mods: copy from old theme slug to new
    $old_mods = get_option( 'theme_mods_unpatti-academic' );
    if ( false !== $old_mods && false === get_option( 'theme_mods_campusos-academic' ) ) {
        update_option( 'theme_mods_campusos-academic', $old_mods );
    }

    // 4. Post meta
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $wpdb->query(
        "UPDATE {$wpdb->postmeta} SET meta_key = '_campusos_quarantined' WHERE meta_key = '_unpatti_quarantined'"
    );

    // 5. Shortcodes in post content
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $wpdb->query(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '[unpatti_', '[campusos_') WHERE post_content LIKE '%[unpatti_%'"
    );

    // 6. Elementor data in post meta
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $wpdb->query(
        "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, 'unpatti_', 'campusos_') WHERE meta_key = '_elementor_data' AND meta_value LIKE '%unpatti_%'"
    );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $wpdb->query(
        "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, 'unpatti-', 'campusos-') WHERE meta_key = '_elementor_data' AND meta_value LIKE '%unpatti-%'"
    );

    // 7. Cron hooks: reschedule with new names
    $cron_map = [
        'unpatti_content_scan'         => 'campusos_content_scan',
        'unpatti_file_integrity_check' => 'campusos_file_integrity_check',
        'unpatti_activity_log_cleanup' => 'campusos_activity_log_cleanup',
    ];
    foreach ( $cron_map as $old_hook => $new_hook ) {
        $timestamp = wp_next_scheduled( $old_hook );
        if ( $timestamp ) {
            $recurrence = wp_get_schedule( $old_hook );
            wp_clear_scheduled_hook( $old_hook );
            if ( $recurrence ) {
                wp_schedule_event( $timestamp, $recurrence, $new_hook );
            }
        }
    }

    // 8. Active plugins list: update plugin path
    $active_plugins = get_option( 'active_plugins', [] );
    $old_plugin     = 'unpatti-academic-core/unpatti-academic-core.php';
    $new_plugin     = 'campusos-academic-core/campusos-academic-core.php';
    $key            = array_search( $old_plugin, $active_plugins, true );
    if ( false !== $key ) {
        $active_plugins[ $key ] = $new_plugin;
        update_option( 'active_plugins', $active_plugins );
    }

    // Active theme
    if ( 'unpatti-academic' === get_option( 'stylesheet' ) ) {
        update_option( 'stylesheet', 'campusos-academic' );
    }
    if ( 'unpatti-academic' === get_option( 'template' ) ) {
        update_option( 'template', 'campusos-academic' );
    }

    // 9. Delete old transients
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $wpdb->query(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_unpatti_%' OR option_name LIKE '_transient_timeout_unpatti_%'"
    );

    // 10. Mark migration complete
    update_option( 'campusos_migrated_from_unpatti', '1.0' );
}

add_action( 'admin_init', 'campusos_maybe_migrate_from_unpatti' );

function campusos_core() {
    return CampusOS\Core\Plugin::instance();
}

campusos_core();
