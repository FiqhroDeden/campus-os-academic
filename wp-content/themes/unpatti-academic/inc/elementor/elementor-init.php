<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Only load if Elementor is active
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category( 'unpatti-academic', [
        'title' => __( 'UNPATTI Academic', 'unpatti-academic' ),
        'icon'  => 'fa fa-university',
    ] );
} );

add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    $widgets_dir = UNPATTI_THEME_PATH . '/inc/elementor/widgets/';

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

    $widgets_manager->register( new \UNPATTI_Hero_Slider() );
    $widgets_manager->register( new \UNPATTI_Stats_Counter() );
    $widgets_manager->register( new \UNPATTI_Team_Grid() );
    $widgets_manager->register( new \UNPATTI_News_Grid() );
    $widgets_manager->register( new \UNPATTI_Announcement_List() );
    $widgets_manager->register( new \UNPATTI_Agenda_Calendar() );
    $widgets_manager->register( new \UNPATTI_FAQ_Accordion() );
    $widgets_manager->register( new \UNPATTI_Partner_Logos() );
    $widgets_manager->register( new \UNPATTI_Gallery_Grid() );
    $widgets_manager->register( new \UNPATTI_Prestasi_Carousel() );
    $widgets_manager->register( new \UNPATTI_Why_Choose_Us() );
} );
