<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function campusos_is_fakultas() {
    return get_theme_mod( 'campusos_site_mode', 'prodi' ) === 'fakultas';
}

function campusos_is_prodi() {
    return get_theme_mod( 'campusos_site_mode', 'prodi' ) === 'prodi';
}

function campusos_get_institution_name() {
    return get_theme_mod( 'campusos_institution_name', get_bloginfo( 'name' ) );
}

function campusos_primary_color() {
    return get_theme_mod( 'campusos_primary_color', '#003d82' );
}

function campusos_secondary_color() {
    return get_theme_mod( 'campusos_secondary_color', '#e67e22' );
}
