<?php
namespace UNPATTI\Core\SSO;

if ( ! defined( 'ABSPATH' ) ) exit;

class SSO_Auth {
    private $base_url;
    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function init() {
        $settings = get_option( 'unpatti_settings', [] );
        if ( empty( $settings['sso_enabled'] ) ) return;

        $this->base_url      = rtrim( $settings['sso_base_url'] ?? 'https://sso.unpatti.ac.id', '/' );
        $this->client_id     = $settings['sso_client_id'] ?? '';
        $this->client_secret = $settings['sso_client_secret'] ?? '';
        $this->redirect_uri  = admin_url( 'admin-ajax.php?action=unpatti_sso_callback' );

        if ( empty( $this->client_id ) || empty( $this->client_secret ) ) return;

        add_action( 'login_init', [ $this, 'redirect_to_sso' ] );
        add_action( 'wp_ajax_nopriv_unpatti_sso_callback', [ $this, 'handle_callback' ] );
        add_action( 'wp_ajax_unpatti_sso_callback', [ $this, 'handle_callback' ] );
        add_action( 'wp_logout', [ $this, 'sso_logout' ] );
    }

    public function redirect_to_sso() {
        // Allow fallback login
        if ( isset( $_GET['fallback'] ) && $_GET['fallback'] === '1' ) {
            $settings = get_option( 'unpatti_settings', [] );
            $fallback_user = $settings['sso_fallback_admin'] ?? '';
            if ( $fallback_user && isset( $_POST['log'] ) && $_POST['log'] === $fallback_user ) {
                return; // Allow normal WP login for fallback admin
            }
            if ( $fallback_user ) return; // Show login form for fallback
        }
        if ( isset( $_POST['log'] ) ) return; // Allow POST for fallback

        $state = wp_generate_password( 40, false );
        set_transient( 'unpatti_sso_state_' . $state, 1, 10 * MINUTE_IN_SECONDS );

        $url = $this->base_url . '/oauth/authorize?' . http_build_query( [
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'response_type' => 'code',
            'scope'         => '',
            'state'         => $state,
        ] );

        wp_redirect( $url );
        exit;
    }

    public function handle_callback() {
        $state = sanitize_text_field( $_GET['state'] ?? '' );
        $code  = sanitize_text_field( $_GET['code'] ?? '' );

        if ( ! $state || ! get_transient( 'unpatti_sso_state_' . $state ) ) {
            wp_die( esc_html__( 'Invalid state parameter.', 'unpatti-academic' ), 403 );
        }
        delete_transient( 'unpatti_sso_state_' . $state );

        if ( ! $code ) {
            wp_die( esc_html__( 'No authorization code.', 'unpatti-academic' ), 400 );
        }

        // Exchange code for token
        $token_response = wp_remote_post( $this->base_url . '/oauth/token', [
            'body' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->redirect_uri,
                'code'          => $code,
            ],
            'timeout' => 30,
            'sslverify' => true,
        ] );

        if ( is_wp_error( $token_response ) ) {
            wp_die( esc_html__( 'SSO: Gagal mendapatkan token.', 'unpatti-academic' ), 500 );
        }

        $token_body = wp_remote_retrieve_body( $token_response );
        $token_data = json_decode( $token_body, true );
        $access_token = $token_data['access_token'] ?? null;

        if ( ! $access_token ) {
            wp_die( esc_html__( 'SSO: Access token tidak ditemukan.', 'unpatti-academic' ), 500 );
        }

