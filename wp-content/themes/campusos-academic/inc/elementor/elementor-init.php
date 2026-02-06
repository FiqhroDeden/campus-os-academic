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
} );
