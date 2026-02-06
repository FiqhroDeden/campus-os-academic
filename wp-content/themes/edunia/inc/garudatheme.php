<?php

defined( 'ABSPATH' ) || exit;

final class Garudatheme_Init {
	private $theme;
	private $path;
	private $uri;

	public function __construct() {
		$this->theme = [
			'name' => 'Edunia',
			'slug' => 'edunia',
			'version' => '2.1',
		];
		$this->path = get_template_directory() . '/inc/src';
		$this->uri = get_template_directory_uri() . '/inc/src';

		$this->load_dependencies();

		add_action( 'init', [$this, 'init'] );
		add_action( 'tgmpa_register', [$this, 'tgmpa_data'] );
		add_action( 'admin_menu', [$this, 'register_admin_menu'] );
		add_action( 'wp_enqueue_scripts', [$this, 'wp_enqueue_scripts'] );
		add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );
		add_action( 'admin_head', [$this, 'admin_head'] );
		add_filter( 'ocdi/import_files', [$this, 'ocdi_import_files'] );
		add_action( 'ocdi/after_import', [$this, 'ocdi_after_import'] );
		add_action( 'after_setup_theme', [$this, 'after_setup_theme'] );
		add_action( 'after_switch_theme', [$this, 'redirect_to_installation_wizard'] );
		add_action( 'widgets_init', [$this, 'widgets_init'] );
		add_action( 'wp_ajax_garudatheme_license_verification', [$this, 'license_ajax_handler'] );
		add_filter( 'pre_set_site_transient_update_themes', [$this, 'update_check'] );
		add_action( 'template_redirect', [$this, 'template_redirect'] );
	}

	private function load_dependencies() {
		require_once( $this->path . '/class-tgm-plugin-activation.php' );
		require_once( $this->path . '/customize-controls.php' );
	}

	public function init() {
		$archive_title = array(
			'announcement' => get_theme_mod( 'announcement_archive_title' ),
			'agenda' => get_theme_mod( 'agenda_archive_title' ),
			'achievement' => get_theme_mod( 'achievement_archive_title' ),
			'gallery' => get_theme_mod( 'gallery_archive_title' ),
			'video' => get_theme_mod( 'video_archive_title' ),
			'facility' => get_theme_mod( 'facility_archive_title' ),
			'gtk' => get_theme_mod( 'gtk_archive_title' ),
			'download' => get_theme_mod( 'download_archive_title' ),
		);
	
		$announcement_title = ( ( ! empty( $archive_title['announcement'] ) ) ? $archive_title['announcement'] : __( 'Announcement', 'edunia') );
		$agenda_title = ( ( ! empty( $archive_title['agenda'] ) ) ? $archive_title['agenda'] : __( 'Agenda', 'edunia') );
		$achievement_title = ( ( ! empty( $archive_title['achievement'] ) ) ? $archive_title['achievement'] : __( 'Achievement', 'edunia') );
		$gallery_title = ( ( ! empty( $archive_title['gallery'] ) ) ? $archive_title['gallery'] : __( 'Gallery', 'edunia') );
		$video_title = ( ( ! empty( $archive_title['video'] ) ) ? $archive_title['video'] : __( 'Video', 'edunia') );
		$facility_title = ( ( ! empty( $archive_title['facility'] ) ) ? $archive_title['facility'] : __( 'Facility', 'edunia') );
		$gtk_title = ( ( ! empty( $archive_title['gtk'] ) ) ? $archive_title['gtk'] : __( 'GTK', 'edunia') );
		$download_title = ( ( ! empty( $archive_title['download'] ) ) ? $archive_title['download'] : __( 'Download', 'edunia') );
	 
		$announcement = array(
			'label'             => $announcement_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'pengumuman' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-megaphone',
		);
		
		$agenda = array(
			'label'             => $agenda_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'agenda' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-calendar-alt',
		);
		
		$achievement = array(
			'label'             => $achievement_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'prestasi' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-awards',
		);
	
		$gallery = array(
			'label'              => $gallery_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'galeri' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-format-gallery',
		);
	
		$video = array(
			'label'              => $video_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'video' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-format-video',
		);
	
		$facility = array(
			'label'              => $facility_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'fasilitas' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-image-filter',
		);
	
		$gtk = array(
			'label'             => $gtk_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'gtk' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments', 'excerpt' ),
			'menu_icon' => 'dashicons-welcome-learn-more',
		);
	
		$testimonial = array(
			'label'             => 'Testimonial',
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'testimonial' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-editor-quote',
		);
	
		$partner = array(
			'label'             => __('Partner', 'edunia'),
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'mitra' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-universal-access',
		);
	
		$downloads = array(
			'label'             => $download_title,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'download' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 4,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'excerpt', 'thumbnail', 'comments' ),
			'menu_icon' => 'dashicons-download',
		);
	 
		register_post_type( 'pengumuman', $announcement );
		register_post_type( 'agenda', $agenda );
		register_post_type( 'prestasi', $achievement );
		register_post_type( 'galeri', $gallery );
		register_post_type( 'video', $video );
		register_post_type( 'fasilitas', $facility );
		register_post_type( 'gtk', $gtk );
		register_post_type( 'testimonial', $testimonial );
		register_post_type( 'mitra', $partner );
		register_post_type( 'download', $downloads );
	}

	/**
	 * Frontend Scripts 
	 */
	public function wp_enqueue_scripts() {
		$primary_color = get_theme_mod('primary_color', '#269db8');
		$primary_color_hover = get_theme_mod('primary_color_hover', '#1c859d');
		$background_topbar = get_theme_mod('background_topbar', '#121314');
		$background_topbar_language = get_theme_mod('background_topbar_language', '#269db8');
		$background_footer = get_theme_mod('background_footer', '#121314');
		$font_family = explode( ',', get_theme_mod( 'font_family_new', 'heebo,sans-serif' ) );
		$font_family_new = ucwords( str_replace( '-', ' ', $font_family[0] ) );
		$logo_height = get_theme_mod('logo_height', '48');
		$logo_height_fixed = get_theme_mod('logo_height_fixed', '36');
		$latnews_padtop = get_theme_mod('latnews_padtop', '60');
		$latnews_padtop_mobile = get_theme_mod('latnews_padtop_mobile', '40');
		$latnews_padbot = get_theme_mod('latnews_padbot', '60');
		$latnews_padbot_mobile = get_theme_mod('latnews_padbot_mobile', '40');
		$angen_padtop = get_theme_mod('angen_padtop', '60');
		$angen_padtop_mobile = get_theme_mod('angen_padtop_mobile', '40');
		$angen_padbot = get_theme_mod('angen_padbot', '60');
		$angen_padbot_mobile = get_theme_mod('angen_padbot_mobile', '40');
		$achievement_padtop = get_theme_mod('achievement_padtop', '60');
		$achievement_padtop_mobile = get_theme_mod('achievement_padtop_mobile', '40');
		$achievement_padbot = get_theme_mod('achievement_padbot', '60');
		$achievement_padbot_mobile = get_theme_mod('achievement_padbot_mobile', '40');
		$gallery_padtop = get_theme_mod('gallery_padtop', '60');
		$gallery_padtop_mobile = get_theme_mod('gallery_padtop_mobile', '40');
		$gallery_padbot = get_theme_mod('gallery_padbot', '60');
		$gallery_padbot_mobile = get_theme_mod('gallery_padbot_mobile', '40');
		$video_padtop = get_theme_mod('video_padtop', '60');
		$video_padtop_mobile = get_theme_mod('video_padtop_mobile', '40');
		$video_padbot = get_theme_mod('video_padbot', '30');
		$video_padbot_mobile = get_theme_mod('video_padbot_mobile', '20');
		$facility_padtop = get_theme_mod('facility_padtop', '30');
		$facility_padtop_mobile = get_theme_mod('facility_padtop_mobile', '20');
		$facility_padbot = get_theme_mod('facility_padbot', '60');
		$facility_padbot_mobile = get_theme_mod('facility_padbot_mobile', '40');
		$gtk_padtop = get_theme_mod('gtk_padtop', '60');
		$gtk_padtop_mobile = get_theme_mod('gtk_padtop_mobile', '40');
		$gtk_padbot = get_theme_mod('gtk_padbot', '60');
		$gtk_padbot_mobile = get_theme_mod('gtk_padbot_mobile', '40');
		$testimonial_padtop = get_theme_mod('testimonial_padtop', '60');
		$testimonial_padtop_mobile = get_theme_mod('testimonial_padtop_mobile', '40');
		$testimonial_padbot = get_theme_mod('testimonial_padbot', '30');
		$testimonial_padbot_mobile = get_theme_mod('testimonial_padbot_mobile', '20');
		$partnership_padtop = get_theme_mod('partnership_padtop', '30');
		$partnership_padtop_mobile = get_theme_mod('partnership_padtop_mobile', '20');
		$partnership_padbot = get_theme_mod('partnership_padbot', '60');
		$partnership_padbot_mobile = get_theme_mod('partnership_padbot_mobile', '40');

		wp_enqueue_style( 'font-family', 'https://fonts.googleapis.com/css?family=' . $font_family_new . ':400,500,700&display=swap', array(), $this->theme['version'] );

		wp_enqueue_style( 'edunia-style', get_stylesheet_uri(), array(), $this->theme['version'] );

		$custom_css = ':root{--color-primary:'.$primary_color.';--color-primary-hover:'.$primary_color_hover.';--background-topbar:'.$background_topbar.';--background-topbar-language:'.$background_topbar_language.';--background-footer:'.$background_footer.';--font-family: \'' . $font_family_new . '\', '.$font_family[1].';--logo-height:'.$logo_height.'px;--logo-height-fixed:'.$logo_height_fixed.'px;--latnews-padtop:'.$latnews_padtop.'px;--latnews-padtop-mobile:'.$latnews_padtop_mobile.'px;--latnews-padbot:'.$latnews_padbot.'px;--latnews-padbot-mobile:'.$latnews_padbot_mobile.'px;--angen-padtop:'.$angen_padtop.'px;--angen-padtop-mobile:'.$angen_padtop_mobile.'px;--angen-padbot:'.$angen_padbot.'px;--angen-padbot-mobile:'.$angen_padbot_mobile.'px;--achievement-padtop:'.$achievement_padtop.'px;--achievement-padtop-mobile:'.$achievement_padtop_mobile.'px;--achievement-padbot:'.$achievement_padbot.'px;--achievement-padbot-mobile:'.$achievement_padbot_mobile.'px;--gallery-padtop:'.$gallery_padtop.'px;--gallery-padtop-mobile:'.$gallery_padtop_mobile.'px;--gallery-padbot:'.$gallery_padbot.'px;--gallery-padbot-mobile:'.$gallery_padbot_mobile.'px;--video-padtop:'.$video_padtop.'px;--video-padtop-mobile:'.$video_padtop_mobile.'px;--video-padbot:'.$video_padbot.'px;--video-padbot-mobile:'.$video_padbot_mobile.'px;--facility-padtop:'.$facility_padtop.'px;--facility-padtop-mobile:'.$facility_padtop_mobile.'px;--facility-padbot:'.$facility_padbot.'px;--facility-padbot-mobile:'.$facility_padbot_mobile.'px;--gtk-padtop:'.$gtk_padtop.'px;--gtk-padtop-mobile:'.$gtk_padtop_mobile.'px;--gtk-padbot:'.$gtk_padbot.'px;--gtk-padbot-mobile:'.$gtk_padbot_mobile.'px;--testimonial-padtop:'.$testimonial_padtop.'px;--testimonial-padtop-mobile:'.$testimonial_padtop_mobile.'px;--testimonial-padbot:'.$testimonial_padbot.'px;--testimonial-padbot-mobile:'.$testimonial_padbot_mobile.'px;--partnership-padtop:'.$partnership_padtop.'px;--partnership-padtop-mobile:'.$partnership_padtop_mobile.'px;--partnership-padbot:'.$partnership_padbot.'px;--partnership-padbot-mobile:'.$partnership_padbot_mobile.'px;}';
		wp_add_inline_style( 'edunia-style', $custom_css );

		wp_enqueue_script( 'smartmenus-script', get_template_directory_uri() . '/assets/js/jquery.smartmenus.min.js', array('jquery'), $this->theme['version'], true );
		wp_enqueue_script( 'slick-script', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), $this->theme['version'], true );
		wp_enqueue_script( 'kbmodal-script', get_template_directory_uri() . '/assets/js/kbmodal.min.js', array('jquery'), $this->theme['version'], true );
		wp_enqueue_script( 'main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), $this->theme['version'], true );
		if(is_page_template('homepage.php')){
			wp_enqueue_script( 'home-script', get_template_directory_uri() . '/assets/js/home.js', array('jquery'), $this->theme['version'], true );
		}
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_style( 'wp-block-library' );
	}

	/**
	 * Backend Scripts
	 */
	public function admin_enqueue_scripts( $hook ) {
		$current_screen = get_current_screen()->id;
		if ( in_array( $hook, array( 'post.php' ) ) ) {
			wp_enqueue_style( $this->theme['slug'] . '-wizard-style', get_template_directory_uri() . '/assets/css/metabox.css' );
		}

		if ( in_array( $hook, array( 'widgets.php' ) ) ) {
			wp_enqueue_script( 'mupwsa-js', get_template_directory_uri() . '/assets/js/mupwsa.js', array('jquery'), $this->theme['version'], true );
		}

		if ( $hook == 'admin_page_' . $this->theme['slug'] . '-installation-wizard' ) {
			wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap' );
			wp_enqueue_style( $this->theme['slug'] . '-wizard-style', get_template_directory_uri() . '/inc/src/assets/css/setup-wizard.css' );
			wp_enqueue_script( $this->theme['slug'] . '-wizard-script', get_template_directory_uri() . '/inc/src/assets/js/setup-wizard.js', array( 'jquery' ), $this->theme['version'], true );
			wp_localize_script( $this->theme['slug'] . '-wizard-script', 'about_theme', array(
				'slug' => $this->theme['slug'],
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'str_next' => __( 'Next', 'garudatheme' ),
			) );
		}
	}

	public function admin_head() {
		echo '<style>#adminmenu .wp-not-current-submenu.wp-menu-separator{display: none !important;}</style>';
	}

	/**
	 * Required Plugins
	 */
	public function required_plugins() {
		return array(
			array(
				'name'      => 'One Click Demo Import',
				'slug'      => 'one-click-demo-import',
				'required'  => false,
			),
		);	
	}

	public function tgmpa_data() {
		$plugins = $this->required_plugins();

		$config = array(
			'id'           => 'tgmpa',
			'default_path' => '',
			'menu'         => $this->theme['slug'] . '-required-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => false,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => false,
			'message'      => '',
			'strings'      => array(
				'nag_type' => 'updated',
			)
			
		);

		tgmpa( $plugins, $config );
	}

	/**
	 * Demo Content
	 */
	public function ocdi_import_files() {
		return [
			[
				'import_file_name' => __( 'Default', 'edunia' ),
				'categories' => [ 'Education' ],
				'local_import_file' => $this->path . '/assets/ocdi/edunia.WordPress.2025-03-03.xml',
				'local_import_widget_file' => $this->path . '/assets/ocdi/edunia.garudatheme.com-widgets.wie',
				'local_import_customizer_file' => $this->path . '/assets/ocdi/edunia-export.dat',
				'import_preview_image_url' => $this->uri . '/assets/ocdi/complete.png',
			]
		];
	}

	/**
	 * After Demo Imported
	 */
	public function ocdi_after_import() {
		$primary_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
		$topbar_menu = get_term_by( 'name', 'Top Bar', 'nav_menu' );
		set_theme_mod( 'nav_menu_locations', [
				'primary-menu' => $primary_menu->term_id,
				'top-menu' => $topbar_menu->term_id,
			]
		);
		$front_page_id = get_page_by_title( 'Beranda Edunia' );
		$blog_page_id  = get_page_by_title( 'Berita' );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $front_page_id->ID );
		update_option( 'page_for_posts', $blog_page_id->ID );
		update_option( 'posts_per_page', 8 );
		update_option( 'posts_per_rss', 8 );
	}

	/**
	 * Admin Menu
	 */
	public function register_admin_menu() {
		add_submenu_page(
			'options-writing.php',
			__( 'Installation Wizard', 'garudatheme' ),
			__( 'Installation Wizard', 'garudatheme' ),
			'manage_options',
			$this->theme['slug'] . '-installation-wizard',
			[$this, 'installation_wizard_page'],
		);
	}

	public function after_setup_theme() {
		load_theme_textdomain( $this->theme['slug'], get_template_directory() . '/languages' );

		register_nav_menus(
			array(
				'top-menu' => esc_html__( 'Top Menu', 'edunia' ),
				'primary-menu' => esc_html__( 'Primary Menu', 'edunia' ),
			)
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 48,
				'width'       => 185,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		add_image_size( 'news-thumb', 350, 230, true );
		add_image_size( 'gtk-thumb', 250, 250, true );
		add_image_size( 'testimonial-thumb', 150, 150, true );

		// Default
		load_theme_textdomain( 'garudatheme', $this->path . '/languages/garudatheme' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
	}

	public function redirect_to_installation_wizard() {
		if ( is_admin() && isset( $_GET['activated'] ) && $_GET['activated'] === 'true' ) {
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->theme['slug'] . '-installation-wizard' ) );
			exit;
		}
	}

	public function widgets_init() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'edunia' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here.', 'edunia' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	
		register_sidebar( array(
			'name' => __( 'Footer 1', 'edunia' ),
			'id' => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'edunia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer 2', 'edunia' ),
			'id' => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'edunia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer 3', 'edunia' ),
			'id' => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'edunia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		) );
		
		register_sidebar( array(
			'name' => __( 'Footer 4', 'edunia' ),
			'id' => 'footer-4',
			'description'   => esc_html__( 'Add widgets here.', 'edunia' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		) );
	}

	public function license_ajax_handler() {
		if ( isset( $_POST['license_verification_nonce'] ) && wp_verify_nonce( $_POST['license_verification_nonce'], 'license_verification_action' ) ) {

			$current_domain = parse_url( get_site_url(), PHP_URL_HOST );
			$license_key = preg_replace( '/[^A-Za-z0-9\-]/', '', sanitize_text_field( $_POST['license_key'] ) );

			$response = wp_remote_get( 'https://garudatheme.com/wp-json/wp-theme/v1/license-verification?key=' . $license_key . '&theme=' . $this->theme['slug'] . '&domain=' . $current_domain . '&lang=' . get_locale(), array(
				'timeout' => 20,
				'headers' => array(
					'Accept' => 'application/json',
				),
			) );

			$response_body = wp_remote_retrieve_body( $response );
			$response_decode = json_decode( $response_body, true );

			if ( isset( $response_decode['status'] ) && $response_decode['status'] == true ) {
				$update_license = array(
					'theme' => $this->theme['slug'],
					'key' => $license_key,
					'expired' => $response_decode['expired']
				);

				update_option( md5( $this->theme['slug'] . '_license' ), $update_license );
			}
		} else {
			$response_body = array(
				'status' => false,
				'message' => __( 'Sorry, the request could not be processed.', 'garudatheme' )
			);
		}

		echo $response_body;
		
		wp_die();
	}

	public function update_check( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$stylesheet = get_template();
		$license = $this->license();

		$request = wp_remote_get( 'https://garudatheme.com/wp-json/wp-theme/v1/update-check?license-key=' . $license['key'] . '&theme=' . $this->theme['slug'], array(
			'timeout' => 20,
			'headers' => array(
				'Accept' => 'application/json',
			),
		) );

		if ( is_wp_error( $request ) ) {
			return $transient;
		}

		$response_code = wp_remote_retrieve_response_code( $request );
		$response_body = wp_remote_retrieve_body( $request );

		if ( $response_code === 200 ) {
			$data = json_decode( $response_body, true );
			if ( isset( $data['version'] ) && version_compare( $data['version'], $transient->checked[$stylesheet] ) === 1 ) {
				$transient->response[$stylesheet] = array(
					'theme' => $stylesheet,
					'new_version' => $data['version'],
					'url' => $data['details_url'],
					'package' => $data['download_url'],
				);
			}
		}
		return $transient;
	}

	public function license() {
		$default = array(
			'theme' => '',
			'key' => '',
			'expired' => ''
		);
		return get_option( md5( $this->theme['slug'] . '_license' ), $default );
	}

	public function installation_wizard_page() {
		require_once( $this->path . '/templates/setup-wizard.php' );
	}

	public function template_redirect() {
		$license = $this->license();
		$current_domain = parse_url( get_site_url(), PHP_URL_HOST );
		if ( $current_domain !== 'localhost' ) {
			if ( empty( $license['key'] ) ) {
				$message = sprintf( __( 'This site uses a theme that has not been activated, if you are an admin, please activate it %shere%s.', 'garudatheme' ), '<a href="' . admin_url( 'admin.php?page=' . $this->theme['slug'] . '-installation-wizard' ) . '">', '</a>' );
				wp_die( $message );
			}
		}
	}
}

$garudatheme_init = new Garudatheme_Init();