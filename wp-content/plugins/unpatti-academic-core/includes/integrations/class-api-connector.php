<?php
namespace UNPATTI\Core\Integrations;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class API_Connector {
    abstract public function get_name(): string;
    abstract public function get_base_url(): string;
    abstract public function get_auth_headers(): array;

    public function fetch( string $endpoint, array $params = [] ): array {
        $url = rtrim( $this->get_base_url(), '/' ) . '/' . ltrim( $endpoint, '/' );
        if ( ! empty( $params ) ) {
            $url = add_query_arg( $params, $url );
        }

        // Check cache
        $cache_key = 'unpatti_api_' . md5( $url );
        $settings = get_option( 'unpatti_settings', [] );
        $ttl = absint( $settings['api_cache_ttl'] ?? 3600 );

        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
            return $cached;
        }

        $response = wp_remote_get( $url, [
            'headers' => $this->get_auth_headers(),
            'timeout' => 30,
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'error' => true, 'message' => $response->get_error_message() ];
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code !== 200 || ! is_array( $body ) ) {
            return [ 'error' => true, 'message' => 'HTTP ' . $code ];
        }

        set_transient( $cache_key, $body, $ttl );
        return $body;
    }

    public function test_connection(): array {
        $url = $this->get_base_url();
        if ( empty( $url ) ) {
            return [ 'success' => false, 'message' => __( 'Base URL belum dikonfigurasi.', 'unpatti-academic' ) ];
        }

        $response = wp_remote_get( $url, [
            'headers' => $this->get_auth_headers(),
            'timeout' => 10,
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'success' => false, 'message' => $response->get_error_message() ];
        }

        $code = wp_remote_retrieve_response_code( $response );
        if ( $code >= 200 && $code < 400 ) {
            return [ 'success' => true, 'message' => __( 'Terhubung.', 'unpatti-academic' ) ];
        }

        return [ 'success' => false, 'message' => 'HTTP ' . $code ];
    }
}
