<?php

class Edunia_Testimonial extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_testimonial';
}

public function get_title() {
    return esc_html__( 'Testimonial', 'edunia' );
}

public function get_icon() {
    return 'eicon-blockquote';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'testimonial' ];
}

public function get_script_depends() {
    wp_register_script( 'edunia-testimonial', get_template_directory_uri() . '/assets/js/elementor-testimonial.js', [ 'jquery', 'slick-script' ] );
    return [
        'edunia-testimonial',
    ];
}

protected function register_controls() {

    $this->start_controls_section(
        'testimonial_section',
        [
            'label' => esc_html__( 'Testimonial', 'edunia' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
    'testimonial_bgcolor',
    [
        'label' => esc_html__( 'Background Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'testimonial_background',
    [
        'label' => esc_html__( 'Select Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'alpha' => false,
        'default' => '#ffffff',
    ]
    );

    $this->add_control(
    'testimonial_background_style',
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
    'testimonial_bgimage',
    [
        'label' => esc_html__( 'Background Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'testimonial_background_image',
    [
        'label' => esc_html__( 'Select Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'testimonial_background_fixed',
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
    'testimonial_padtop',
    [
        'label' => esc_html__( 'Padding Top', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'testimonial_padtop_desktop',
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
            ':root' => '--testimonial-padtop: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'testimonial_padtop_mobile',
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
            ':root' => '--testimonial-padtop-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'testimonial_padbot',
    [
        'label' => esc_html__( 'Padding Bottom', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'testimonial_padbot_desktop',
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
            ':root' => '--testimonial-padbot: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'testimonial_padbot_mobile',
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
            ':root' => '--testimonial-padbot-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'testimonial_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'testimonial_maintitle',
    [
        'label' => esc_html__( 'Main', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
    ]
    );

    $this->add_control(
    'testimonial_secondtitle',
    [
        'label' => esc_html__( 'Second', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'testimonial_description_heading',
    [
        'label' => esc_html__( 'Description', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'testimonial_description',
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
    $testimonial_background = ((!empty($settings['testimonial_background']))?$settings['testimonial_background']:'#ffffff');
    $testimonial_background_style = ((!empty($settings['testimonial_background_style']))?$settings['testimonial_background_style']:'solid');
    $testimonial_background_image = $settings['testimonial_background_image']['url'];
    $testimonial_background_fixed = (($settings['testimonial_background_fixed'] == 'yes')?1:0);
?>
<div class="testimonial-wrapper section-wrapper" style="<?php get_section_background($testimonial_background, $testimonial_background_style, $testimonial_background_image, $testimonial_background_fixed);?>">
    <div class="container">
        <?php
        if ( ! empty( $settings['testimonial_maintitle'] ) || ! empty( $settings['testimonial_secondtitle'] ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo $settings['testimonial_maintitle'] . ((!empty($settings['testimonial_secondtitle']))?' <span>'.$settings['testimonial_secondtitle'].'</span>':'');?></h2>
            <?php echo ((!empty($settings['testimonial_description']))?'<div class="description">'.$settings['testimonial_description'].'</div>':'');?>
        </div>
        <?php
        }
        ?>

        <div class="testimonial-slider">
        <?php
        $args = array(
            'posts_per_page' => 6,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'testimonial',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'testimonial' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
    </div>
</div><!-- .testimonials -->
<?php
}

}