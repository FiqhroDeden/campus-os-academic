<?php
namespace CampusOS\Core\Security;

if ( ! defined( 'ABSPATH' ) ) exit;

class Hardening {
    public function init() {
        $opts = get_option( 'campusos_settings', [] );

        // Disable XML-RPC
        if ( ! empty( $opts['security_xmlrpc_disabled'] ) ) {
            add_filter( 'xmlrpc_enabled', '__return_false' );
            add_filter( 'xmlrpc_methods', function() { return []; } );
        }

        // Disable user enumeration
        if ( ! empty( $opts['security_user_enum_disabled'] ) ) {
            add_filter( 'rest_endpoints', function( $endpoints ) {
                unset( $endpoints['/wp/v2/users'] );
                unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
                return $endpoints;
            } );
            add_action( 'template_redirect', function() {
                if ( is_author() && ! is_admin() ) {
                    wp_redirect( home_url(), 301 );
                    exit;
                }
            } );
        }

        // Remove WP version
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );

        // Security headers
        add_action( 'send_headers', function() {
            if ( ! is_admin() ) {
                header( 'X-Frame-Options: SAMEORIGIN' );
                header( 'X-Content-Type-Options: nosniff' );
                header( 'Referrer-Policy: strict-origin-when-cross-origin' );
                header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
            }
        } );

        // Disable file editing
        if ( ! empty( $opts['security_file_edit_disabled'] ) && ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }

        // Rate limit login
        add_filter( 'authenticate', [ $this, 'check_rate_limit' ], 30, 3 );
        add_action( 'wp_login_failed', [ $this, 'record_failed' ] );
    }

    public function check_rate_limit( $user, $username, $password ) {
        if ( empty( $username ) ) return $user;
        $ip  = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
        $key = 'campusos_login_' . md5( $ip );
        $attempts = (int) get_transient( $key );
        if ( $attempts >= 5 ) {
            return new \WP_Error(
                'too_many_attempts',
                __( 'Terlalu banyak percobaan login. Coba lagi dalam 15 menit.', 'campusos-academic' )
            );
        }
        return $user;
    }

    public function record_failed( $username ) {
        $ip  = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' );
        $key = 'campusos_login_' . md5( $ip );
        $attempts = (int) get_transient( $key );
        set_transient( $key, $attempts + 1, 15 * MINUTE_IN_SECONDS );
    }
}
