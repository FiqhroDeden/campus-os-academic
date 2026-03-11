<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CAMPUSOS_THEME_VERSION', '1.2.3' );
define( 'CAMPUSOS_THEME_PATH', get_template_directory() );
define( 'CAMPUSOS_THEME_URI', get_template_directory_uri() );

// Block frontend if CampusOS Academic Core plugin is not active
add_action( 'template_redirect', function() {
    if ( defined( 'CAMPUSOS_CORE_PATH' ) ) return;
    if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) return;
    if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) return;
    if ( defined( 'WP_CLI' ) && WP_CLI ) return;

    wp_die(
        '<h1>' . esc_html__( 'Situs Tidak Tersedia', 'campusos-academic' ) . '</h1>' .
        '<p>' . esc_html__( 'Plugin CampusOS Academic Core harus diaktifkan untuk menggunakan tema ini.', 'campusos-academic' ) . '</p>' .
        '<p><a href="' . esc_url( wp_login_url() ) . '">' . esc_html__( 'Login Administrator', 'campusos-academic' ) . '</a></p>',
        esc_html__( 'Plugin Diperlukan', 'campusos-academic' ),
        [ 'response' => 503 ]
    );
}, 1 );

// Admin notice if plugin is not active
add_action( 'admin_notices', function() {
    if ( defined( 'CAMPUSOS_CORE_PATH' ) ) return;
    if ( ! current_user_can( 'manage_options' ) ) return;
    echo '<div class="notice notice-error"><p>';
    esc_html_e( 'CampusOS Academic: Plugin CampusOS Academic Core harus diaktifkan untuk menggunakan tema ini.', 'campusos-academic' );
    echo '</p></div>';
} );

// Theme setup
add_action( 'after_setup_theme', function() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );

    register_nav_menus( [
        'primary'              => __( 'Menu Utama', 'campusos-academic' ),
        'footer'               => __( 'Menu Footer', 'campusos-academic' ),
        'footer-akademik'      => __( 'Footer - Kolom 2', 'campusos-academic' ),
        'footer-kemahasiswaan' => __( 'Footer - Kolom 3', 'campusos-academic' ),
        'footer-alumni'        => __( 'Footer - Kolom 4', 'campusos-academic' ),
    ] );

    add_image_size( 'campusos-card', 400, 300, true );
    add_image_size( 'campusos-hero', 1920, 600, true );
    add_image_size( 'campusos-profile', 300, 300, true );
} );

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
    $suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

    wp_enqueue_style( 'dashicons' ); // Load dashicons for frontend icons
    wp_enqueue_style( 'campusos-academic', CAMPUSOS_THEME_URI . '/assets/css/main' . $suffix . '.css', [], CAMPUSOS_THEME_VERSION );
    wp_enqueue_script( 'campusos-academic', CAMPUSOS_THEME_URI . '/assets/js/main' . $suffix . '.js', [], CAMPUSOS_THEME_VERSION, [ 'in_footer' => true, 'strategy' => 'defer' ] );

    // Google Font
    $font_family = get_theme_mod( 'campusos_font_family', 'Inter' );
    $font_slug   = str_replace( ' ', '+', $font_family );
    if ( $font_family !== 'Inter' ) {
        wp_enqueue_style( 'campusos-google-font', 'https://fonts.googleapis.com/css2?family=' . $font_slug . ':wght@400;500;600;700&display=swap', [], null );
    }

    $primary   = get_theme_mod( 'campusos_primary_color', '#003d82' );
    $secondary = get_theme_mod( 'campusos_secondary_color', '#e67e22' );

    // Compute light/dark variants from hex colors
    $pr = $pg = $pb = 0;
    sscanf( $primary, '#%02x%02x%02x', $pr, $pg, $pb );
    $primary_light = sprintf( '#%02x%02x%02x',
        $pr + (int) ( ( 255 - $pr ) * 0.9 ),
        $pg + (int) ( ( 255 - $pg ) * 0.9 ),
        $pb + (int) ( ( 255 - $pb ) * 0.9 )
    );
    $primary_dark = sprintf( '#%02x%02x%02x',
        max( 0, (int) ( $pr * 0.7 ) ),
        max( 0, (int) ( $pg * 0.7 ) ),
        max( 0, (int) ( $pb * 0.7 ) )
    );

    $sr = $sg = $sb = 0;
    sscanf( $secondary, '#%02x%02x%02x', $sr, $sg, $sb );
    $secondary_light = sprintf( '#%02x%02x%02x',
        $sr + (int) ( ( 255 - $sr ) * 0.9 ),
        $sg + (int) ( ( 255 - $sg ) * 0.9 ),
        $sb + (int) ( ( 255 - $sb ) * 0.9 )
    );
    $secondary_dark = sprintf( '#%02x%02x%02x',
        max( 0, (int) ( $sr * 0.7 ) ),
        max( 0, (int) ( $sg * 0.7 ) ),
        max( 0, (int) ( $sb * 0.7 ) )
    );

    $css = ":root {
        --campusos-primary: {$primary};
        --campusos-primary-light: {$primary_light};
        --campusos-primary-dark: {$primary_dark};
        --campusos-secondary: {$secondary};
        --campusos-secondary-light: {$secondary_light};
        --campusos-secondary-dark: {$secondary_dark};
        --campusos-font-family: '{$font_family}', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    body { font-family: var(--campusos-font-family); }";
    wp_add_inline_style( 'campusos-academic', $css );
} );

