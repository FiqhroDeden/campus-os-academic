<?php
namespace UNPATTI\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

final class Plugin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        // Will load CPTs, admin, security, etc. as we build them
    }

    private function init_hooks() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'unpatti-academic', false, dirname( plugin_basename( UNPATTI_CORE_PATH . 'unpatti-academic-core.php' ) ) . '/languages' );
    }
}
