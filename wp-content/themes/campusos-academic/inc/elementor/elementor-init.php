<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Only load if Elementor is active
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category( 'campusos-academic', [
        'title' => __( 'CampusOS Academic', 'campusos-academic' ),
        'icon'  => 'fa fa-university',
    ] );
} );

add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    $widgets_dir = CAMPUSOS_THEME_PATH . '/inc/elementor/widgets/';

    // Base class (must load first)
    require_once $widgets_dir . 'widget-base.php';

    // Existing widgets
    require_once $widgets_dir . 'hero-slider.php';
    require_once $widgets_dir . 'stats-counter.php';
    require_once $widgets_dir . 'team-grid.php';
    require_once $widgets_dir . 'news-grid.php';
    require_once $widgets_dir . 'announcement-list.php';
    require_once $widgets_dir . 'agenda-calendar.php';
    require_once $widgets_dir . 'faq-accordion.php';
    require_once $widgets_dir . 'partner-logos.php';
    require_once $widgets_dir . 'gallery-grid.php';
    require_once $widgets_dir . 'prestasi-carousel.php';
    require_once $widgets_dir . 'why-choose-us.php';

    // New widgets
    require_once $widgets_dir . 'kerjasama.php';
    require_once $widgets_dir . 'dokumen.php';
    require_once $widgets_dir . 'fasilitas.php';
    require_once $widgets_dir . 'mitra-industri.php';
    require_once $widgets_dir . 'mata-kuliah.php';
    require_once $widgets_dir . 'publikasi.php';
    require_once $widgets_dir . 'beasiswa.php';
    require_once $widgets_dir . 'testimonial.php';
    require_once $widgets_dir . 'video-grid.php';
    require_once $widgets_dir . 'organisasi-mhs.php';
    require_once $widgets_dir . 'sambutan.php';

    // Register existing widgets
    $widgets_manager->register( new \CampusOS_Hero_Slider() );
    $widgets_manager->register( new \CampusOS_Stats_Counter() );
    $widgets_manager->register( new \CampusOS_Team_Grid() );
    $widgets_manager->register( new \CampusOS_News_Grid() );
    $widgets_manager->register( new \CampusOS_Announcement_List() );
    $widgets_manager->register( new \CampusOS_Agenda_Calendar() );
    $widgets_manager->register( new \CampusOS_FAQ_Accordion() );
    $widgets_manager->register( new \CampusOS_Partner_Logos() );
    $widgets_manager->register( new \CampusOS_Gallery_Grid() );
    $widgets_manager->register( new \CampusOS_Prestasi_Carousel() );
    $widgets_manager->register( new \CampusOS_Why_Choose_Us() );

    // Register new widgets
    $widgets_manager->register( new \CampusOS_Kerjasama_Widget() );
    $widgets_manager->register( new \CampusOS_Dokumen_Widget() );
    $widgets_manager->register( new \CampusOS_Fasilitas_Widget() );
    $widgets_manager->register( new \CampusOS_Mitra_Industri_Widget() );
    $widgets_manager->register( new \CampusOS_Mata_Kuliah_Widget() );
    $widgets_manager->register( new \CampusOS_Publikasi_Widget() );
    $widgets_manager->register( new \CampusOS_Beasiswa_Widget() );
    $widgets_manager->register( new \CampusOS_Testimonial_Widget() );
    $widgets_manager->register( new \CampusOS_Video_Grid_Widget() );
    $widgets_manager->register( new \CampusOS_Organisasi_Mhs_Widget() );
    $widgets_manager->register( new \CampusOS_Sambutan_Widget() );
} );
