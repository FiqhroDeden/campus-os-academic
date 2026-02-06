<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function unpatti_is_fakultas() {
    return get_theme_mod( 'unpatti_site_mode', 'prodi' ) === 'fakultas';
}

function unpatti_is_prodi() {
    return get_theme_mod( 'unpatti_site_mode', 'prodi' ) === 'prodi';
}

function unpatti_get_institution_name() {
    return get_theme_mod( 'unpatti_institution_name', get_bloginfo( 'name' ) );
}

function unpatti_primary_color() {
    return get_theme_mod( 'unpatti_primary_color', '#003d82' );
}

function unpatti_secondary_color() {
    return get_theme_mod( 'unpatti_secondary_color', '#e67e22' );
}
