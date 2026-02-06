<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function edunia_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( get_theme_mod( 'topbar_isactive', 0 ) == 0 ) {
		$classes[] = 'hide-topbar';
	} 

	return $classes;
}
add_filter( 'body_class', 'edunia_body_classes' );

function edunia_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'edunia_pingback_header' );

function custom_type_archive_display( $query ) {
$announcement_la = get_theme_mod( 'announcement_limit_archive', 'default' );
$agenda_la = get_theme_mod( 'agenda_limit_archive', 'default' );
$achievement_la = get_theme_mod( 'achievement_limit_archive', 'default' );
$gallery_la = get_theme_mod( 'gallery_limit_archive', 'default' );
$video_la = get_theme_mod( 'video_limit_archive', 'default' );
$facility_la = get_theme_mod( 'facility_limit_archive', 'default' );
$gtk_la = get_theme_mod( 'gtk_limit_archive', 'default' );
$download_la = get_theme_mod( 'download_limit_archive', 'default' );

if ( $announcement_la !== 'default' ) {
	$announcement_la = ($announcement_la == 'nolimit')?'-1':$announcement_la;
	if ( is_post_type_archive( 'pengumuman' ) ) {
		$query->set( 'posts_per_page', $announcement_la );
	}
}

if ( $agenda_la !== 'default' ) {
	$agenda_la = ($agenda_la == 'nolimit')?'-1':$agenda_la;
	if ( is_post_type_archive( 'agenda' ) ) {
		$query->set( 'posts_per_page', $agenda_la );
	}
}

if ( $achievement_la !== 'default' ) {
	$achievement_la = ($achievement_la == 'nolimit')?'-1':$achievement_la;
	if ( is_post_type_archive( 'prestasi' ) ) {
		$query->set( 'posts_per_page', $achievement_la );
	}
}

if ( $gallery_la !== 'default' ) {
	$gallery_la = ($gallery_la == 'nolimit')?'-1':$gallery_la;
	if ( is_post_type_archive( 'galeri' ) ) {
		$query->set( 'posts_per_page', $gallery_la );
	}
}

if ( $video_la !== 'default' ) {
	$video_la = ($video_la == 'nolimit')?'-1':$video_la;
	if ( is_post_type_archive( 'video' ) ) {
		$query->set( 'posts_per_page', $video_la );
	}
}

if ( $facility_la !== 'default' ) {
	$facility_la = ($facility_la == 'nolimit')?'-1':$facility_la;
	if ( is_post_type_archive( 'fasilitas' ) ) {
		$query->set( 'posts_per_page', $facility_la );
	}
}

if ( $gtk_la !== 'default' ) {
	$gtk_la = ($gtk_la == 'nolimit')?'-1':$gtk_la;
	if ( is_post_type_archive( 'gtk' ) ) {
		$query->set( 'posts_per_page', $gtk_la );
	}
}

if ( $download_la !== 'default' ) {
	$download_la = ($download_la == 'nolimit')?'-1':$download_la;
	if ( is_post_type_archive( 'download' ) ) {
		$query->set( 'posts_per_page', $download_la );
	}
}

if ($query->is_search && !$query->is_admin && empty($query->query_vars['post_type'])) {
	$query->set('post_type', 'post');
}

return;
}
add_action( 'pre_get_posts', 'custom_type_archive_display' );

function get_home_section( $section = '' ) {
	if ( ! empty( $section ) ) {
		get_template_part( 'template-parts/sections/' . $section );
	}
}

function get_section_background( $color = '#ffffff', $style = 'solid', $image = '', $fixed = 0 ) {
	if ( $style == 'solid' ) {
		echo 'background:' . $color . ';';
	} else if ( $style == 'gradient_one' ) {
		echo 'background:' . $color . ';background:linear-gradient(270deg, #ffffff 10%, ' . $color . ' 100%);';
	} else if ( $style == 'gradient_two' ) {
		echo 'background:' . $color . ';background:linear-gradient(90deg, #ffffff 10%, ' . $color . ' 100%);';
	}

	if ( ! empty( $image ) ) {
		echo 'background-image:url(\'' . $image . '\');background-position:center;background-repeat:no-repeat;background-size:cover;';
	}

	if ( ! empty( $image ) && $fixed == 1 ) {
		echo 'background-attachment:fixed;';
	}
}