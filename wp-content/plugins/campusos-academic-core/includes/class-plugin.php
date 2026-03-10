<?php
namespace CampusOS\Core;

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
        $this->load_license_core();

        if ( \CampusOS\Core\License\License_Client::is_license_active() ) {
            $this->load_dependencies();
            $this->init_hooks();
        } else {
            $this->init_unlicensed();
        }
    }

    private function load_license_core() {
        require_once CAMPUSOS_CORE_PATH . 'includes/license/class-license-client.php';
        ( new \CampusOS\Core\License\License_Client() )->init();

        require_once CAMPUSOS_CORE_PATH . 'includes/admin/class-admin-settings.php';
    }

    private function init_unlicensed() {
        add_action( 'init', [ $this, 'load_textdomain' ] );

        add_action( 'admin_menu', function() {
            add_menu_page(
                __( 'CampusOS Academic', 'campusos-academic' ),
                __( 'CampusOS Academic', 'campusos-academic' ),
                'manage_options',
                'campusos-academic',
                [ $this, 'render_license_page' ],
                'dashicons-university',
                3
            );
        } );

        add_action( 'admin_init', function() {
            register_setting( 'campusos_settings_group', 'campusos_settings', [
                'sanitize_callback' => [ ( new \CampusOS\Core\Admin\Admin_Settings() ), 'sanitize_settings' ],
            ] );
        } );

        add_action( 'wp_ajax_campusos_license_activate', function() {
            ( new \CampusOS\Core\Admin\Admin_Settings() )->ajax_license_activate();
        } );

        add_action( 'wp_ajax_campusos_license_deactivate', function() {
            ( new \CampusOS\Core\Admin\Admin_Settings() )->ajax_license_deactivate();
        } );

        add_action( 'admin_notices', function() {
            if ( ! current_user_can( 'manage_options' ) ) return;
            echo '<div class="notice notice-error"><p>';
            printf(
                esc_html__( 'CampusOS Academic: Lisensi belum diaktifkan. Semua fitur dinonaktifkan. %sAktifkan sekarang%s', 'campusos-academic' ),
                '<a href="' . esc_url( admin_url( 'admin.php?page=campusos-academic' ) ) . '">',
                '</a>'
            );
            echo '</p></div>';
        } );

        add_action( 'template_redirect', function() {
            if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
                return;
            }
            require CAMPUSOS_CORE_PATH . 'includes/license/template-license-required.php';
        } );
    }

    public function render_license_page() {
        $admin_settings = new \CampusOS\Core\Admin\Admin_Settings();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'CampusOS Academic — Aktivasi Lisensi', 'campusos-academic' ); ?></h1>
            <div class="notice notice-warning inline" style="margin: 15px 0;">
                <p><?php esc_html_e( 'Lisensi belum diaktifkan. Aktifkan lisensi untuk menggunakan semua fitur tema dan plugin.', 'campusos-academic' ); ?></p>
            </div>
            <form method="post" action="options.php">
                <?php settings_fields( 'campusos_settings_group' ); ?>
                <input type="hidden" name="campusos_settings[_active_tab]" value="lisensi" />
                <?php $admin_settings->render_tab_lisensi(); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    private function load_dependencies() {
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-base.php';
        // Pimpinan is now a settings page, not a CPT
        // require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-pimpinan.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-tenaga-pendidik.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-kerjasama.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-fasilitas.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-prestasi.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-dokumen.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-agenda.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-faq.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-mata-kuliah.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-organisasi-mhs.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-mitra-industri.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-publikasi.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-beasiswa.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-galeri.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-video.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-pengumuman.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/cpt/class-cpt-testimonial.php';

        // Pimpinan Settings (single configuration page)
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/class-pimpinan-settings.php';
        new \CampusOS\Core\Admin\Pimpinan_Settings();

        // Register all CPTs (Pimpinan moved to settings page)
        // ( new \CampusOS\Core\CPT\CPT_Pimpinan() )->register();
        ( new \CampusOS\Core\CPT\CPT_Tenaga_Pendidik() )->register();
        ( new \CampusOS\Core\CPT\CPT_Kerjasama() )->register();
        ( new \CampusOS\Core\CPT\CPT_Fasilitas() )->register();
        ( new \CampusOS\Core\CPT\CPT_Prestasi() )->register();
        ( new \CampusOS\Core\CPT\CPT_Dokumen() )->register();
        ( new \CampusOS\Core\CPT\CPT_Agenda() )->register();
        ( new \CampusOS\Core\CPT\CPT_Faq() )->register();
        ( new \CampusOS\Core\CPT\CPT_Mata_Kuliah() )->register();
        ( new \CampusOS\Core\CPT\CPT_Organisasi_Mhs() )->register();
        ( new \CampusOS\Core\CPT\CPT_Mitra_Industri() )->register();
        ( new \CampusOS\Core\CPT\CPT_Publikasi() )->register();
        ( new \CampusOS\Core\CPT\CPT_Beasiswa() )->register();
        ( new \CampusOS\Core\CPT\CPT_Galeri() )->register();
        ( new \CampusOS\Core\CPT\CPT_Video() )->register();
        ( new \CampusOS\Core\CPT\CPT_Pengumuman() )->register();
        ( new \CampusOS\Core\CPT\CPT_Testimonial() )->register();

        // Meta boxes
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-base.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-sejarah.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-visi-misi.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-struktur-org.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-sambutan.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-akreditasi.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-cpl.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-penerimaan.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-biaya-ukt.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-statistik.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/meta-boxes/class-mb-tracer-study.php';

        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Sejarah() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Visi_Misi() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Struktur_Org() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Sambutan() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Akreditasi() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_CPL() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Penerimaan() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Biaya_UKT() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Statistik() )->register();
        ( new \CampusOS\Core\Admin\MetaBoxes\MB_Tracer_Study() )->register();

        // Admin settings (class already loaded in load_license_core)
        ( new \CampusOS\Core\Admin\Admin_Settings() )->register();

        // Page Updater
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/class-page-updater.php';
        ( new \CampusOS\Core\Admin\Page_Updater() )->init();

        // Post Status Fixer
        require_once CAMPUSOS_CORE_PATH . 'includes/admin/class-post-status-fixer.php';
        ( new \CampusOS\Core\Admin\Post_Status_Fixer() )->init();

        // Export/Import
        require_once CAMPUSOS_CORE_PATH . 'includes/export-import/class-exporter.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/export-import/class-importer.php';
        ( new \CampusOS\Core\ExportImport\Exporter() )->init();
        ( new \CampusOS\Core\ExportImport\Importer() )->init();

        // Updater
        require_once CAMPUSOS_CORE_PATH . 'includes/updater/class-theme-updater.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/updater/class-plugin-updater.php';
        ( new \CampusOS\Core\Updater\Theme_Updater() )->init();
        ( new \CampusOS\Core\Updater\Plugin_Updater() )->init();

        // SSO
        require_once CAMPUSOS_CORE_PATH . 'includes/sso/class-sso-auth.php';
        ( new \CampusOS\Core\SSO\SSO_Auth() )->init();

        // License (class already loaded and init'd in load_license_core)

        // Security
        require_once CAMPUSOS_CORE_PATH . 'includes/security/class-hardening.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/security/class-content-scanner.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/security/class-file-integrity.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/security/class-activity-log.php';

        ( new \CampusOS\Core\Security\Hardening() )->init();
        ( new \CampusOS\Core\Security\Content_Scanner() )->init();
        ( new \CampusOS\Core\Security\File_Integrity() )->init();
        ( new \CampusOS\Core\Security\Activity_Log() )->init();

        // API Integrations
        require_once CAMPUSOS_CORE_PATH . 'includes/integrations/class-api-connector.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/integrations/class-siakad-connector.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/integrations/class-sigap-connector.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/integrations/class-shortcode-data.php';
        require_once CAMPUSOS_CORE_PATH . 'includes/integrations/class-api-ajax.php';

        ( new \CampusOS\Core\Integrations\Shortcode_Data() )->init();
        ( new \CampusOS\Core\Integrations\API_Ajax() )->init();

        // Frontend Shortcodes
        require_once CAMPUSOS_CORE_PATH . 'includes/frontend/class-shortcodes.php';
        ( new \CampusOS\Core\Frontend\Shortcodes() )->init();
    }

    private function init_hooks() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'campusos-academic', false, dirname( plugin_basename( CAMPUSOS_CORE_PATH . 'campusos-academic-core.php' ) ) . '/languages' );
    }

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'campusos_dashboard_widget',
            __( 'CampusOS Academic', 'campusos-academic' ),
            [ $this, 'render_dashboard_widget' ]
        );
    }

    public function render_dashboard_widget() {
        $mode = get_theme_mod( 'campusos_site_mode', 'prodi' );
        $name = get_theme_mod( 'campusos_institution_name', get_bloginfo( 'name' ) );
        $settings = get_option( 'campusos_settings', [] );

        echo '<div class="campusos-dashboard">';
        echo '<p><strong>' . esc_html( $name ) . '</strong> — ' . ( $mode === 'fakultas' ? 'Fakultas' : 'Program Studi' ) . '</p>';
        echo '<hr/>';

        // CPT counts (Pimpinan is now a settings page)
        $cpts = [
            'tenaga_pendidik' => __( 'Tenaga Pendidik', 'campusos-academic' ),
            'kerjasama'       => __( 'Kerjasama', 'campusos-academic' ),
            'fasilitas'       => __( 'Fasilitas', 'campusos-academic' ),
            'prestasi'        => __( 'Prestasi', 'campusos-academic' ),
            'dokumen'         => __( 'Dokumen', 'campusos-academic' ),
            'agenda'          => __( 'Agenda', 'campusos-academic' ),
            'faq'             => __( 'FAQ', 'campusos-academic' ),
            'publikasi'       => __( 'Publikasi', 'campusos-academic' ),
            'galeri'          => __( 'Galeri', 'campusos-academic' ),
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
        $last_scan = get_option( 'campusos_last_scan', [] );
        if ( $last_scan ) {
            echo '<p>' . esc_html__( 'Scan terakhir:', 'campusos-academic' ) . ' ' . esc_html( $last_scan['time'] ?? '-' ) . ' — ';
            echo esc_html( ( $last_scan['flagged'] ?? 0 ) . ' konten ditandai' ) . '</p>';
        }

        // SSO status
        $sso = ! empty( $settings['sso_enabled'] ) ? __( 'Aktif', 'campusos-academic' ) : __( 'Nonaktif', 'campusos-academic' );
        echo '<p>SSO: <strong>' . esc_html( $sso ) . '</strong></p>';

        echo '<p><a href="' . esc_url( admin_url( 'admin.php?page=campusos-academic' ) ) . '" class="button">' . esc_html__( 'Pengaturan', 'campusos-academic' ) . '</a></p>';
        echo '</div>';
    }
}
