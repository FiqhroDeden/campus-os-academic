<?php
namespace CampusOS\Core\Security;

if ( ! defined( 'ABSPATH' ) ) exit;

class Activity_Log {

    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'campusos_activity_log';
    }

    public function init() {
        $this->maybe_create_table();

        // Hooks
        add_action( 'save_post', [ $this, 'log_save_post' ], 10, 3 );
        add_action( 'delete_post', [ $this, 'log_delete_post' ] );
        add_action( 'wp_login', [ $this, 'log_login' ], 10, 2 );
        add_action( 'wp_logout', [ $this, 'log_logout' ] );
        add_action( 'activated_plugin', [ $this, 'log_plugin_activated' ] );
        add_action( 'deactivated_plugin', [ $this, 'log_plugin_deactivated' ] );

        // Admin menu
        add_action( 'admin_menu', [ $this, 'add_menu' ] );

        // Cleanup cron
        add_action( 'campusos_activity_log_cleanup', [ $this, 'cleanup_old_entries' ] );
        if ( ! wp_next_scheduled( 'campusos_activity_log_cleanup' ) ) {
            wp_schedule_event( time(), 'daily', 'campusos_activity_log_cleanup' );
        }
    }

    public function maybe_create_table() {
        global $wpdb;

        if ( get_option( 'campusos_activity_log_db_version' ) === '1.0' ) {
            return;
        }

        $charset = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$this->table_name} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            ip VARCHAR(45) NOT NULL DEFAULT '',
            action VARCHAR(100) NOT NULL DEFAULT '',
            object_type VARCHAR(50) NOT NULL DEFAULT '',
            object_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
            details TEXT,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY action (action),
            KEY created_at (created_at)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        update_option( 'campusos_activity_log_db_version', '1.0' );
    }

    private function log( $action, $object_type = '', $object_id = 0, $details = '' ) {
        global $wpdb;

        $user_id = get_current_user_id();
        $ip      = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );

        $wpdb->insert( $this->table_name, [
            'user_id'     => $user_id,
            'ip'          => $ip,
            'action'      => sanitize_text_field( $action ),
            'object_type' => sanitize_text_field( $object_type ),
            'object_id'   => absint( $object_id ),
            'details'     => sanitize_textarea_field( $details ),
            'created_at'  => current_time( 'mysql' ),
        ], [ '%d', '%s', '%s', '%s', '%d', '%s', '%s' ] );
    }

    public function log_save_post( $post_id, $post, $update ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( wp_is_post_revision( $post_id ) ) return;

        $action = $update ? 'post_updated' : 'post_created';
        $this->log( $action, $post->post_type, $post_id, $post->post_title );
    }

    public function log_delete_post( $post_id ) {
        $post = get_post( $post_id );
        if ( ! $post || wp_is_post_revision( $post_id ) ) return;
        $this->log( 'post_deleted', $post->post_type, $post_id, $post->post_title );
    }

    public function log_login( $user_login, $user ) {
        $this->log( 'login', 'user', $user->ID, $user_login );
    }

    public function log_logout() {
        $user = wp_get_current_user();
        if ( $user->ID ) {
            $this->log( 'logout', 'user', $user->ID, $user->user_login );
        }
    }

    public function log_plugin_activated( $plugin ) {
        $this->log( 'plugin_activated', 'plugin', 0, $plugin );
    }

    public function log_plugin_deactivated( $plugin ) {
        $this->log( 'plugin_deactivated', 'plugin', 0, $plugin );
    }

    public function add_menu() {
        add_submenu_page(
            'campusos-academic',
            __( 'Activity Log', 'campusos-academic' ),
            __( 'Activity Log', 'campusos-academic' ),
            'manage_options',
            'campusos-activity-log',
            [ $this, 'render_page' ]
        );
    }

    public function render_page() {
        $table = new Activity_Log_Table( $this->table_name );
        $table->prepare_items();

        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Activity Log', 'campusos-academic' ) . '</h1>';
        $table->display();
        echo '</div>';
    }

    public function cleanup_old_entries() {
        global $wpdb;
        $wpdb->query( $wpdb->prepare(
            "DELETE FROM {$this->table_name} WHERE created_at < %s",
            gmdate( 'Y-m-d H:i:s', strtotime( '-90 days' ) )
        ) );
    }
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Activity_Log_Table extends \WP_List_Table {

    private $table_name;

    public function __construct( $table_name ) {
        $this->table_name = $table_name;
        parent::__construct( [
            'singular' => 'log_entry',
            'plural'   => 'log_entries',
            'ajax'     => false,
        ] );
    }

    public function get_columns() {
        return [
            'created_at'  => __( 'Waktu', 'campusos-academic' ),
            'user_id'     => __( 'User', 'campusos-academic' ),
            'ip'          => __( 'IP', 'campusos-academic' ),
            'action'      => __( 'Aksi', 'campusos-academic' ),
            'object_type' => __( 'Tipe', 'campusos-academic' ),
            'object_id'   => __( 'Object ID', 'campusos-academic' ),
            'details'     => __( 'Detail', 'campusos-academic' ),
        ];
    }

    public function get_sortable_columns() {
        return [
            'created_at' => [ 'created_at', true ],
            'action'     => [ 'action', false ],
            'user_id'    => [ 'user_id', false ],
        ];
    }

    public function prepare_items() {
        global $wpdb;

        $per_page = 30;
        $page     = $this->get_pagenum();
        $offset   = ( $page - 1 ) * $per_page;

        $orderby = isset( $_GET['orderby'] ) ? sanitize_sql_orderby( $_GET['orderby'] . ' ASC' ) : 'created_at';
        $orderby = explode( ' ', $orderby )[0]; // get column name only
        $order   = ( isset( $_GET['order'] ) && 'asc' === strtolower( $_GET['order'] ) ) ? 'ASC' : 'DESC';

        $allowed = [ 'created_at', 'action', 'user_id' ];
        if ( ! in_array( $orderby, $allowed, true ) ) {
            $orderby = 'created_at';
        }

        $total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$this->table_name}" );

        $this->items = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$this->table_name} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
            $per_page,
            $offset
        ) );

        $this->set_pagination_args( [
            'total_items' => $total,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total / $per_page ),
        ] );

        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns(),
        ];
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'user_id':
                $user = get_userdata( $item->user_id );
                return $user ? esc_html( $user->display_name ) : '#' . $item->user_id;
            case 'created_at':
                return esc_html( wp_date( 'd M Y H:i:s', strtotime( $item->created_at ) ) );
            default:
                return esc_html( $item->$column_name ?? '' );
        }
    }
}
