<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'customize_register', function( $wp_customize ) {

    // Panel
    $wp_customize->add_panel( 'unpatti_settings', [
        'title'    => __( 'UNPATTI Academic', 'unpatti-academic' ),
        'priority' => 10,
    ] );

    // --- Section: Identitas Situs ---
    $wp_customize->add_section( 'unpatti_identity', [
        'title' => __( 'Identitas Situs', 'unpatti-academic' ),
        'panel' => 'unpatti_settings',
    ] );

    // Site Mode
    $wp_customize->add_setting( 'unpatti_site_mode', [
        'default'           => 'prodi',
        'sanitize_callback' => function( $val ) {
            return in_array( $val, [ 'fakultas', 'prodi' ], true ) ? $val : 'prodi';
        },
    ] );
    $wp_customize->add_control( 'unpatti_site_mode', [
        'label'   => __( 'Mode Situs', 'unpatti-academic' ),
        'section' => 'unpatti_identity',
        'type'    => 'radio',
        'choices' => [
            'fakultas' => __( 'Fakultas', 'unpatti-academic' ),
            'prodi'    => __( 'Program Studi', 'unpatti-academic' ),
        ],
    ] );

    // Institution name
    $wp_customize->add_setting( 'unpatti_institution_name', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'unpatti_institution_name', [
        'label'   => __( 'Nama Fakultas / Program Studi', 'unpatti-academic' ),
        'section' => 'unpatti_identity',
        'type'    => 'text',
    ] );

    // Parent URL
    $wp_customize->add_setting( 'unpatti_parent_url', [
        'default'           => 'https://unpatti.ac.id',
        'sanitize_callback' => 'esc_url_raw',
    ] );
    $wp_customize->add_control( 'unpatti_parent_url', [
        'label'   => __( 'URL Induk (Fakultas / Universitas)', 'unpatti-academic' ),
        'section' => 'unpatti_identity',
        'type'    => 'url',
    ] );

    // Address
    $wp_customize->add_setting( 'unpatti_address', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ] );
    $wp_customize->add_control( 'unpatti_address', [
        'label'   => __( 'Alamat', 'unpatti-academic' ),
        'section' => 'unpatti_identity',
        'type'    => 'textarea',
    ] );

    // Phone
    $wp_customize->add_setting( 'unpatti_phone', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'unpatti_phone', [
        'label'   => __( 'Telepon', 'unpatti-academic' ),
        'section' => 'unpatti_identity',
        'type'    => 'text',
    ] );

    // Email
    $wp_customize->add_setting( 'unpatti_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ] );
    $wp_customize->add_control( 'unpatti_email', [
        'label'   => __( 'Email', 'unpatti-academic' ),
        'section' => 'unpatti_identity',
        'type'    => 'email',
    ] );

    // --- Section: Warna ---
    $wp_customize->add_section( 'unpatti_colors', [
        'title' => __( 'Warna Tema', 'unpatti-academic' ),
        'panel' => 'unpatti_settings',
    ] );

    $wp_customize->add_setting( 'unpatti_primary_color', [
        'default'           => '#003d82',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'unpatti_primary_color', [
        'label'   => __( 'Warna Primary', 'unpatti-academic' ),
        'section' => 'unpatti_colors',
    ] ) );

    $wp_customize->add_setting( 'unpatti_secondary_color', [
        'default'           => '#e67e22',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'unpatti_secondary_color', [
        'label'   => __( 'Warna Secondary', 'unpatti-academic' ),
        'section' => 'unpatti_colors',
    ] ) );

    // --- Section: Media Sosial ---
    $wp_customize->add_section( 'unpatti_social', [
        'title' => __( 'Media Sosial', 'unpatti-academic' ),
        'panel' => 'unpatti_settings',
    ] );

    $socials = [
        'facebook'  => 'Facebook URL',
        'instagram' => 'Instagram URL',
        'youtube'   => 'YouTube URL',
        'twitter'   => 'Twitter / X URL',
        'tiktok'    => 'TikTok URL',
    ];
    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( "unpatti_social_{$key}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( "unpatti_social_{$key}", [
            'label'   => $label,
            'section' => 'unpatti_social',
            'type'    => 'url',
        ] );
    }

    // --- Section: Footer ---
    $wp_customize->add_section( 'unpatti_footer', [
        'title' => __( 'Footer', 'unpatti-academic' ),
        'panel' => 'unpatti_settings',
    ] );

    $wp_customize->add_setting( 'unpatti_footer_text', [
        'default'           => '© ' . gmdate('Y') . ' Universitas Pattimura',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'unpatti_footer_text', [
        'label'   => __( 'Teks Footer', 'unpatti-academic' ),
        'section' => 'unpatti_footer',
        'type'    => 'textarea',
    ] );
} );
