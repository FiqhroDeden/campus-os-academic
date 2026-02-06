<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function edunia_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'edunia_content_width', 640 );
}
add_action( 'after_setup_theme', 'edunia_content_width', 0 );

if ( get_theme_mod( 'post_editor', 'gutenberg' )  == 'classic' ) {
	add_filter( 'use_block_editor_for_post', '__return_false', 10 );
}

require get_template_directory() . '/inc/garudatheme.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/metaboxes.php';
require get_template_directory() . '/widgets/latest-agenda.php';
require get_template_directory() . '/widgets/latest-announcement.php';
require get_template_directory() . '/widgets/school-details.php';
require get_template_directory() . '/widgets/elementor/register-widgets.php';