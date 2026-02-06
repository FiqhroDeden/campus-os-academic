<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'customize_register', function( $wp_customize ) {

    // Panel
    $wp_customize->add_panel( 'campusos_settings', [
        'title'    => __( 'CampusOS Academic', 'campusos-academic' ),
        'priority' => 10,
    ] );

    // --- Section: Identitas Situs ---
    $wp_customize->add_section( 'campusos_identity', [
        'title' => __( 'Identitas Situs', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    // Site Mode
    $wp_customize->add_setting( 'campusos_site_mode', [
        'default'           => 'prodi',
        'sanitize_callback' => function( $val ) {
            return in_array( $val, [ 'fakultas', 'prodi' ], true ) ? $val : 'prodi';
        },
    ] );
    $wp_customize->add_control( 'campusos_site_mode', [
        'label'   => __( 'Mode Situs', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'radio',
        'choices' => [
            'fakultas' => __( 'Fakultas', 'campusos-academic' ),
            'prodi'    => __( 'Program Studi', 'campusos-academic' ),
        ],
    ] );

    // Institution name
    $wp_customize->add_setting( 'campusos_institution_name', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'campusos_institution_name', [
        'label'   => __( 'Nama Fakultas / Program Studi', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'text',
    ] );

    // Parent URL
    $wp_customize->add_setting( 'campusos_parent_url', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ] );
    $wp_customize->add_control( 'campusos_parent_url', [
        'label'   => __( 'URL Induk (Fakultas / Universitas)', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'url',
    ] );

    // Address
    $wp_customize->add_setting( 'campusos_address', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ] );
    $wp_customize->add_control( 'campusos_address', [
        'label'   => __( 'Alamat', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'textarea',
    ] );

    // Phone
    $wp_customize->add_setting( 'campusos_phone', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'campusos_phone', [
        'label'   => __( 'Telepon', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'text',
    ] );

    // Email
    $wp_customize->add_setting( 'campusos_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ] );
    $wp_customize->add_control( 'campusos_email', [
        'label'   => __( 'Email', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'email',
    ] );

    // Tampilkan Top Bar
    $wp_customize->add_setting( 'campusos_show_topbar', [
        'default'           => true,
        'sanitize_callback' => function( $val ) { return (bool) $val; },
    ] );
    $wp_customize->add_control( 'campusos_show_topbar', [
        'label'   => __( 'Tampilkan Top Bar', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'checkbox',
    ] );

    // --- Section: Warna ---
    $wp_customize->add_section( 'campusos_colors', [
        'title' => __( 'Warna Tema', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $wp_customize->add_setting( 'campusos_primary_color', [
        'default'           => '#003d82',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'campusos_primary_color', [
        'label'   => __( 'Warna Primary', 'campusos-academic' ),
        'section' => 'campusos_colors',
    ] ) );

    $wp_customize->add_setting( 'campusos_secondary_color', [
        'default'           => '#e67e22',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'campusos_secondary_color', [
        'label'   => __( 'Warna Secondary', 'campusos-academic' ),
        'section' => 'campusos_colors',
    ] ) );

    // --- Section: Media Sosial ---
    $wp_customize->add_section( 'campusos_social', [
        'title' => __( 'Media Sosial', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $socials = [
        'facebook'  => 'Facebook URL',
        'instagram' => 'Instagram URL',
        'youtube'   => 'YouTube URL',
        'twitter'   => 'Twitter / X URL',
        'tiktok'    => 'TikTok URL',
    ];
    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( "campusos_social_{$key}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( "campusos_social_{$key}", [
            'label'   => $label,
            'section' => 'campusos_social',
            'type'    => 'url',
        ] );
    }

    // --- Section: Footer ---
    $wp_customize->add_section( 'campusos_footer', [
        'title' => __( 'Footer', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $wp_customize->add_setting( 'campusos_footer_text', [
        'default'           => '© ' . gmdate('Y') . ' ',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'campusos_footer_text', [
        'label'   => __( 'Teks Footer', 'campusos-academic' ),
        'section' => 'campusos_footer',
        'type'    => 'textarea',
    ] );

    // --- Section: Tipografi ---
    $wp_customize->add_section( 'campusos_typography', [
        'title' => __( 'Tipografi', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $wp_customize->add_setting( 'campusos_font_family', [
        'default'           => 'Inter',
        'sanitize_callback' => function( $val ) {
            $allowed = [ 'Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato', 'Nunito', 'Source Sans Pro', 'Montserrat' ];
            return in_array( $val, $allowed, true ) ? $val : 'Inter';
        },
    ] );
    $wp_customize->add_control( 'campusos_font_family', [
        'label'   => __( 'Font Family', 'campusos-academic' ),
        'section' => 'campusos_typography',
        'type'    => 'select',
        'choices' => [
            'Inter'           => 'Inter',
            'Poppins'         => 'Poppins',
            'Roboto'          => 'Roboto',
            'Open Sans'       => 'Open Sans',
            'Lato'            => 'Lato',
            'Nunito'          => 'Nunito',
            'Source Sans Pro'  => 'Source Sans Pro',
            'Montserrat'      => 'Montserrat',
        ],
    ] );

    // --- Section: Beranda ---
    $wp_customize->add_section( 'campusos_homepage', [
        'title' => __( 'Beranda', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $wp_customize->add_setting( 'campusos_homepage_sections', [
        'default'           => 'hero,news,announcement,agenda,gallery,stats,faq,partner',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'campusos_homepage_sections', [
        'label'       => __( 'Urutan Bagian Beranda', 'campusos-academic' ),
        'description' => __( 'Pisahkan dengan koma. Bagian tersedia: hero, news, announcement, agenda, gallery, stats, faq, partner', 'campusos-academic' ),
        'section'     => 'campusos_homepage',
        'type'        => 'text',
    ] );
} );
