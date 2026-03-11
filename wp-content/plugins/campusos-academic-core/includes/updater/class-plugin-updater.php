<?php
namespace CampusOS\Core\Updater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Plugin_Updater {

    private $update_url;
    private $plugin_slug = 'campusos-academic-core';
    private $plugin_file;

    public function init() {
        $settings = get_option( 'campusos_settings', [] );
        $this->update_url = $settings['update_server_url'] ?? '';
        if ( empty( $this->update_url ) ) {
            $this->update_url = $settings['license_server_url'] ?? '';
        }
        if ( empty( $this->update_url ) ) return;

        $license_client = new \CampusOS\Core\License\License_Client();
        if ( ! $license_client->is_valid() ) return;

        $this->plugin_file = $this->plugin_slug . '/' . $this->plugin_slug . '.php';

        add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_update' ] );
        add_filter( 'plugins_api', [ $this, 'plugin_info' ], 20, 3 );
    }

    public function check_update( $transient ) {
        if ( empty( $transient->checked ) ) return $transient;

        $current_version = $transient->checked[ $this->plugin_file ] ?? '';
        $license = ( new \CampusOS\Core\License\License_Client() )->get_license();
        $response = wp_remote_get( add_query_arg( [
            'slug'        => $this->plugin_slug,
            'version'     => $current_version,
            'type'        => 'plugin',
            'license_key' => $license['key'] ?? '',
        ], trailingslashit( $this->update_url ) . 'api/check' ), [ 'timeout' => 15 ] );

        if ( is_wp_error( $response ) ) return $transient;

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! empty( $data['version'] ) && version_compare( $data['version'], $current_version, '>' ) ) {
            $transient->response[ $this->plugin_file ] = (object) [
                'slug'        => $this->plugin_slug,
                'plugin'      => $this->plugin_file,
                'new_version' => $data['version'],
                'url'         => $data['changelog_url'] ?? '',
                'package'     => $data['download_url'] ?? '',
                'tested'      => $data['tested'] ?? '',
                'requires'    => $data['requires'] ?? '',
            ];
        }

        return $transient;
    }

    public function plugin_info( $result, $action, $args ) {
        if ( $action !== 'plugin_information' || $args->slug !== $this->plugin_slug ) {
            return $result;
        }

        $response = wp_remote_get( add_query_arg( [
            'slug' => $this->plugin_slug,
            'type' => 'plugin',
        ], trailingslashit( $this->update_url ) . 'api/info' ), [ 'timeout' => 15 ] );

        if ( is_wp_error( $response ) ) return $result;

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( empty( $data ) ) return $result;

        return (object) [
            'name'          => $data['name'] ?? 'CampusOS Academic Core',
            'slug'          => $this->plugin_slug,
            'version'       => $data['version'] ?? '',
            'author'        => $data['author'] ?? '',
            'homepage'      => $data['homepage'] ?? '',
            'requires'      => $data['requires'] ?? '',
            'tested'        => $data['tested'] ?? '',
            'downloaded'    => $data['downloaded'] ?? 0,
            'last_updated'  => $data['last_updated'] ?? '',
            'sections'      => $data['sections'] ?? [],
            'download_link' => $data['download_url'] ?? '',
        ];
    }
}
