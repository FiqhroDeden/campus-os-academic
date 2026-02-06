<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function edunia_customize_register( $wp_customize ) {
	$wp_customize->add_panel('theme_options',array(
        'title' => __('Theme Options', 'edunia'),
        'priority' => 10,
    ));

    $wp_customize->add_section('appearance_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Appearance', 'edunia'),
    	'priority' => 10,
	));

	$wp_customize->add_section('topbar_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Top Bar', 'edunia'),
    	'priority' => 11,
	));

	$wp_customize->add_section('home_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Home Section', 'edunia'),
    	'priority' => 12,
	));

	$wp_customize->add_section('slider_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Featured Slider', 'edunia'),
    	'priority' => 13,
	));

	$wp_customize->add_section('featbox_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Featured Box', 'edunia'),
    	'priority' => 14,
	));

	$wp_customize->add_section('latnews_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Latest News', 'edunia'),
    	'priority' => 15,
	));

	$wp_customize->add_section('angen_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Announcement & Agenda', 'edunia'),
    	'priority' => 16,
	));

	$wp_customize->add_section('achievement_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Achievement', 'edunia'),
    	'priority' => 17,
	));

	$wp_customize->add_section('gallery_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Gallery', 'edunia'),
    	'priority' => 18,
	));

	$wp_customize->add_section('video_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Video', 'edunia'),
    	'priority' => 19,
	));

	$wp_customize->add_section('facility_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Facility', 'edunia'),
    	'priority' => 20,
	));

	$wp_customize->add_section('gtk_section' , array(
        'panel' => 'theme_options',
    	'title' => __('GTK', 'edunia'),
    	'priority' => 21,
	));

	$wp_customize->add_section('testimonial_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Testimonial', 'edunia'),
    	'priority' => 22,
	));

	$wp_customize->add_section('partnership_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Partnership', 'edunia'),
    	'priority' => 23,
	));

	$wp_customize->add_section('footer_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Footer', 'edunia'),
    	'priority' => 24,
	));

	$wp_customize->add_section('archive_section' , array(
        'panel' => 'theme_options',
    	'title' => __('Archive Settings', 'edunia'),
    	'priority' => 25,
	));

	$wp_customize->add_section('more_section' , array(
        'panel' => 'theme_options',
    	'title' => __('More', 'edunia'),
    	'priority' => 26,
	));

	$wp_customize->add_setting( 'logo_height', array(
		'default' => '48',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'logo_height', array(
		'label' => __( 'Logo Size', 'edunia' ) . ' (Normal)',
		'section' => 'title_tagline',
		'priority' => 8,
		'input_attrs' => array(
			'min' => 10,
			'max' => 60,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'logo_height_fixed', array(
		'default' => '36',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'logo_height_fixed', array(
		'label' => __( 'Logo Size', 'edunia' ) . ' (Fixed)',
		'section' => 'title_tagline',
		'priority' => 8,
		'input_attrs' => array(
			'min' => 10,
			'max' => 60,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'logo_height_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'logo_height_divider',
		array(
			'section' => 'title_tagline',
			'priority' => 8,
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting('primary_color', array(
		'default' => '#269db8',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	  
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
		  $wp_customize,
		  'primary_color',
		  array(
			'label' => __( 'Primary Color', 'edunia'),
			'description' => __( 'Select Color', 'edunia'),
			'section' => 'appearance_section',
		  )
		)
	);

	$wp_customize->add_setting('primary_color_hover', array(
		'default' => '#1c859d',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	  
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
		  $wp_customize,
		  'primary_color_hover',
		  array(
			'description' => __( 'On hover', 'edunia'),
			'section' => 'appearance_section',
		  )
		)
	);

	$wp_customize->add_setting( 'color_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'color_divider',
		array(
			'section' => 'appearance_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting('background_topbar', array(
		'default' => '#121314',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	  
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
		  $wp_customize,
		  'background_topbar',
		  array(
			'label' => __( 'Topbar', 'edunia'),
			'description' => __( 'Background Color', 'edunia' ),
			'section' => 'appearance_section',
		  )
		)
	);

	$wp_customize->add_setting('background_topbar_language', array(
		'default' => '#269db8',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	  
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
		  $wp_customize,
		  'background_topbar_language',
		  array(
			'description' => __( 'Language Toggle', 'edunia' ),
			'section' => 'appearance_section',
		  )
		)
	);

	$wp_customize->add_setting( 'background_topbar_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'background_topbar_divider',
		array(
			'section' => 'appearance_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting('background_footer', array(
		'default' => '#121314',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_hex_color',
	));
	  
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
		  $wp_customize,
		  'background_footer',
		  array(
			'label' => __( 'Footer', 'edunia'),
			'description' => __( 'Background Color', 'edunia' ),
			'section' => 'appearance_section',
		  )
		)
	);

	$wp_customize->add_setting( 'background_footer_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'background_footer_divider',
		array(
			'section' => 'appearance_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'font_family_new',
		array(
			'default' => 'heebo,sans-serif',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Garudatheme_FontFamily_Customize_Control( $wp_customize, 'font_family_new',
		array(
			'label' => __( 'Font Family', 'edunia' ),
			'description' => __( 'Select Font Family alphabetically', 'edunia' ),
			'section' => 'appearance_section',
		)
	) ); 

	$wp_customize->add_setting( 'topbar_isactive',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization'
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'topbar_isactive',
		array(
			'label' => __( 'Enable', 'edunia' ),
			'section' => 'topbar_section'
		)
	) );

	$wp_customize->add_setting( 'topbar_toggle_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'topbar_toggle_divider',
		array(
			'section' => 'topbar_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'topbar_contacttitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'topbar_contacttitle', array(
		'label' => __( 'Contact', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'topbar_section',
		'settings' => 'topbar_contacttitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'topbar_contactphone' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'topbar_contactphone', array(
		'description' => __('Phone', 'edunia'),
		'section' => 'topbar_section',
		'settings' => 'topbar_contactphone',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'topbar_contactemail' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'topbar_contactemail', array(
		'description' => __('Email', 'edunia'),
		'section' => 'topbar_section',
		'settings' => 'topbar_contactemail',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'topbar_contact_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'topbar_contact_divider',
		array(
			'section' => 'topbar_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'topbar_idlang' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'topbar_idlang', array(
		'label' => __('Language', 'edunia'),
		'description' => __('Indonesia (Url)', 'edunia'),
		'section' => 'topbar_section',
		'settings' => 'topbar_idlang',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'topbar_enlang' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'topbar_enlang', array(
		'description' => __('English (Url)', 'edunia'),
		'section' => 'topbar_section',
		'settings' => 'topbar_enlang',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'sort_section',
		array(
			'default' => 'slider,latnews,angen,achievement,gallery,video,facility,gtk,testimonial,partnership',
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_text_sanitization'
		)
	);
	$wp_customize->add_control( new Skyrocket_Pill_Checkbox_Custom_Control( $wp_customize, 'sort_section',
		array(
			'label' => __( 'Sort Section', 'edunia' ),
			'description' => esc_html__( 'Uncheck the section you want to hide', 'edunia' ),
			'section' => 'home_section',
			'input_attrs' => array(
				'sortable' => true,
				'fullwidth' => true,
			),
			'choices' => array(
				'slider' => __( 'Featured Slider', 'edunia' ),
				'latnews' => __( 'Latest News', 'edunia' ),
				'angen' => __( 'Announcement & Agenda', 'edunia' ),
				'achievement' => __( 'Achievement', 'edunia' ),
				'gallery' => __( 'Gallery', 'edunia' ),
				'video' => __( 'Video', 'edunia' ),
				'facility' => __( 'Facility', 'edunia' ),
				'gtk' => __( 'GTK', 'edunia' ),
				'testimonial' => __( 'Testimonial', 'edunia' ),
				'partnership' => __( 'Partnership', 'edunia' ),
			)
		)
	) );

	$wp_customize->add_setting( 'slider1_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'slider1_image', array(
		'label' => __('Slider 1', 'edunia'),
		'description' => __('Image', 'edunia'),
        'section' => 'slider_section',
        'settings' => 'slider1_image',
	)));

	$wp_customize->add_setting( 'slider1_video', array(
        'default'           => '',
        'sanitize_callback' => '',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'slider1_video', array(
        'description' => __( 'Video', 'edunia' ),
        'section'     => 'slider_section',
        'settings'    => 'slider1_video',
        'mime_type'   => 'video',
    ) ) );
	
	$wp_customize->add_setting( 'slider1_subtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'slider1_subtitle', array(
		'description' => __('Sub Title', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider1_subtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider1_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'slider1_title', array(
		'description' => __('Title', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider1_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider1_btnname' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'slider1_btnname', array(
		'description' => __('Button Name', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider1_btnname',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider1_link' , array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
  
	$wp_customize->add_control( 'slider1_link', array(
		'description' => __('Link', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider1_link',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider1_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'slider1_divider',
		array(
			'section' => 'slider_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'slider2_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'slider2_image', array(
		'label' => __('Slider 2', 'edunia'),
		'description' => __('Image', 'edunia'),
        'section' => 'slider_section',
        'settings' => 'slider2_image',
	)));

	$wp_customize->add_setting( 'slider2_video', array(
        'default'           => '',
        'sanitize_callback' => '',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'slider2_video', array(
        'description' => __( 'Video', 'edunia' ),
        'section'     => 'slider_section',
        'settings'    => 'slider2_video',
        'mime_type'   => 'video',
    ) ) );
	
	$wp_customize->add_setting( 'slider2_subtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'slider2_subtitle', array(
		'description' => __('Sub Title', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider2_subtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider2_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
  
	$wp_customize->add_control( 'slider2_title', array(
		'description' => __('Title', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider2_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider2_btnname' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
  
	$wp_customize->add_control( 'slider2_btnname', array(
		'description' => __('Button Name', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider2_btnname',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider2_link' , array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
  
	$wp_customize->add_control( 'slider2_link', array(
		'description' => __('Link', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider2_link',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider2_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'slider2_divider',
		array(
			'section' => 'slider_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'slider3_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'slider3_image', array(
		'label' => __('Slider 3', 'edunia'),
		'description' => __('Image', 'edunia'),
        'section' => 'slider_section',
        'settings' => 'slider3_image',
	)));

	$wp_customize->add_setting( 'slider3_video', array(
        'default'           => '',
        'sanitize_callback' => '',
    ) );

    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'slider3_video', array(
        'description' => __( 'Video', 'edunia' ),
        'section'     => 'slider_section',
        'settings'    => 'slider3_video',
        'mime_type'   => 'video',
    ) ) );
	
	$wp_customize->add_setting( 'slider3_subtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
  
	$wp_customize->add_control( 'slider3_subtitle', array(
		'description' => __('Sub Title', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider3_subtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider3_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
  
	$wp_customize->add_control( 'slider3_title', array(
		'description' => __('Title', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider3_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider3_btnname' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'slider3_btnname', array(
		'description' => __('Button Name', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider3_btnname',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'slider3_link' , array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
  
	$wp_customize->add_control( 'slider3_link', array(
		'description' => __('Link', 'edunia'),
		'section' => 'slider_section',
		'settings' => 'slider3_link',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'featbox_isactive',
		array(
			'default' => 1,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization'
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'featbox_isactive',
		array(
			'label' => __( 'Enable', 'edunia' ),
			'section' => 'featbox_section'
		)
	) );

	$wp_customize->add_setting( 'featbox_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'featbox_divider',
		array(
			'section' => 'featbox_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'fbox1_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fbox1_image', array(
		'label' => __('Box 1', 'edunia'),
		'description' => __('Image/ Icon', 'edunia'),
        'section' => 'featbox_section',
        'settings' => 'fbox1_image',
	)));

	$wp_customize->add_setting( 'fbox1_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'fbox1_title', array(
		'description' => __('Title', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox1_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox1_btnname' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'fbox1_btnname', array(
		'description' => __('Button Name', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox1_btnname',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox1_link' , array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
  
	$wp_customize->add_control( 'fbox1_link', array(
		'description' => __('Link', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox1_link',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox1_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'fbox1_divider',
		array(
			'section' => 'featbox_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'fbox2_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fbox2_image', array(
		'label' => __('Box 2', 'edunia'),
		'description' => __('Image/ Icon', 'edunia'),
        'section' => 'featbox_section',
        'settings' => 'fbox2_image',
	)));

	$wp_customize->add_setting( 'fbox2_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'fbox2_title', array(
		'description' => __('Title', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox2_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox2_btnname' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'fbox2_btnname', array(
		'description' => __('Button Name', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox2_btnname',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox2_link' , array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	));
  
	$wp_customize->add_control( 'fbox2_link', array(
		'description' => __('Link', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox2_link',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox2_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'fbox2_divider',
		array(
			'section' => 'featbox_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'fbox3_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'fbox3_image', array(
		'label' => __('Box 3', 'edunia'),
		'description' => __('Image/ Icon', 'edunia'),
        'section' => 'featbox_section',
        'settings' => 'fbox3_image',
	)));

	$wp_customize->add_setting( 'fbox3_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'fbox3_title', array(
		'description' => __('Title', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox3_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox3_btnname' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'fbox3_btnname', array(
		'description' => __('Button Name', 'edunia'),
		'section' => 'featbox_section',
		'settings' => 'fbox3_btnname',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'fbox3_link' , array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw'
	) );
  
	$wp_customize->add_control( 'fbox3_link', array(
		'description' => __( 'Link', 'edunia' ),
		'section' => 'featbox_section',
		'settings' => 'fbox3_link',
		'type' => 'text',
	) );

	$wp_customize->add_setting( 'latnews_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'latnews_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'latnews_section',
		'settings' => 'latnews_background',
	)));

	$wp_customize->add_setting( 'latnews_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'latnews_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'latnews_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'latnews_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'latnews_bgcolor_divider',
		array(
			'section' => 'latnews_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'latnews_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'latnews_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'latnews_section',
        'settings' => 'latnews_background_image',
	)));

	$wp_customize->add_setting( 'latnews_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'latnews_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'latnews_section',
		)
	));

	$wp_customize->add_setting( 'latnews_bgimage_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'latnews_bgimage_divider',
		array(
			'section' => 'latnews_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'latnews_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'latnews_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'latnews_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'latnews_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'latnews_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'latnews_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'latnews_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'latnews_padtop_divider',
		array(
			'section' => 'latnews_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'latnews_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'latnews_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'latnews_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'latnews_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'latnews_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'latnews_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'latnews_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'latnews_padbot_divider',
		array(
			'section' => 'latnews_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'latnews_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'latnews_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'latnews_section',
		'settings' => 'latnews_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'latnews_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'latnews_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'latnews_section',
		'settings' => 'latnews_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'latnews_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'latnews_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'latnews_section',
		'settings' => 'latnews_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'angen_background', array(
		'default' => '#d4ebf1',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'angen_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'angen_section',
		'settings' => 'angen_background',
	)));

	$wp_customize->add_setting( 'angen_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'angen_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'angen_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'angen_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'angen_bgcolor_divider',
		array(
			'section' => 'angen_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'angen_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'angen_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'angen_section',
        'settings' => 'angen_background_image',
	)));

	$wp_customize->add_setting( 'angen_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'angen_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'angen_section',
		)
	));

	$wp_customize->add_setting( 'angen_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'angen_background_divider',
		array(
			'section' => 'angen_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'angen_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'angen_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'angen_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'angen_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'angen_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'angen_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'angen_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'angen_padtop_divider',
		array(
			'section' => 'angen_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'angen_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'angen_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'angen_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'angen_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'angen_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'angen_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'angen_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'angen_padbot_divider',
		array(
			'section' => 'angen_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'announcement_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'announcement_title', array(
		'label' => __('Announcement', 'edunia'),
		'description' => __('Title', 'edunia'),
		'section' => 'angen_section',
		'settings' => 'announcement_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'announcement_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field',
	));
  
	$wp_customize->add_control( 'announcement_description', array(
		'description' => __('Description', 'edunia'),
		'section' => 'angen_section',
		'settings' => 'announcement_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'announcement_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'announcement_divider',
		array(
			'section' => 'angen_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'agenda_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'agenda_title', array(
		'label' => __('Agenda', 'edunia'),
		'description' => __('Title', 'edunia'),
		'section' => 'angen_section',
		'settings' => 'agenda_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'agenda_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'agenda_description', array(
		'description' => __('Description', 'edunia'),
		'section' => 'angen_section',
		'settings' => 'agenda_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'achievement_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'achievement_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'achievement_section',
		'settings' => 'achievement_background',
	)));

	$wp_customize->add_setting( 'achievement_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'achievement_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'achievement_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'achievement_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'achievement_bgcolor_divider',
		array(
			'section' => 'achievement_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'achievement_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'achievement_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'achievement_section',
        'settings' => 'achievement_background_image',
	)));

	$wp_customize->add_setting( 'achievement_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'achievement_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'achievement_section',
		)
	));

	$wp_customize->add_setting( 'achievement_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'achievement_background_divider',
		array(
			'section' => 'achievement_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'achievement_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'achievement_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'achievement_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'achievement_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'achievement_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'achievement_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'achievement_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'achievement_padtop_divider',
		array(
			'section' => 'achievement_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'achievement_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'achievement_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'achievement_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'achievement_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'achievement_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'achievement_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'achievement_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'achievement_padbot_divider',
		array(
			'section' => 'achievement_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'achievement_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'achievement_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'achievement_section',
		'settings' => 'achievement_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'achievement_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'achievement_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'achievement_section',
		'settings' => 'achievement_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'achievement_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'achievement_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'achievement_section',
		'settings' => 'achievement_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'gallery_background', array(
		'default' => '#d4ebf1',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'gallery_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'gallery_section',
		'settings' => 'gallery_background',
	)));

	$wp_customize->add_setting( 'gallery_background_style', array(
		'default' => 'gradient_two',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'gallery_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'gallery_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'gallery_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gallery_bgcolor_divider',
		array(
			'section' => 'gallery_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gallery_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'gallery_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'gallery_section',
        'settings' => 'gallery_background_image',
	)));

	$wp_customize->add_setting( 'gallery_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'gallery_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'gallery_section',
		)
	));

	$wp_customize->add_setting( 'gallery_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gallery_background_divider',
		array(
			'section' => 'gallery_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gallery_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gallery_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'gallery_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gallery_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gallery_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'gallery_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gallery_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gallery_padtop_divider',
		array(
			'section' => 'gallery_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gallery_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gallery_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'gallery_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gallery_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gallery_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'gallery_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gallery_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gallery_padbot_divider',
		array(
			'section' => 'gallery_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gallery_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'gallery_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'gallery_section',
		'settings' => 'gallery_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'gallery_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'gallery_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'gallery_section',
		'settings' => 'gallery_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'gallery_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'gallery_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'gallery_section',
		'settings' => 'gallery_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'video_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'video_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'video_section',
		'settings' => 'video_background',
	)));

	$wp_customize->add_setting( 'video_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'video_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'video_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'video_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'video_bgcolor_divider',
		array(
			'section' => 'video_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'video_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'video_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'video_section',
        'settings' => 'video_background_image',
	)));

	$wp_customize->add_setting( 'video_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'video_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'video_section',
		)
	));

	$wp_customize->add_setting( 'video_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'video_background_divider',
		array(
			'section' => 'video_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'video_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'video_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'video_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'video_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'video_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'video_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'video_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'video_padtop_divider',
		array(
			'section' => 'video_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'video_padbot', array(
		'default' => '30',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'video_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'video_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'video_padbot_mobile', array(
		'default' => '20',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'video_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'video_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'video_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'video_padbot_divider',
		array(
			'section' => 'video_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'video_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'video_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'video_section',
		'settings' => 'video_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'video_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'video_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'video_section',
		'settings' => 'video_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'video_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'video_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'video_section',
		'settings' => 'video_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'facility_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'facility_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'facility_section',
		'settings' => 'facility_background',
	)));

	$wp_customize->add_setting( 'facility_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'facility_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'facility_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'facility_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'facility_bgcolor_divider',
		array(
			'section' => 'facility_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'facility_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'facility_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'facility_section',
        'settings' => 'facility_background_image',
	)));

	$wp_customize->add_setting( 'facility_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'facility_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'facility_section',
		)
	));

	$wp_customize->add_setting( 'facility_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'facility_background_divider',
		array(
			'section' => 'facility_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'facility_padtop', array(
		'default' => '30',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'facility_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'facility_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'facility_padtop_mobile', array(
		'default' => '20',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'facility_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'facility_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'facility_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'facility_padtop_divider',
		array(
			'section' => 'facility_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'facility_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'facility_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'facility_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'facility_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'facility_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'facility_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'facility_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'facility_padbot_divider',
		array(
			'section' => 'facility_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'facility_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'facility_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'facility_section',
		'settings' => 'facility_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'facility_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'facility_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'facility_section',
		'settings' => 'facility_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'facility_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'facility_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'facility_section',
		'settings' => 'facility_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'gtk_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'gtk_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'gtk_section',
		'settings' => 'gtk_background',
	)));

	$wp_customize->add_setting( 'gtk_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'gtk_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'gtk_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'gtk_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gtk_bgcolor_divider',
		array(
			'section' => 'gtk_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gtk_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'gtk_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'gtk_section',
        'settings' => 'gtk_background_image',
	)));

	$wp_customize->add_setting( 'gtk_background_fixed',
		array(
			'default' => 1,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'gtk_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'gtk_section',
		)
	));

	$wp_customize->add_setting( 'gtk_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gtk_background_divider',
		array(
			'section' => 'gtk_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gtk_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gtk_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'gtk_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gtk_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gtk_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'gtk_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gtk_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gtk_padtop_divider',
		array(
			'section' => 'gtk_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gtk_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gtk_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'gtk_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gtk_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'gtk_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'gtk_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'gtk_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gtk_padbot_divider',
		array(
			'section' => 'gtk_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gtk_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'gtk_title', array(
		'label' => __('Title', 'edunia'),
		'section' => 'gtk_section',
		'settings' => 'gtk_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'gtk_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'gtk_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'gtk_section',
		'settings' => 'gtk_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'gtk_description_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gtk_description_divider',
		array(
			'section' => 'gtk_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gtk_limit_home',
		array(
			'default' => '12',
			'transport' => 'refresh',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gtk_limit_home',
		array(
			'label' => __('Number Displayed', 'edunia'),
			'section' => 'gtk_section',
			'settings' => 'gtk_limit_home',
			'type' => 'select',
			'choices' => array(
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'testimonial_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'testimonial_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'testimonial_section',
		'settings' => 'testimonial_background',
	)));

	$wp_customize->add_setting( 'testimonial_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'testimonial_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'testimonial_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'testimonial_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'testimonial_bgcolor_divider',
		array(
			'section' => 'testimonial_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'testimonial_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'testimonial_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'testimonial_section',
        'settings' => 'testimonial_background_image',
	)));

	$wp_customize->add_setting( 'testimonial_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'testimonial_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'testimonial_section',
		)
	));

	$wp_customize->add_setting( 'testimonial_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'testimonial_background_divider',
		array(
			'section' => 'testimonial_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'testimonial_padtop', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'testimonial_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'testimonial_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'testimonial_padtop_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'testimonial_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'testimonial_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'testimonial_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'testimonial_padtop_divider',
		array(
			'section' => 'testimonial_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'testimonial_padbot', array(
		'default' => '30',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'testimonial_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'testimonial_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'testimonial_padbot_mobile', array(
		'default' => '20',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'testimonial_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'testimonial_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'testimonial_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'testimonial_padbot_divider',
		array(
			'section' => 'testimonial_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'testimonial_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'testimonial_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'testimonial_section',
		'settings' => 'testimonial_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'testimonial_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'testimonial_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'testimonial_section',
		'settings' => 'testimonial_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'testimonial_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'testimonial_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'testimonial_section',
		'settings' => 'testimonial_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'partnership_background', array(
		'default' => '#ffffff',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));

	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'partnership_background', array(
		'label' => __('Background Color', 'edunia'),
		'description' => __('Select Color', 'edunia'),
		'section' => 'partnership_section',
		'settings' => 'partnership_background',
	)));

	$wp_customize->add_setting( 'partnership_background_style', array(
		'default' => 'solid',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_text_sanitization',
	));

	$wp_customize->add_control(new Skyrocket_Text_Radio_Button_Custom_Control($wp_customize, 'partnership_background_style', array(
		'description' => __('Select Style', 'edunia'),
		'section' => 'partnership_section',
		'choices' => array(
			'solid' => 'Solid',
			'gradient_one' => 'Gradient 1',
			'gradient_two' => 'Gradient 2',
		),
	)));

	$wp_customize->add_setting( 'partnership_bgcolor_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'partnership_bgcolor_divider',
		array(
			'section' => 'partnership_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'partnership_background_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw'
    ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'partnership_background_image', array(
		'label' => __('Background Image', 'edunia'),
		'description' => __('Select Image', 'edunia'),
        'section' => 'partnership_section',
        'settings' => 'partnership_background_image',
	)));

	$wp_customize->add_setting( 'partnership_background_fixed',
		array(
			'default' => 0,
			'transport' => 'refresh',
			'sanitize_callback' => 'skyrocket_switch_sanitization',
		)
	);

	$wp_customize->add_control( new Skyrocket_Toggle_Switch_Custom_control( $wp_customize, 'partnership_background_fixed',
		array(
			'label' => __( 'Fixed', 'edunia' ),
			'section' => 'partnership_section',
		)
	));

	$wp_customize->add_setting( 'partnership_background_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'partnership_background_divider',
		array(
			'section' => 'partnership_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'partnership_padtop', array(
		'default' => '30',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'partnership_padtop', array(
		'label' => __('Padding Top', 'edunia') . ' - Desktop',
		'section' => 'partnership_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'partnership_padtop_mobile', array(
		'default' => '20',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'partnership_padtop_mobile', array(
		'label' => __('Padding Top', 'edunia') . ' - Mobile',
		'section' => 'partnership_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'partnership_padtop_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'partnership_padtop_divider',
		array(
			'section' => 'partnership_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'partnership_padbot', array(
		'default' => '60',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'partnership_padbot', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Desktop',
		'section' => 'partnership_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'partnership_padbot_mobile', array(
		'default' => '40',
		'transport' => 'refresh',
		'sanitize_callback' => 'skyrocket_sanitize_integer',
	));

	$wp_customize->add_control(new Skyrocket_Slider_Custom_Control($wp_customize, 'partnership_padbot_mobile', array(
		'label' => __('Padding Bottom', 'edunia') . ' - Mobile',
		'section' => 'partnership_section',
		'input_attrs' => array(
			'min' => 10,
			'max' => 80,
			'step' => 1,
		),
	)));

	$wp_customize->add_setting( 'partnership_padbot_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'partnership_padbot_divider',
		array(
			'section' => 'partnership_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'partnership_maintitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'partnership_maintitle', array(
		'label' => __('Title', 'edunia'),
		'description' => __('Main', 'edunia'),
		'section' => 'partnership_section',
		'settings' => 'partnership_maintitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'partnership_secondtitle' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'partnership_secondtitle', array(
		'description' => __('Second', 'edunia'),
		'section' => 'partnership_section',
		'settings' => 'partnership_secondtitle',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'partnership_description' , array(
		'capability' => 'edit_theme_options',
		'default' => '',
		'sanitize_callback' => 'sanitize_textarea_field'
	));
  
	$wp_customize->add_control( 'partnership_description', array(
		'label' => __('Description', 'edunia'),
		'section' => 'partnership_section',
		'settings' => 'partnership_description',
		'type' => 'textarea',
	));

	$wp_customize->add_setting( 'footer_credit',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_kses_post'
		)
	);

	$wp_customize->add_control( new Skyrocket_TinyMCE_Custom_control( $wp_customize, 'footer_credit',
		array(
			'label' => __( 'Site Credit', 'edunia' ),
			'description' => __( 'Credit information in the footer', 'edunia' ),
			'section' => 'footer_section',
			'input_attrs' => array(
				'toolbar1' => 'bold italic link',
				'mediaButtons' => false,
			)
		)
	) );

	$wp_customize->add_setting( 'announcement_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'announcement_archive_title', array(
		'label' => __( 'Announcement', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'announcement_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'announcement_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'announcement_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'announcement_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'announcement_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'announcement_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'agenda_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'agenda_archive_title', array(
		'label' => __( 'Agenda', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'agenda_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'agenda_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'agenda_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'agenda_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'agenda_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'agenda_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'achievement_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'achievement_archive_title', array(
		'label' => __( 'Achievement', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'achievement_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'achievement_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'achievement_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'achievement_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'achievement_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'achievement_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gallery_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'gallery_archive_title', array(
		'label' => __( 'Gallery', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'gallery_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'gallery_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gallery_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'gallery_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'gallery_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gallery_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'video_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'video_archive_title', array(
		'label' => __( 'Video', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'video_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'video_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'video_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'video_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'video_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'video_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'facility_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'facility_archive_title', array(
		'label' => __( 'Facility', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'facility_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'facility_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'facility_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'facility_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'facility_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'facility_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'gtk_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'gtk_archive_title', array(
		'label' => __( 'GTK', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'gtk_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'gtk_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gtk_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'gtk_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'gtk_archive_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'gtk_archive_divider',
		array(
			'section' => 'archive_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'download_archive_title' , array(
		'default' => '',
		'sanitize_callback' => 'wp_filter_nohtml_kses'
	));
  
	$wp_customize->add_control( 'download_archive_title', array(
		'label' => __( 'Download', 'edunia' ),
		'description' => __( 'Title', 'edunia' ),
		'section' => 'archive_section',
		'settings' => 'download_archive_title',
		'type' => 'text',
	));

	$wp_customize->add_setting( 'download_limit_archive',
		array(
			'default' => 'default',
			'transport' => 'postMessage',
			'sanitize_callback' => 'wp_filter_nohtml_kses'
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'download_limit_archive',
		array(
			'description' => __('Number Displayed', 'edunia'),
			'section' => 'archive_section',
			'settings' => 'download_limit_archive',
			'type' => 'select',
			'choices' => array(
				'default' => 'Default',
				'6' => '6',
				'8' => '8',
				'10' => '10',
				'12' => '12',
				'14' => '14',
				'16' => '16',
				'18' => '18',
				'20' => '20',
				'nolimit' => __('No Limit', 'edunia'),
			)
		)
	));

	$wp_customize->add_setting( 'preload_animation', array(
		'capability' => 'edit_theme_options',
		'default' => 'loading',
		'transport' => 'refresh',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
	
	$wp_customize->add_control( 'preload_animation', array(
		'label' => __('Preload', 'edunia'),
		'section' => 'more_section',
		'settings' => 'preload_animation',
		'type' => 'select',
		'choices' => array(
			'loading' => 'Loading (Default)',
			'circle' => 'Circle',
			'dualring' => 'Dual Ring',
			'ellipsis' => 'Ellipsis',
			'facebook' => 'Facebook',
			'ring' => 'Ring',
		),
	));

	$wp_customize->add_setting( 'preload_animation_divider',
		array(
			'default' => '',
			'transport' => 'postMessage',
			'sanitize_callback' => ''
		)
	);

	$wp_customize->add_control( new Skyrocket_Divider_Custom_Control( $wp_customize, 'preload_animation_divider',
		array(
			'section' => 'more_section',
			'input_attrs' => array(
				'width' => 'full',
				'type' => 'solid',
				'margintop' => 5,
				'marginbottom' => 5,
			),
		)
	));

	$wp_customize->add_setting( 'post_editor', array(
		'capability' => 'edit_theme_options',
		'default' => 'gutenberg',
		'sanitize_callback' => 'wp_filter_nohtml_kses',
	));
	
	$wp_customize->add_control( 'post_editor', array(
		'label' => __('Post Editor', 'edunia'),
		'section' => 'more_section',
		'settings' => 'post_editor',
		'type' => 'radio',
		'choices' => array(
			'gutenberg' => __('Gutenberg', 'edunia'),
			'classic' => __('Classic', 'edunia'),
		),
	));

}
add_action( 'customize_register', 'edunia_customize_register' );

require get_template_directory() . '/inc/custom-controls.php';