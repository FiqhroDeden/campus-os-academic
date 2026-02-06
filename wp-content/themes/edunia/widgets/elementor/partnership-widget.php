<?php

class Edunia_PartnerShip extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_partnership';
}

public function get_title() {
    return esc_html__( 'Partnership', 'edunia' );
}

public function get_icon() {
    return 'eicon-time-line';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'partnership', 'mitra', 'kerjasama' ];
}

public function get_script_depends() {
    wp_register_script( 'edunia-partnership', get_template_directory_uri() . '/assets/js/elementor-partnership.js', [ 'jquery', 'slick-script' ] );
    return [
        'edunia-partnership',
    ];
}

protected function register_controls() {

    $this->start_controls_section(
        'partnership_section',
        [
            'label' => esc_html__( 'Partnership', 'edunia' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
    'partnership_bgcolor',
    [
        'label' => esc_html__( 'Background Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'partnership_background',
    [
        'label' => esc_html__( 'Select Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'alpha' => false,
        'default' => '#ffffff',
    ]
    );

    $this->add_control(
    'partnership_background_style',
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
    'partnership_bgimage',
    [
        'label' => esc_html__( 'Background Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'partnership_background_image',
    [
        'label' => esc_html__( 'Select Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'partnership_background_fixed',
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
    'partnership_padtop',
    [
        'label' => esc_html__( 'Padding Top', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'partnership_padtop_desktop',
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
            ':root' => '--partnership-padtop: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'partnership_padtop_mobile',
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
            ':root' => '--partnership-padtop-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'partnership_padbot',
    [
        'label' => esc_html__( 'Padding Bottom', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'partnership_padbot_desktop',
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
            ':root' => '--partnership-padbot: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'partnership_padbot_mobile',
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
            ':root' => '--partnership-padbot-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'partnership_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'partnership_maintitle',
    [
        'label' => esc_html__( 'Main', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
    ]
    );

    $this->add_control(
    'partnership_secondtitle',
    [
        'label' => esc_html__( 'Second', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'partnership_description_heading',
    [
        'label' => esc_html__( 'Description', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'partnership_description',
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
    $partnership_background = ((!empty($settings['partnership_background']))?$settings['partnership_background']:'#ffffff');
    $partnership_background_style = ((!empty($settings['partnership_background_style']))?$settings['partnership_background_style']:'solid');
    $partnership_background_image = $settings['partnership_background_image']['url'];
    $partnership_background_fixed = (($settings['partnership_background_fixed'] == 'yes')?1:0);
?>
<div class="partnership-wrapper section-wrapper" style="<?php get_section_background($partnership_background, $partnership_background_style, $partnership_background_image, $partnership_background_fixed);?>">
    <div class="container">
        <?php
        if ( ! empty( $settings['partnership_maintitle'] ) || ! empty( $settings['partnership_secondtitle'] ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo $settings['partnership_maintitle'] . ((!empty($settings['partnership_secondtitle']))?' <span>'.$settings['partnership_secondtitle'].'</span>':'');?></h2>
            <?php echo ((!empty($settings['partnership_description']))?'<div class="description">'.$settings['partnership_description'].'</div>':'');?>
        </div>
        <?php
        }
        ?>

        <div class="partner-list">
        <?php
        $args = array(
            'posts_per_page' => 12,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'mitra',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'mitra' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
    </div>
</div><!-- .partnership -->
<?php
}

}