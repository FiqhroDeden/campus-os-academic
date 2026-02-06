<?php
/*
 * Template Name: Beranda
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$sections_raw = get_theme_mod( 'unpatti_homepage_sections', 'hero,news,announcement,agenda,gallery,stats,faq,partner' );
$sections     = array_map( 'trim', explode( ',', $sections_raw ) );
$allowed      = [ 'hero', 'news', 'announcement', 'agenda', 'gallery', 'stats', 'faq', 'partner' ];

foreach ( $sections as $section ) {
    $section = sanitize_file_name( $section );
    if ( in_array( $section, $allowed, true ) ) {
        get_template_part( 'template-parts/homepage', $section );
    }
}

get_footer();
