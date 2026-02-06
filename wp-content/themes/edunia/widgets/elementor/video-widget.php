<?php

class Edunia_Video extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_video';
}

public function get_title() {
    return esc_html__( 'Video', 'edunia' );
}

public function get_icon() {
    return 'eicon-video-playlist';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'video', 'youtube' ];
}

public function get_script_depends() {
    wp_register_script( 'edunia-video', get_template_directory_uri() . '/assets/js/elementor-video.js', [ 'jquery', 'slick-script' ] );
    return [
        'edunia-video',
    ];
}

protected function register_controls() {

    $this->start_controls_section(
    'video_section',
    [
        'label' => esc_html__( 'Video', 'edunia' ),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
    ]
    );

    $this->add_control(
    'video_bgcolor',
    [
        'label' => esc_html__( 'Background Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'video_background',
    [
        'label' => esc_html__( 'Select Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'alpha' => false,
        'default' => '#ffffff',
    ]
    );

    $this->add_control(
    'video_background_style',
    [
        'label' => esc_html__( 'Select Style', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
            'solid' => esc_html__( 'Solid', 'edunia' ),
            'gradient_one' => esc_html__( 'Gradient 1', 'edunia' ),
            'gradient_two' => esc_html__( 'Gradient 2', 'edunia' ),
        ],
        'default' => 'solid',
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'video_bgimage',
    [
        'label' => esc_html__( 'Background Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'video_background_image',
    [
        'label' => esc_html__( 'Select Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'video_background_fixed',
    [
        'label' => esc_html__( 'Fixed', 'textdomain' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__( 'Yes', 'edunia' ),
        'label_off' => esc_html__( 'No', 'edunia' ),
        'return_value' => 'yes',
        'separator' => 'after',
    ]
    );

    $this->add_control(
    'video_padtop',
    [
        'label' => esc_html__( 'Padding Top', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'video_padtop_desktop',
    [
        'label' => esc_html__( 'Desktop', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [
                'min' => 10,
                'max' => 80,
                'step' => 1,
            ]
        ],
        'default' => [
            'unit' => 'px',
            'size' => 60,
        ],
        'selectors' => [
            ':root' => '--video-padtop: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'video_padtop_mobile',
    [
        'label' => esc_html__( 'Mobile', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [
                'min' => 10,
                'max' => 80,
                'step' => 1,
            ]
        ],
        'default' => [
            'unit' => 'px',
            'size' => 40,
        ],
        'selectors' => [
            ':root' => '--video-padtop-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'video_padbot',
    [
        'label' => esc_html__( 'Padding Bottom', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'video_padbot_desktop',
    [
        'label' => esc_html__( 'Desktop', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [
                'min' => 10,
                'max' => 80,
                'step' => 1,
            ]
        ],
        'default' => [
            'unit' => 'px',
            'size' => 30,
        ],
        'selectors' => [
            ':root' => '--video-padbot: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'video_padbot_mobile',
    [
        'label' => esc_html__( 'Mobile', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
            'px' => [
                'min' => 10,
                'max' => 80,
                'step' => 1,
            ]
        ],
        'default' => [
            'unit' => 'px',
            'size' => 20,
        ],
        'selectors' => [
            ':root' => '--video-padbot-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'video_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'video_maintitle',
    [
        'label' => esc_html__( 'Main', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
    ]
    );

    $this->add_control(
    'video_secondtitle',
    [
        'label' => esc_html__( 'Second', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'video_description_heading',
    [
        'label' => esc_html__( 'Description', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'video_description',
    [
        'label' => esc_html__( 'Insert', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 4,
        'show_label' => false,
    ]
    );

    $this->end_controls_section();

}

protected function render() {
    $settings = $this->get_settings_for_display();
    $video_background = ((!empty($settings['video_background']))?$settings['video_background']:'#ffffff');
    $video_background_style = ((!empty($settings['video_background_style']))?$settings['video_background_style']:'solid');
    $video_background_image = $settings['video_background_image']['url'];
    $video_background_fixed = (($settings['video_background_fixed'] == 'yes')?1:0);
?>
<div class="video-wrapper section-wrapper" style="<?php get_section_background($video_background, $video_background_style, $video_background_image, $video_background_fixed);?>">
    <div class="container">
        <?php
        if ( ! empty( $settings['video_maintitle'] ) || ! empty( $settings['video_secondtitle'] ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo $settings['video_maintitle'] . ((!empty($settings['video_secondtitle']))?' <span>'.$settings['video_secondtitle'].'</span>':'');?></h2>
            <?php echo ((!empty($settings['video_description']))?'<div class="description">'.$settings['video_description'].'</div>':'');?>
            <a class="more-link" href="<?php echo get_post_type_archive_link('video');?>"><span class="text"><?php echo __('View All', 'edunia');?></span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></span></a>
        </div>
        <?php
        }
        ?>

        <div class="latvideo-slider">
            <?php
            $args = array(
                'posts_per_page' => 4,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'video',
                'post_status' => 'publish'
            );
            $the_query = new WP_Query( $args ); 
            if ( $the_query->have_posts() ) : 
                while ( $the_query->have_posts() ) : $the_query->the_post();
                get_template_part( 'template-parts/contents/archive', 'video' );
                endwhile;
            else:
                echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div><!-- .video-wrapper -->
<?php
}

}