<?php
namespace UNPATTI\Core\Integrations;

if ( ! defined( 'ABSPATH' ) ) exit;

class API_Ajax {
    public function init() {
        add_action( 'wp_ajax_unpatti_test_api', [ $this, 'test_connection' ] );
    }

    public function test_connection() {
        check_ajax_referer( 'unpatti_test_api_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $source = sanitize_text_field( $_POST['source'] ?? '' );
        $connector = null;

        switch ( $source ) {
            case 'siakad':
                $connector = new SIAKAD_Connector();
                break;
            case 'sigap':
                $connector = new SIGAP_Connector();
                break;
            default:
                wp_send_json_error( 'Unknown source' );
        }

        $result = $connector->test_connection();
        if ( $result['success'] ) {
            wp_send_json_success( $result['message'] );
        } else {
            wp_send_json_error( $result['message'] );
        }
    }
}
