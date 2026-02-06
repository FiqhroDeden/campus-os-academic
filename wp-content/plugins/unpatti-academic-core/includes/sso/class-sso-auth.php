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
        $this->redirect_uri  = home_url( '/sso/callback/' );

        if ( empty( $this->client_id ) || empty( $this->client_secret ) ) return;

        add_action( 'init', [ $this, 'register_rewrite' ] );
        add_filter( 'query_vars', [ $this, 'add_query_vars' ] );
        add_action( 'template_redirect', [ $this, 'handle_rewrite_callback' ] );

        $is_fallback = isset( $_GET['fallback'] ) && $_GET['fallback'] === '1';

        add_filter( 'login_message', [ $this, 'sso_error_message' ] );

        if ( $is_fallback ) {
            // Fallback mode: only allow the designated fallback admin username
            add_filter( 'authenticate', [ $this, 'restrict_fallback_user' ], 30, 2 );
        } else {
            // SSO mode: hide login form, show only SSO button, block password auth
            add_action( 'login_form', [ $this, 'render_sso_button' ] );
            add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );
            add_filter( 'authenticate', [ $this, 'block_password_auth' ], 30, 3 );
        }

        add_action( 'wp_ajax_nopriv_unpatti_sso_callback', [ $this, 'handle_callback' ] );
        add_action( 'wp_ajax_unpatti_sso_callback', [ $this, 'handle_callback' ] );
        add_action( 'wp_ajax_nopriv_unpatti_sso_redirect', [ $this, 'redirect_to_sso' ] );
        add_action( 'wp_ajax_unpatti_sso_redirect', [ $this, 'redirect_to_sso' ] );
        add_action( 'wp_logout', [ $this, 'sso_logout' ] );
    }

    public function register_rewrite() {
        add_rewrite_rule( '^sso/callback/?$', 'index.php?unpatti_sso_callback=1', 'top' );
    }

    public function add_query_vars( $vars ) {
        $vars[] = 'unpatti_sso_callback';
        return $vars;
    }

    public function handle_rewrite_callback() {
        if ( ! get_query_var( 'unpatti_sso_callback' ) ) return;
        $this->handle_callback();
    }

    public function redirect_to_sso() {
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

    public function render_sso_button() {
        $sso_url = admin_url( 'admin-ajax.php?action=unpatti_sso_redirect' );
        ?>
        <p class="unpatti-sso-wrap" style="text-align:center; margin: 8px 0 0;">
            <a href="<?php echo esc_url( $sso_url ); ?>" style="display:inline-flex; align-items:center; justify-content:center; gap:8px; width:100%; padding:12px 24px; background:#0073aa; color:#fff; text-decoration:none; border-radius:4px; font-size:14px; font-weight:600; transition:background .2s; box-sizing:border-box;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Login dengan SSO UNPATTI
            </a>
        </p>
        <?php
    }

    public function login_styles() {
        ?>
        <style>
            /* Hide username/password fields, submit button, and lost password */
            .login #loginform p:not(.unpatti-sso-wrap),
            .login #loginform .user-pass-wrap,
            .login #loginform .forgetmenot,
            .login #loginform .submit,
            .login #nav,
            .login #loginform > label {
                display: none !important;
            }
            .login #loginform {
                padding: 26px 24px;
            }
            .login .unpatti-sso-wrap a:hover {
                background: #005a87 !important;
            }
        </style>
        <?php
    }

    public function sso_error_message( $message ) {
        if ( isset( $_GET['sso_error'] ) && $_GET['sso_error'] === 'no_access' ) {
            $message .= '<div id="login_error" style="margin-bottom:16px;"><strong>Akses ditolak.</strong> Akun SSO Anda tidak memiliki role untuk mengakses website ini. Hubungi administrator SSO untuk mendapatkan akses.</div>';
        }
        return $message;
    }

    public function block_password_auth( $user, $username, $password ) {
        if ( ! empty( $username ) ) {
            return new \WP_Error( 'sso_required', __( 'Login dengan username dan password tidak diizinkan. Gunakan SSO UNPATTI.', 'unpatti-academic' ) );
        }
        return $user;
    }

    public function restrict_fallback_user( $user, $username ) {
        if ( is_wp_error( $user ) ) return $user;
        if ( empty( $username ) ) return $user;

        $settings = get_option( 'unpatti_settings', [] );
        $fallback_user = $settings['sso_fallback_admin'] ?? '';

        if ( empty( $fallback_user ) ) {
            return new \WP_Error( 'fallback_disabled', __( 'Fallback admin belum dikonfigurasi. Gunakan SSO untuk login.', 'unpatti-academic' ) );
        }

        if ( $username !== $fallback_user ) {
            return new \WP_Error( 'fallback_restricted', __( 'Hanya fallback admin yang diizinkan login di mode ini.', 'unpatti-academic' ) );
        }

        return $user;
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

        // Authorization: user must have at least one role for this app
        $sso_roles = $user_info['roles'] ?? [];
        if ( empty( $sso_roles ) ) {
            wp_safe_redirect( add_query_arg( 'sso_error', 'no_access', wp_login_url() ) );
            exit;
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
