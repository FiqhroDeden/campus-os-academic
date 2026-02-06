<?php
namespace CampusOS\Core\Integrations;

if ( ! defined( 'ABSPATH' ) ) exit;

class Shortcode_Data {
    public function init() {
        add_shortcode( 'campusos_data', [ $this, 'render' ] );
    }

    public function render( $atts ) {
        $atts = shortcode_atts( [
            'source' => '',
            'type'   => '',
            'endpoint' => '',
        ], $atts, 'campusos_data' );

        $source = sanitize_text_field( $atts['source'] );
        $type = sanitize_text_field( $atts['type'] );
        $endpoint = sanitize_text_field( $atts['endpoint'] );

        if ( empty( $source ) ) {
            return '<span class="campusos-data-error">' . esc_html__( 'Parameter source diperlukan.', 'campusos-academic' ) . '</span>';
        }

        $connector = null;
        switch ( $source ) {
            case 'siakad':
                $connector = new SIAKAD_Connector();
                break;
            case 'sigap':
                $connector = new SIGAP_Connector();
                break;
            default:
                return '<span class="campusos-data-error">' . esc_html__( 'Source tidak dikenali.', 'campusos-academic' ) . '</span>';
        }

        if ( empty( $connector->get_base_url() ) ) {
            return '<span class="campusos-data-placeholder">' . esc_html__( 'Belum terhubung', 'campusos-academic' ) . '</span>';
        }

        $ep = ! empty( $endpoint ) ? $endpoint : $type;
        if ( empty( $ep ) ) {
            return '<span class="campusos-data-error">' . esc_html__( 'Parameter type atau endpoint diperlukan.', 'campusos-academic' ) . '</span>';
        }

        $data = $connector->fetch( $ep );
        if ( ! empty( $data['error'] ) ) {
            return '<span class="campusos-data-error">' . esc_html( $data['message'] ?? 'Error' ) . '</span>';
        }

        // If data is a single value (e.g. count), return it
        if ( isset( $data['count'] ) ) {
            return '<span class="campusos-data-value">' . esc_html( $data['count'] ) . '</span>';
        }
        if ( isset( $data['value'] ) ) {
            return '<span class="campusos-data-value">' . esc_html( $data['value'] ) . '</span>';
        }

        return '<span class="campusos-data-value">' . esc_html( wp_json_encode( $data ) ) . '</span>';
    }
}
