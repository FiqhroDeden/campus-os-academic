<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'UNPATTI_THEME_VERSION', '1.0.0' );
define( 'UNPATTI_THEME_PATH', get_template_directory() );
define( 'UNPATTI_THEME_URI', get_template_directory_uri() );

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
        'primary' => __( 'Menu Utama', 'unpatti-academic' ),
        'footer'  => __( 'Menu Footer', 'unpatti-academic' ),
    ] );

    add_image_size( 'unpatti-card', 400, 300, true );
    add_image_size( 'unpatti-hero', 1920, 600, true );
    add_image_size( 'unpatti-profile', 300, 300, true );
} );

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'dashicons' ); // Load dashicons for frontend icons
    wp_enqueue_style( 'unpatti-academic', UNPATTI_THEME_URI . '/assets/css/main.css', [], UNPATTI_THEME_VERSION );
    wp_enqueue_script( 'unpatti-academic', UNPATTI_THEME_URI . '/assets/js/main.js', [], UNPATTI_THEME_VERSION, true );

    // Google Font
    $font_family = get_theme_mod( 'unpatti_font_family', 'Inter' );
    $font_slug   = str_replace( ' ', '+', $font_family );
    if ( $font_family !== 'Inter' ) {
        wp_enqueue_style( 'unpatti-google-font', 'https://fonts.googleapis.com/css2?family=' . $font_slug . ':wght@400;500;600;700&display=swap', [], null );
    }

    $primary = get_theme_mod( 'unpatti_primary_color', '#003d82' );
    $secondary = get_theme_mod( 'unpatti_secondary_color', '#e67e22' );
    $css = ":root {
        --unpatti-primary: {$primary};
        --unpatti-secondary: {$secondary};
        --unpatti-font-family: '{$font_family}', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    body { font-family: var(--unpatti-font-family); }";
    wp_add_inline_style( 'unpatti-academic', $css );
} );

// Setup wizard
require_once UNPATTI_THEME_PATH . '/inc/setup-wizard/class-setup-wizard.php';
new \UNPATTI_Setup_Wizard();

require_once UNPATTI_THEME_PATH . '/inc/customizer/customizer.php';
require_once UNPATTI_THEME_PATH . '/inc/template-functions.php';
require_once UNPATTI_THEME_PATH . '/inc/social-share.php';
require_once UNPATTI_THEME_PATH . '/inc/breadcrumbs.php';

// Elementor integration
if ( did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' ) ) {
    require_once UNPATTI_THEME_PATH . '/inc/elementor/elementor-init.php';
} else {
    add_action( 'plugins_loaded', function() {
        if ( did_action( 'elementor/loaded' ) ) {
            require_once UNPATTI_THEME_PATH . '/inc/elementor/elementor-init.php';
        }
    } );
}

// Register sidebar
add_action( 'widgets_init', function() {
    register_sidebar( [
        'name'          => __( 'Sidebar', 'unpatti-academic' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
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
