<?php
namespace UNPATTI\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

final class Plugin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-base.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-pimpinan.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-tenaga-pendidik.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-kerjasama.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-fasilitas.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-prestasi.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-dokumen.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-agenda.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-faq.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-mata-kuliah.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-organisasi-mhs.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-mitra-industri.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-publikasi.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-beasiswa.php';
        require_once UNPATTI_CORE_PATH . 'includes/cpt/class-cpt-galeri.php';

        // Register all CPTs
        ( new \UNPATTI\Core\CPT\CPT_Pimpinan() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Tenaga_Pendidik() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Kerjasama() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Fasilitas() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Prestasi() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Dokumen() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Agenda() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Faq() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Mata_Kuliah() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Organisasi_Mhs() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Mitra_Industri() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Publikasi() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Beasiswa() )->register();
        ( new \UNPATTI\Core\CPT\CPT_Galeri() )->register();

        // Meta boxes
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-base.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-sejarah.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-visi-misi.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-struktur-org.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-sambutan.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-akreditasi.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-cpl.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-penerimaan.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-biaya-ukt.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-statistik.php';
        require_once UNPATTI_CORE_PATH . 'includes/admin/meta-boxes/class-mb-tracer-study.php';

        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Sejarah() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Visi_Misi() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Struktur_Org() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Sambutan() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Akreditasi() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_CPL() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Penerimaan() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Biaya_UKT() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Statistik() )->register();
        ( new \UNPATTI\Core\Admin\MetaBoxes\MB_Tracer_Study() )->register();

        // Admin settings
        require_once UNPATTI_CORE_PATH . 'includes/admin/class-admin-settings.php';
        ( new \UNPATTI\Core\Admin\Admin_Settings() )->register();

        // Export/Import
        require_once UNPATTI_CORE_PATH . 'includes/export-import/class-exporter.php';
        require_once UNPATTI_CORE_PATH . 'includes/export-import/class-importer.php';
        ( new \UNPATTI\Core\ExportImport\Exporter() )->init();
        ( new \UNPATTI\Core\ExportImport\Importer() )->init();

        // Updater
        require_once UNPATTI_CORE_PATH . 'includes/updater/class-theme-updater.php';
        require_once UNPATTI_CORE_PATH . 'includes/updater/class-plugin-updater.php';
        ( new \UNPATTI\Core\Updater\Theme_Updater() )->init();
        ( new \UNPATTI\Core\Updater\Plugin_Updater() )->init();

        // SSO
        require_once UNPATTI_CORE_PATH . 'includes/sso/class-sso-auth.php';
        ( new \UNPATTI\Core\SSO\SSO_Auth() )->init();

        // Security
        require_once UNPATTI_CORE_PATH . 'includes/security/class-hardening.php';
        require_once UNPATTI_CORE_PATH . 'includes/security/class-content-scanner.php';
        require_once UNPATTI_CORE_PATH . 'includes/security/class-file-integrity.php';
        require_once UNPATTI_CORE_PATH . 'includes/security/class-activity-log.php';

        ( new \UNPATTI\Core\Security\Hardening() )->init();
        ( new \UNPATTI\Core\Security\Content_Scanner() )->init();
        ( new \UNPATTI\Core\Security\File_Integrity() )->init();
        ( new \UNPATTI\Core\Security\Activity_Log() )->init();

        // API Integrations
        require_once UNPATTI_CORE_PATH . 'includes/integrations/class-api-connector.php';
        require_once UNPATTI_CORE_PATH . 'includes/integrations/class-siakad-connector.php';
        require_once UNPATTI_CORE_PATH . 'includes/integrations/class-sigap-connector.php';
        require_once UNPATTI_CORE_PATH . 'includes/integrations/class-shortcode-data.php';
        require_once UNPATTI_CORE_PATH . 'includes/integrations/class-api-ajax.php';

        ( new \UNPATTI\Core\Integrations\Shortcode_Data() )->init();
        ( new \UNPATTI\Core\Integrations\API_Ajax() )->init();
    }

    private function init_hooks() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'unpatti-academic', false, dirname( plugin_basename( UNPATTI_CORE_PATH . 'unpatti-academic-core.php' ) ) . '/languages' );
    }

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'unpatti_dashboard_widget',
            __( 'UNPATTI Academic', 'unpatti-academic' ),
            [ $this, 'render_dashboard_widget' ]
        );
    }

    public function render_dashboard_widget() {
        $mode = get_theme_mod( 'unpatti_site_mode', 'prodi' );
        $name = get_theme_mod( 'unpatti_institution_name', get_bloginfo( 'name' ) );
        $settings = get_option( 'unpatti_settings', [] );

        echo '<div class="unpatti-dashboard">';
        echo '<p><strong>' . esc_html( $name ) . '</strong> — ' . ( $mode === 'fakultas' ? 'Fakultas' : 'Program Studi' ) . '</p>';
        echo '<hr/>';

        // CPT counts
        $cpts = [
            'pimpinan'        => __( 'Pimpinan', 'unpatti-academic' ),
            'tenaga_pendidik' => __( 'Tenaga Pendidik', 'unpatti-academic' ),
            'kerjasama'       => __( 'Kerjasama', 'unpatti-academic' ),
            'fasilitas'       => __( 'Fasilitas', 'unpatti-academic' ),
            'prestasi'        => __( 'Prestasi', 'unpatti-academic' ),
            'dokumen'         => __( 'Dokumen', 'unpatti-academic' ),
            'agenda'          => __( 'Agenda', 'unpatti-academic' ),
            'faq'             => __( 'FAQ', 'unpatti-academic' ),
            'publikasi'       => __( 'Publikasi', 'unpatti-academic' ),
            'galeri'          => __( 'Galeri', 'unpatti-academic' ),
        ];
        echo '<table style="width:100%;font-size:13px;">';
        foreach ( $cpts as $slug => $label ) {
            $count = wp_count_posts( $slug );
            $published = $count->publish ?? 0;
            echo '<tr><td>' . esc_html( $label ) . '</td><td style="text-align:right;"><strong>' . (int) $published . '</strong></td></tr>';
        }
        echo '</table>';
        echo '<hr/>';

        // Security status
        $last_scan = get_option( 'unpatti_last_scan', [] );
        if ( $last_scan ) {
            echo '<p>' . esc_html__( 'Scan terakhir:', 'unpatti-academic' ) . ' ' . esc_html( $last_scan['time'] ?? '-' ) . ' — ';
            echo esc_html( ( $last_scan['flagged'] ?? 0 ) . ' konten ditandai' ) . '</p>';
        }

        // SSO status
        $sso = ! empty( $settings['sso_enabled'] ) ? __( 'Aktif', 'unpatti-academic' ) : __( 'Nonaktif', 'unpatti-academic' );
        echo '<p>SSO: <strong>' . esc_html( $sso ) . '</strong></p>';

        echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=unpatti-academic' ) ) . '" class="button">' . esc_html__( 'Pengaturan', 'unpatti-academic' ) . '</a></p>';
        echo '</div>';
    }
}
