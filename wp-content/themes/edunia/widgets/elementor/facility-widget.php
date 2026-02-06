<?php

class Edunia_Facility extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_facility';
}

public function get_title() {
    return esc_html__( 'Facility', 'edunia' );
}

public function get_icon() {
    return 'eicon-shape';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'facility', 'fasilitas' ];
}

protected function register_controls() {

    $this->start_controls_section(
        'latestnews_section',
        [
            'label' => esc_html__( 'Facility', 'edunia' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
    'facility_bgcolor',
    [
        'label' => esc_html__( 'Background Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'facility_background',
    [
        'label' => esc_html__( 'Select Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'alpha' => false,
        'default' => '#ffffff',
    ]
    );

    $this->add_control(
    'facility_background_style',
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
    'facility_bgimage',
    [
        'label' => esc_html__( 'Background Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'facility_background_image',
    [
        'label' => esc_html__( 'Select Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'facility_background_fixed',
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
    'facility_padtop',
    [
        'label' => esc_html__( 'Padding Top', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'facility_padtop_desktop',
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
            ':root' => '--facility-padtop: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'facility_padtop_mobile',
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
            ':root' => '--facility-padtop-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'facility_padbot',
    [
        'label' => esc_html__( 'Padding Bottom', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'facility_padbot_desktop',
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
            ':root' => '--facility-padbot: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'facility_padbot_mobile',
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
            ':root' => '--facility-padbot-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'facility_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'facility_maintitle',
    [
        'label' => esc_html__( 'Main', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
    ]
    );

    $this->add_control(
    'facility_secondtitle',
    [
        'label' => esc_html__( 'Second', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'facility_description_heading',
    [
        'label' => esc_html__( 'Description', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'facility_description',
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
    $facility_background = ((!empty($settings['facility_background']))?$settings['facility_background']:'#ffffff');
    $facility_background_style = ((!empty($settings['facility_background_style']))?$settings['facility_background_style']:'solid');
    $facility_background_image = $settings['facility_background_image']['url'];
    $facility_background_fixed = (($settings['facility_background_fixed'] == 'yes')?1:0);
?>
<div class="facility-wrapper section-wrapper" style="<?php get_section_background($facility_background, $facility_background_style, $facility_background_image, $facility_background_fixed);?>">
    <div class="container">
        <?php
        if ( ! empty( $settings['facility_maintitle'] ) || ! empty( $settings['facility_secondtitle'] ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo $settings['facility_maintitle'] . ((!empty($settings['facility_secondtitle']))?' <span>'.$settings['facility_secondtitle'].'</span>':'');?></h2>
            <?php echo ((!empty($settings['facility_description']))?'<div class="description">'.$settings['facility_description'].'</div>':'');?>
            <a class="more-link" href="<?php echo get_post_type_archive_link('fasilitas');?>"><span class="text"><?php echo __('View All', 'edunia');?></span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></span></a>
        </div>
        <?php
        }
        ?>

        <div class="facility-content">
            <?php
            $args = array(
                'posts_per_page' => 4,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'fasilitas',
                'post_status' => 'publish'
            );
            $the_query = new WP_Query( $args ); 
            if ( $the_query->have_posts() ) : 
                while ( $the_query->have_posts() ) : $the_query->the_post();
                get_template_part( 'template-parts/contents/archive', 'fasilitas' );
                endwhile;
            else:
                echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div><!-- .facility-wrapper -->
<?php
}

}