<?php

/*
* Template Name: Beranda
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

$sort_section = get_theme_mod( 'sort_section', 'slider,latnews,angen,achievement,gallery,video,facility,gtk,testimonial,partnership' );
$home_section = explode( ',', $sort_section );

foreach( $home_section as $section ) {
    get_home_section( $section );
}

get_footer();

?>