// Preconnect for Google Fonts
add_action( 'wp_head', function() {
    $font_family = get_theme_mod( 'campusos_font_family', 'Inter' );
    if ( $font_family !== 'Inter' ) {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" />' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />' . "\n";
    }
}, 1 );

// Setup wizard
require_once CAMPUSOS_THEME_PATH . '/inc/setup-wizard/class-setup-wizard.php';
new \CampusOS_Setup_Wizard();

require_once CAMPUSOS_THEME_PATH . '/inc/customizer/customizer.php';
require_once CAMPUSOS_THEME_PATH . '/inc/template-functions.php';
require_once CAMPUSOS_THEME_PATH . '/inc/social-share.php';
require_once CAMPUSOS_THEME_PATH . '/inc/breadcrumbs.php';

// Elementor integration
if ( did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' ) ) {
    require_once CAMPUSOS_THEME_PATH . '/inc/elementor/elementor-init.php';
} else {
    add_action( 'plugins_loaded', function() {
        if ( did_action( 'elementor/loaded' ) ) {
            require_once CAMPUSOS_THEME_PATH . '/inc/elementor/elementor-init.php';
        }
    } );
}

// Register sidebar
add_action( 'widgets_init', function() {
    register_sidebar( [
        'name'          => __( 'Sidebar', 'campusos-academic' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
} );

// Create dummy footer menus on first load
add_action( 'init', function() {
    if ( get_option( 'campusos_footer_menus_created' ) ) {
        return;
    }

    $footer_menus = [
        'footer-akademik' => [
            'name'  => 'Sistem Terkait',
            'items' => [ 'SIAKAD', 'PMB', 'E-Learning', 'Repository', 'Jurnal' ],
        ],
        'footer-kemahasiswaan' => [
            'name'  => 'Unit Penunjang',
            'items' => [ 'Perpustakaan', 'Laboratorium', 'UPT Bahasa', 'UPT TIK', 'Pusat Karir' ],
        ],
        'footer-alumni' => [
            'name'  => 'Lembaga Terkait',
            'items' => [ 'LPPM', 'LPM', 'LP3M', 'BPM', 'Senat' ],
        ],
    ];

    $locations = get_theme_mod( 'nav_menu_locations', [] );

    foreach ( $footer_menus as $location => $config ) {
        // Skip if menu location already assigned
        if ( ! empty( $locations[ $location ] ) ) {
            continue;
        }

        $menu_id = wp_create_nav_menu( $config['name'] );
        if ( is_wp_error( $menu_id ) ) {
            // Menu might already exist, try to get it
            $existing = wp_get_nav_menu_object( $config['name'] );
            if ( $existing ) {
                $menu_id = $existing->term_id;
            } else {
                continue;
            }
        }

        foreach ( $config['items'] as $position => $title ) {
            wp_update_nav_menu_item( $menu_id, 0, [
                'menu-item-title'  => $title,
                'menu-item-url'    => '#',
                'menu-item-status' => 'publish',
                'menu-item-position' => $position + 1,
                'menu-item-type'   => 'custom',
            ] );
        }

        $locations[ $location ] = $menu_id;
    }

    set_theme_mod( 'nav_menu_locations', $locations );
    update_option( 'campusos_footer_menus_created', true );
} );

// Redirect pimpinan archive to sambutan page
// Pimpinan CPT is only used for single leader data, integrated with Sambutan page
add_action( 'template_redirect', function() {
    if ( is_post_type_archive( 'pimpinan' ) ) {
        $sambutan_page = get_page_by_path( 'sambutan' );
        if ( $sambutan_page ) {
            wp_redirect( get_permalink( $sambutan_page->ID ), 301 );
        } else {
            wp_redirect( home_url( '/' ), 301 );
        }
        exit;
    }
} );
