<?php
namespace CampusOS\Core\License;

if ( ! defined( 'ABSPATH' ) ) exit;

class License_Client {

    private $option_key = 'campusos_license';

    public function init() {
        add_action( 'admin_notices', [ $this, 'license_notice' ] );
        add_action( 'campusos_license_revalidate', [ $this, 'revalidate' ] );

        if ( ! wp_next_scheduled( 'campusos_license_revalidate' ) ) {
            wp_schedule_event( time(), 'weekly', 'campusos_license_revalidate' );
        }
    }

    public function get_license() {
        return get_option( $this->option_key, [
            'key'        => '',
            'status'     => 'inactive',
            'expires_at' => '',
            'domain'     => '',
        ] );
    }

    public static function is_license_active() {
        $license = get_option( 'campusos_license', [ 'status' => 'inactive', 'expires_at' => '' ] );
        if ( $license['status'] !== 'active' ) return false;
        if ( ! empty( $license['expires_at'] ) && strtotime( $license['expires_at'] ) < time() ) {
            $license['status'] = 'expired';
            update_option( 'campusos_license', $license );
            return false;
        }
        return true;
    }

    public function is_valid() {
        $license = $this->get_license();
        if ( $license['status'] !== 'active' ) {
            return false;
        }
        if ( ! empty( $license['expires_at'] ) && strtotime( $license['expires_at'] ) < time() ) {
            $license['status'] = 'expired';
            update_option( $this->option_key, $license );
            return false;
        }
        return true;
    }

    public function activate( $license_key ) {
        $server_url = $this->get_server_url();
        if ( empty( $server_url ) ) {
            return [ 'success' => false, 'message' => __( 'URL server lisensi belum dikonfigurasi.', 'campusos-academic' ) ];
        }

        $domain  = $this->get_site_domain();
        $payload = [
            'license_key' => sanitize_text_field( $license_key ),
            'domain'      => $domain,
            'product'     => 'campusos-academic',
        ];

        $response = wp_remote_post( trailingslashit( $server_url ) . 'api/activate', [
            'timeout' => 30,
            'headers' => [ 'Content-Type' => 'application/json' ],
            'body'    => wp_json_encode( $payload ),
        ] );

        if ( is_wp_error( $response ) ) {
            return [ 'success' => false, 'message' => $response->get_error_message() ];
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( $code === 200 && ! empty( $body['success'] ) ) {
            update_option( $this->option_key, [
                'key'        => sanitize_text_field( $license_key ),
                'status'     => 'active',
                'expires_at' => sanitize_text_field( $body['expires_at'] ?? '' ),
                'domain'     => $domain,
            ] );
            return [ 'success' => true, 'message' => __( 'Lisensi berhasil diaktifkan.', 'campusos-academic' ) ];
        }

        return [
            'success' => false,
            'message' => $body['message'] ?? __( 'Gagal mengaktifkan lisensi.', 'campusos-academic' ),
        ];
    }

    public function deactivate() {
        $license    = $this->get_license();
        $server_url = $this->get_server_url();

        if ( ! empty( $server_url ) && ! empty( $license['key'] ) ) {
            wp_remote_post( trailingslashit( $server_url ) . 'api/deactivate', [
                'timeout' => 15,
                'headers' => [ 'Content-Type' => 'application/json' ],
                'body'    => wp_json_encode( [
                    'license_key' => $license['key'],
                    'domain'      => $this->get_site_domain(),
                ] ),
            ] );
        }

        delete_option( $this->option_key );
        return [ 'success' => true, 'message' => __( 'Lisensi dinonaktifkan.', 'campusos-academic' ) ];
    }

    public function revalidate() {
        $license    = $this->get_license();
        $server_url = $this->get_server_url();

        if ( empty( $license['key'] ) || empty( $server_url ) ) return;

        $response = wp_remote_post( trailingslashit( $server_url ) . 'api/validate', [
            'timeout' => 15,
            'headers' => [ 'Content-Type' => 'application/json' ],
            'body'    => wp_json_encode( [
                'license_key' => $license['key'],
                'domain'      => $this->get_site_domain(),
            ] ),
        ] );

        if ( is_wp_error( $response ) ) return;

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! empty( $body['status'] ) ) {
            $license['status']     = sanitize_text_field( $body['status'] );
            $license['expires_at'] = sanitize_text_field( $body['expires_at'] ?? $license['expires_at'] );
            update_option( $this->option_key, $license );
        }
    }

    public function license_notice() {
        if ( ! current_user_can( 'manage_options' ) ) return;

        $license = $this->get_license();

        if ( empty( $license['key'] ) || $license['status'] === 'inactive' ) {
            echo '<div class="notice notice-warning"><p>';
            printf(
                esc_html__( 'CampusOS Academic: Lisensi belum diaktifkan. %sAktifkan sekarang%s untuk mendapat auto-update.', 'campusos-academic' ),
                '<a href="' . esc_url( admin_url( 'admin.php?page=campusos-academic&tab=lisensi' ) ) . '">',
                '</a>'
            );
            echo '</p></div>';
        } elseif ( $license['status'] === 'expired' ) {
            echo '<div class="notice notice-error"><p>';
            printf(
                esc_html__( 'CampusOS Academic: Lisensi sudah expired pada %s. Perpanjang lisensi untuk mendapat update terbaru.', 'campusos-academic' ),
                esc_html( date_i18n( get_option( 'date_format' ), strtotime( $license['expires_at'] ) ) )
            );
            echo '</p></div>';
        }
    }

    private function get_server_url() {
        $settings = get_option( 'campusos_settings', [] );
        return $settings['license_server_url'] ?? 'https://campusos.devlecta.com';
    }

    private function get_site_domain() {
        return wp_parse_url( home_url(), PHP_URL_HOST );
    }
}
