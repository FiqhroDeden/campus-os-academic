<?php
namespace UNPATTI\Core\Security;

if ( ! defined( 'ABSPATH' ) ) exit;

class File_Integrity {

    public function init() {
        add_action( 'unpatti_file_integrity_check', [ $this, 'check_integrity' ] );

        if ( ! wp_next_scheduled( 'unpatti_file_integrity_check' ) ) {
            wp_schedule_event( time(), 'daily', 'unpatti_file_integrity_check' );
        }
    }

    /**
     * Generate SHA-256 hashes for all files in theme and plugin directories.
     * Called on plugin activation.
     */
    public function generate_hashes() {
        $dirs   = [
            'themes'  => get_theme_root(),
            'plugins' => WP_PLUGIN_DIR,
        ];
        $hashes = [];

        foreach ( $dirs as $label => $dir ) {
            if ( ! is_dir( $dir ) ) {
                continue;
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator( $dir, \RecursiveDirectoryIterator::SKIP_DOTS ),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ( $iterator as $file ) {
                if ( ! $file->isFile() ) {
                    continue;
                }
                $path = $file->getRealPath();
                $rel  = str_replace( ABSPATH, '', $path );
                $hashes[ $rel ] = hash_file( 'sha256', $path );
            }
        }

        update_option( 'unpatti_file_hashes', $hashes, false );
    }

    /**
     * Compare current file hashes against stored baseline.
     */
    public function check_integrity() {
        $stored = get_option( 'unpatti_file_hashes', [] );
        if ( empty( $stored ) ) {
            return;
        }

        $dirs = [
            'themes'  => get_theme_root(),
            'plugins' => WP_PLUGIN_DIR,
        ];

        $current = [];
        foreach ( $dirs as $label => $dir ) {
            if ( ! is_dir( $dir ) ) {
                continue;
            }
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator( $dir, \RecursiveDirectoryIterator::SKIP_DOTS ),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ( $iterator as $file ) {
                if ( ! $file->isFile() ) {
                    continue;
                }
                $path = $file->getRealPath();
                $rel  = str_replace( ABSPATH, '', $path );
                $current[ $rel ] = hash_file( 'sha256', $path );
            }
        }

        $modified = [];
        $added    = [];
        $removed  = [];

        // Check for modified and removed files
        foreach ( $stored as $rel => $hash ) {
            if ( ! isset( $current[ $rel ] ) ) {
                $removed[] = $rel;
            } elseif ( $current[ $rel ] !== $hash ) {
                $modified[] = $rel;
            }
        }

        // Check for new files
        foreach ( $current as $rel => $hash ) {
            if ( ! isset( $stored[ $rel ] ) ) {
                $added[] = $rel;
            }
        }

        if ( empty( $modified ) && empty( $added ) && empty( $removed ) ) {
            return;
        }

        $this->alert_admin( $modified, $added, $removed );

        // Update stored hashes to current state
        update_option( 'unpatti_file_hashes', $current, false );
    }

    private function alert_admin( $modified, $added, $removed ) {
        $admin_email = get_option( 'admin_email' );
        $site_name   = get_bloginfo( 'name' );

        $body = sprintf( "Pemeriksaan integritas file di %s mendeteksi perubahan:\n\n", $site_name );

        if ( ! empty( $modified ) ) {
            $body .= "FILE DIMODIFIKASI:\n";
            foreach ( $modified as $f ) {
                $body .= "  - {$f}\n";
            }
            $body .= "\n";
        }

        if ( ! empty( $added ) ) {
            $body .= "FILE BARU:\n";
            foreach ( $added as $f ) {
                $body .= "  - {$f}\n";
            }
            $body .= "\n";
        }

        if ( ! empty( $removed ) ) {
            $body .= "FILE DIHAPUS:\n";
            foreach ( $removed as $f ) {
                $body .= "  - {$f}\n";
            }
            $body .= "\n";
        }

        $body .= "Harap periksa perubahan ini untuk memastikan tidak ada modifikasi yang tidak sah.\n";

        wp_mail(
            $admin_email,
            sprintf( '[%s] Peringatan: Perubahan File Terdeteksi', $site_name ),
            $body
        );
    }
}
