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
    }

    private function init_hooks() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'unpatti-academic', false, dirname( plugin_basename( UNPATTI_CORE_PATH . 'unpatti-academic-core.php' ) ) . '/languages' );
    }
}
