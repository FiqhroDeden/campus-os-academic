<?php
namespace UNPATTI\Core\Security;

if ( ! defined( 'ABSPATH' ) ) exit;

class Content_Scanner {

    private $default_keywords = [
        'slot gacor', 'slot online', 'togel', 'casino', 'judi online',
        'poker online', 'sbobet', 'pragmatic play', 'slot88', 'joker123',
        'slot deposit', 'bandar togel', 'agen judi', 'taruhan', 'jackpot',
        'maxwin', 'rtp slot', 'bocoran slot',
    ];

    public function init() {
        $opts = get_option( 'unpatti_settings', [] );
        if ( empty( $opts['security_scanner_enabled'] ) ) {
            return;
        }

        // Schedule cron every 6 hours
        add_filter( 'cron_schedules', [ $this, 'add_schedule' ] );
        add_action( 'unpatti_content_scan', [ $this, 'run_scan' ] );

        if ( ! wp_next_scheduled( 'unpatti_content_scan' ) ) {
            wp_schedule_event( time(), 'every_six_hours', 'unpatti_content_scan' );
        }

        // Admin notice for quarantined content
        add_action( 'admin_notices', [ $this, 'quarantine_notice' ] );
    }

    public function add_schedule( $schedules ) {
        $schedules['every_six_hours'] = [
            'interval' => 6 * HOUR_IN_SECONDS,
            'display'  => __( 'Every 6 Hours', 'unpatti-academic' ),
        ];
        return $schedules;
    }

    private function get_keywords() {
        $keywords = $this->default_keywords;
        $opts     = get_option( 'unpatti_settings', [] );

        if ( ! empty( $opts['security_scanner_keywords'] ) ) {
            $custom = array_filter( array_map( 'trim', explode( "\n", $opts['security_scanner_keywords'] ) ) );
            $keywords = array_unique( array_merge( $keywords, $custom ) );
        }

        return $keywords;
    }

    public function run_scan() {
        $keywords = $this->get_keywords();
        if ( empty( $keywords ) ) {
            return;
        }

        $pattern = '/' . implode( '|', array_map( 'preg_quote', $keywords ) ) . '/i';

        $posts = get_posts( [
            'post_type'      => [ 'post', 'page' ],
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ] );

        $flagged = [];

        foreach ( $posts as $post_id ) {
            $post    = get_post( $post_id );
            $content = $post->post_title . ' ' . $post->post_content . ' ' . $post->post_excerpt;

            // Strip tags but also check raw HTML for hidden content
            $plain = wp_strip_all_tags( $content );

            // Check for hidden elements with spam keywords
            $raw_check = $post->post_content;

            $found_in_plain = preg_match( $pattern, $plain );
            $found_in_raw   = preg_match( $pattern, $raw_check );

            if ( $found_in_plain || $found_in_raw ) {
                // Quarantine: set to draft
                wp_update_post( [
                    'ID'          => $post_id,
                    'post_status' => 'draft',
                ] );
                update_post_meta( $post_id, '_unpatti_quarantined', 1 );

                preg_match( $pattern, $content, $matches );
                $flagged[] = [
                    'id'      => $post_id,
                    'title'   => $post->post_title,
                    'matched' => $matches[0] ?? '',
                ];
            }
        }

        if ( ! empty( $flagged ) ) {
            $this->notify_admin( $flagged );
        }
    }

    private function notify_admin( $flagged ) {
        $admin_email = get_option( 'admin_email' );
        $site_name   = get_bloginfo( 'name' );

        $body  = sprintf( "Pemindaian konten di %s menemukan %d konten mencurigakan:\n\n", $site_name, count( $flagged ) );

        foreach ( $flagged as $item ) {
            $body .= sprintf(
                "- [ID %d] %s (kata kunci: \"%s\")\n  Edit: %s\n\n",
                $item['id'],
                $item['title'],
                $item['matched'],
                get_edit_post_link( $item['id'], 'raw' )
            );
        }

        $body .= "Konten telah dikarantina (diubah ke draft) secara otomatis.\n";

        wp_mail(
            $admin_email,
            sprintf( '[%s] Peringatan: Konten Mencurigakan Terdeteksi', $site_name ),
            $body
        );
    }

    public function quarantine_notice() {
        $screen = get_current_screen();
        if ( ! $screen || ! in_array( $screen->id, [ 'edit-post', 'edit-page', 'dashboard' ], true ) ) {
            return;
        }

        $quarantined = get_posts( [
            'post_type'      => [ 'post', 'page' ],
            'post_status'    => 'draft',
            'meta_key'       => '_unpatti_quarantined',
            'meta_value'     => '1',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ] );

        if ( empty( $quarantined ) ) {
            return;
        }

        printf(
            '<div class="notice notice-warning"><p><strong>%s</strong> %s</p></div>',
            __( 'Peringatan Keamanan:', 'unpatti-academic' ),
            sprintf(
                __( '%d konten telah dikarantina karena terdeteksi mengandung kata kunci mencurigakan. Silakan periksa draft Anda.', 'unpatti-academic' ),
                count( $quarantined )
            )
        );
    }
}
