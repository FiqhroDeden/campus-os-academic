<?php

function register_achievement_widget( $widgets_manager ) {
	require_once( __DIR__ . '/achievement-widget.php' );
	$widgets_manager->register( new \Edunia_Achievement() );
}
add_action( 'elementor/widgets/register', 'register_achievement_widget' );

function register_angenda_widget( $widgets_manager ) {
	require_once( __DIR__ . '/angenda-widget.php' );
	$widgets_manager->register( new \Edunia_Angenda() );
}
add_action( 'elementor/widgets/register', 'register_angenda_widget' );

function register_facility_widget( $widgets_manager ) {
	require_once( __DIR__ . '/facility-widget.php' );
	$widgets_manager->register( new \Edunia_Facility() );
}
add_action( 'elementor/widgets/register', 'register_facility_widget' );

function register_featuredwrapper_widget( $widgets_manager ) {
	require_once( __DIR__ . '/featuredwrapper-widget.php' );
	$widgets_manager->register( new \Edunia_FeaturedWrapper() );
}
add_action( 'elementor/widgets/register', 'register_featuredwrapper_widget' );

function register_gallery_widget( $widgets_manager ) {
	require_once( __DIR__ . '/gallery-widget.php' );
	$widgets_manager->register( new \Edunia_Gallery() );
}
add_action( 'elementor/widgets/register', 'register_gallery_widget' );

function register_gtk_widget( $widgets_manager ) {
	require_once( __DIR__ . '/gtk-widget.php' );
	$widgets_manager->register( new \Edunia_Gtk() );
}
add_action( 'elementor/widgets/register', 'register_gtk_widget' );

function register_latestnews_widget( $widgets_manager ) {
	require_once( __DIR__ . '/latestnews-widget.php' );
	$widgets_manager->register( new \Edunia_LatestNews() );
}
add_action( 'elementor/widgets/register', 'register_latestnews_widget' );

function register_partnership_widget( $widgets_manager ) {
	require_once( __DIR__ . '/partnership-widget.php' );
	$widgets_manager->register( new \Edunia_PartnerShip() );
}
add_action( 'elementor/widgets/register', 'register_partnership_widget' );

function register_testimonial_widget( $widgets_manager ) {
	require_once( __DIR__ . '/testimonial-widget.php' );
	$widgets_manager->register( new \Edunia_Testimonial() );
}
add_action( 'elementor/widgets/register', 'register_testimonial_widget' );

function register_video_widget( $widgets_manager ) {
	require_once( __DIR__ . '/video-widget.php' );
	$widgets_manager->register( new \Edunia_Video() );
}
add_action( 'elementor/widgets/register', 'register_video_widget' );