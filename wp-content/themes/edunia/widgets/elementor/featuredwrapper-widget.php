<?php

class Edunia_FeaturedWrapper extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_featuredwrapper';
}

public function get_title() {
    return esc_html__( 'Featured Slider', 'elementor-featuredwrapper-widget' );
}

public function get_icon() {
    return 'eicon-post-slider';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'featured', 'featured wrapper', 'featured slider', 'slider' ];
}

public function get_script_depends() {
    wp_register_script( 'edunia-featuredwrapper', get_template_directory_uri() . '/assets/js/elementor-featuredwrapper.js', [ 'jquery', 'slick-script' ] );
    return [
        'edunia-featuredwrapper',
    ];
}

protected function register_controls() {

    $this->start_controls_section(
        'featuredslider_section',
        [
            'label' => esc_html__( 'Featured Slider', 'edunia' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
    'slider1_heading',
    [
        'label' => esc_html__( 'Slider 1', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'slider1_image',
    [
        'label' => esc_html__( 'Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
            'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
    ]
    );

    $this->add_control(
    'slider1_video',
    [
        'label' => esc_html__( 'Video', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'media_types' => array( 'video' ),
    ]
    );

    $this->add_control(
    'slider1_subtitle',
    [
        'label' => esc_html__( 'Sub Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider1_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider1_btnname',
    [
        'label' => esc_html__( 'Button Name', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider1_link',
    [
        'label' => esc_html__( 'Link', 'edunia' ),
        'type' => \Elementor\Controls_Manager::URL,
        'options' => false,
        'label_block' => true,
    ]
    );

    $this->add_control(
    'slider2_heading',
    [
        'label' => esc_html__( 'Slider 2', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before'
    ]
    );

    $this->add_control(
    'slider2_image',
    [
        'label' => esc_html__( 'Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'slider2_video',
    [
        'label' => esc_html__( 'Video', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'media_types' => array( 'video' ),
    ]
    );

    $this->add_control(
    'slider2_subtitle',
    [
        'label' => esc_html__( 'Sub Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider2_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider2_btnname',
    [
        'label' => esc_html__( 'Button Name', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider2_link',
    [
        'label' => esc_html__( 'Link', 'edunia' ),
        'type' => \Elementor\Controls_Manager::URL,
        'options' => false,
        'label_block' => true,
    ]
    );

    $this->add_control(
    'slider3_heading',
    [
        'label' => esc_html__( 'Slider 3', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before'
    ]
    );

    $this->add_control(
    'slider3_image',
    [
        'label' => esc_html__( 'Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'slider3_video',
    [
        'label' => esc_html__( 'Video', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'media_types' => array( 'video' ),
    ]
    );

    $this->add_control(
    'slider3_subtitle',
    [
        'label' => esc_html__( 'Sub Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider3_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider3_btnname',
    [
        'label' => esc_html__( 'Button Name', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'slider3_link',
    [
        'label' => esc_html__( 'Link', 'edunia' ),
        'type' => \Elementor\Controls_Manager::URL,
        'options' => false,
        'label_block' => true,
    ]
    );

    $this->end_controls_section();

    $this->start_controls_section(
    'featuredbox_section',
    [
        'label' => esc_html__( 'Featured Box', 'edunia' ),
        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
    ]
    );

    $this->add_control(
    'featbox_isactive',
    [
        'label' => esc_html__( 'Enable', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => esc_html__( 'Yes', 'edunia' ),
        'label_off' => esc_html__( 'No', 'edunia' ),
        'return_value' => 'yes',
        'default' => 'yes',
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'fbox1_heading',
    [
        'label' => esc_html__( 'Box 1', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'fbox1_image',
    [
        'label' => esc_html__( 'Image/ Icon', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
            'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
    ]
    );

    $this->add_control(
    'fbox1_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'fbox1_btnname',
    [
        'label' => esc_html__( 'Button Name', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'fbox1_link',
    [
        'label' => esc_html__( 'Link', 'edunia' ),
        'type' => \Elementor\Controls_Manager::URL,
        'options' => false,
        'label_block' => true,
    ]
    );

    $this->add_control(
    'fbox2_heading',
    [
        'label' => esc_html__( 'Box 2', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before'
    ]
    );

    $this->add_control(
    'fbox2_image',
    [
        'label' => esc_html__( 'Image/ Icon', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
            'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
    ]
    );

    $this->add_control(
    'fbox2_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'fbox2_btnname',
    [
        'label' => esc_html__( 'Button Name', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'fbox2_link',
    [
        'label' => esc_html__( 'Link', 'edunia' ),
        'type' => \Elementor\Controls_Manager::URL,
        'options' => false,
        'label_block' => true,
    ]
    );

    $this->add_control(
    'fbox3_heading',
    [
        'label' => esc_html__( 'Box 3', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before'
    ]
    );

    $this->add_control(
    'fbox3_image',
    [
        'label' => esc_html__( 'Image/ Icon', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
            'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
    ]
    );

    $this->add_control(
    'fbox3_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'fbox3_btnname',
    [
        'label' => esc_html__( 'Button Name', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true
    ]
    );

    $this->add_control(
    'fbox3_link',
    [
        'label' => esc_html__( 'Link', 'edunia' ),
        'type' => \Elementor\Controls_Manager::URL,
        'options' => false,
        'label_block' => true,
    ]
    );

    $this->end_controls_section();

}

protected function render() {
    $settings = $this->get_settings_for_display();

    $featbox_isactive = $settings['featbox_isactive'];
?>
<div class="featured-wrapper">
<div class="featured-slider<?php echo ($featbox_isactive !== 'yes')?' featbox-disabled':'';?>"">
    <?php
    for ($x = 1; $x <= 3; $x++){
        $slider_video = $settings['slider' . $x . '_video']['url'];
        if(!empty($settings['slider'.$x.'_image']['url']) || !empty($slider_video)){
            echo '<div>';
            if (!empty($slider_video)) {
                echo '<video class="feathome-video" autoplay muted playsinline loop="" src="' . $slider_video . '"></video>';
            } else {
                echo '<img src="'.$settings['slider'.$x.'_image']['url'].'"/>';
            }
            if(!empty($settings['slider'.$x.'_subtitle']) || !empty($settings['slider'.$x.'_title']) || !empty($settings['slider'.$x.'_btnname'])){
                echo '<div class="fsc-wrapper"><div class="fsc-container"><div class="fsc-content">';
                echo ((!empty($settings['slider'.$x.'_subtitle']))?'<h3>'.$settings['slider'.$x.'_subtitle'].'</h3>':'');
                if(!empty($settings['slider'.$x.'_title'])){
                    echo '<h2><a href="'.((!empty($settings['slider'.$x.'_link']['url']))?$settings['slider'.$x.'_link']['url']:'#').'">'.$settings['slider'.$x.'_title'].'</a></h2>';
                }
                if(!empty($settings['slider'.$x.'_btnname'])){
                    echo '<a class="btn" href="'.((!empty($settings['slider'.$x.'_link']['url']))?$settings['slider'.$x.'_link']['url']:'#').'">'.$settings['slider'.$x.'_btnname'].'</a>';
                }
                echo '</div></div></div>';
            }
            echo '</div>';
        }
    }
    ?>

</div>
<?php
if ( $featbox_isactive == 'yes' ) {
?>
<div class="featured-box">
    <?php
    for ($x = 1; $x <= 3; $x++){
        if(!empty($settings['fbox'.$x.'_image']['url']) || !empty($settings['fbox'.$x.'_title']) || !empty($settings['fbox'.$x.'_btnname'])){
            echo '<div class="box box-'.$x.'">';
            echo ((!empty($settings['fbox'.$x.'_image']['url']))?'<img class="icon" src="'.$settings['fbox'.$x.'_image']['url'].'"/>':'');
            if(!empty($settings['fbox'.$x.'_title'])){
                echo '<h2 class="title"><a href="'.((!empty($settings['fbox'.$x.'_link']['url']))?$settings['fbox'.$x.'_link']['url']:'#').'">'.$settings['fbox'.$x.'_title'].'</a></h2>';
            }
            if(!empty($settings['fbox'.$x.'_btnname'])){
                echo '<a class="button" href="'.((!empty($settings['fbox'.$x.'_link']['url']))?$settings['fbox'.$x.'_link']['url']:'#').'">'.$settings['fbox'.$x.'_btnname'].'</a>';
            }
            echo '</div>';
        }
    }
    ?>
</div><!-- .featured-box -->
<?php
}
?>
</div><!-- .featured-wrapper -->
<?php
}

}