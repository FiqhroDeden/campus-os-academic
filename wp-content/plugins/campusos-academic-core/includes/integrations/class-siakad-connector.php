<?php
namespace UNPATTI\Core\Integrations;

if ( ! defined( 'ABSPATH' ) ) exit;

class SIAKAD_Connector extends API_Connector {
    public function get_name(): string {
        return 'SIAKAD';
    }

    public function get_base_url(): string {
        $settings = get_option( 'unpatti_settings', [] );
        return $settings['api_siakad_url'] ?? '';
    }

    public function get_auth_headers(): array {
        $settings = get_option( 'unpatti_settings', [] );
        $key = $settings['api_siakad_key'] ?? '';
        if ( empty( $key ) ) return [ 'Accept' => 'application/json' ];
        return [
            'Authorization' => 'Bearer ' . $key,
            'Accept'        => 'application/json',
        ];
    }
}
