<?php
namespace CampusOS\Core\Updater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Theme_Updater {

    private $update_url;
    private $theme_slug = 'campusos-academic';

    public function init() {
        $settings = get_option( 'campusos_settings', [] );
        $this->update_url = $settings['update_server_url'] ?? '';
        if ( empty( $this->update_url ) ) return;

        $license_client = new \CampusOS\Core\License\License_Client();
        if ( ! $license_client->is_valid() ) return;

        add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_update' ] );
    }

    public function check_update( $transient ) {
        if ( empty( $transient->checked ) ) return $transient;

        $current_version = $transient->checked[ $this->theme_slug ] ?? '';
        $license = ( new \CampusOS\Core\License\License_Client() )->get_license();
        $response = wp_remote_get( add_query_arg( [
            'slug'        => $this->theme_slug,
            'version'     => $current_version,
            'type'        => 'theme',
            'license_key' => $license['key'] ?? '',
        ], trailingslashit( $this->update_url ) . 'api/check' ), [ 'timeout' => 15 ] );

        if ( is_wp_error( $response ) ) return $transient;

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! empty( $data['version'] ) && version_compare( $data['version'], $current_version, '>' ) ) {
            $transient->response[ $this->theme_slug ] = [
                'theme'       => $this->theme_slug,
                'new_version' => $data['version'],
                'url'         => $data['changelog_url'] ?? '',
                'package'     => $data['download_url'] ?? '',
            ];
        }

        return $transient;
    }
}