        // Get user info
        $user_response = wp_remote_get(
            add_query_arg( 'client_id', $this->client_id, $this->base_url . '/api/me/roles' ),
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                    'Accept'        => 'application/json',
                ],
                'timeout' => 30,
            ]
        );

        if ( is_wp_error( $user_response ) ) {
            wp_die( esc_html__( 'SSO: Gagal mendapatkan data user.', 'unpatti-academic' ), 500 );
        }

        $user_info = json_decode( wp_remote_retrieve_body( $user_response ), true );

        if ( empty( $user_info['user_id'] ) || empty( $user_info['email'] ) ) {
            wp_die( esc_html__( 'SSO: Data user tidak valid.', 'unpatti-academic' ), 500 );
        }

        $wp_user = $this->find_or_create_user( $user_info );
        if ( is_wp_error( $wp_user ) ) {
            wp_die( esc_html( $wp_user->get_error_message() ), 500 );
        }

        // Store encrypted token
        update_user_meta( $wp_user->ID, '_sso_access_token', $this->encrypt( $access_token ) );
        update_user_meta( $wp_user->ID, '_sso_user_id', sanitize_text_field( $user_info['user_id'] ) );

        wp_set_auth_cookie( $wp_user->ID, true );
        wp_set_current_user( $wp_user->ID );

        wp_safe_redirect( admin_url() );
        exit;
    }

    private function find_or_create_user( array $info ) {
        $sso_uid = sanitize_text_field( $info['user_id'] );
        $email   = sanitize_email( $info['email'] );
        $name    = sanitize_text_field( $info['name'] ?? '' );
        $roles   = $info['roles'] ?? [];

        // Find by SSO user_id
        $users = get_users( [ 'meta_key' => '_sso_user_id', 'meta_value' => $sso_uid, 'number' => 1 ] );
        if ( ! empty( $users ) ) {
            $user = $users[0];
            wp_update_user( [ 'ID' => $user->ID, 'display_name' => $name ] );
            $this->apply_role( $user, $roles );
            return $user;
        }

        // Find by email
        $user = get_user_by( 'email', $email );
        if ( $user ) {
            update_user_meta( $user->ID, '_sso_user_id', $sso_uid );
            wp_update_user( [ 'ID' => $user->ID, 'display_name' => $name ] );
            $this->apply_role( $user, $roles );
            return $user;
        }

        // Create new
        $username = sanitize_user( strtolower( explode( '@', $email )[0] ) );
        $base = $username;
        $i = 1;
        while ( username_exists( $username ) ) {
            $username = $base . $i++;
        }

        $user_id = wp_insert_user( [
            'user_login'   => $username,
            'user_email'   => $email,
            'display_name' => $name,
            'user_pass'    => wp_generate_password( 32, true, true ),
            'role'         => 'subscriber',
        ] );

        if ( is_wp_error( $user_id ) ) return $user_id;

        update_user_meta( $user_id, '_sso_user_id', $sso_uid );
        $user = get_user_by( 'id', $user_id );
        $this->apply_role( $user, $roles );
        return $user;
    }

    private function apply_role( \WP_User $user, array $sso_roles ) {
        $mapping = $this->get_role_mapping();
        foreach ( $sso_roles as $role ) {
            if ( isset( $mapping[ $role ] ) ) {
                $user->set_role( $mapping[ $role ] );
                return;
            }
        }
        $user->set_role( 'editor' );
    }

    private function get_role_mapping(): array {
        $settings = get_option( 'unpatti_settings', [] );
        $raw = $settings['sso_role_mapping'] ?? "Admin=administrator\nEditor=editor";
        $map = [];
        foreach ( explode( "\n", $raw ) as $line ) {
            $line = trim( $line );
            if ( strpos( $line, '=' ) !== false ) {
                list( $sso, $wp ) = array_map( 'trim', explode( '=', $line, 2 ) );
                $map[ $sso ] = $wp;
            }
        }
        return $map;
    }

    public function sso_logout() {
        $user_id = get_current_user_id();
        $encrypted = get_user_meta( $user_id, '_sso_access_token', true );
        if ( $encrypted ) {
            $token = $this->decrypt( $encrypted );
            wp_remote_get( $this->base_url . '/api/logmeout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'timeout' => 10,
            ] );
            delete_user_meta( $user_id, '_sso_access_token' );
        }
    }

    private function encrypt( string $data ): string {
        $key = wp_salt( 'auth' );
        $iv  = substr( md5( $key ), 0, 16 );
        return base64_encode( openssl_encrypt( $data, 'AES-256-CBC', $key, 0, $iv ) );
    }

    private function decrypt( string $data ): string {
        $key = wp_salt( 'auth' );
        $iv  = substr( md5( $key ), 0, 16 );
        return openssl_decrypt( base64_decode( $data ), 'AES-256-CBC', $key, 0, $iv );
    }
}
