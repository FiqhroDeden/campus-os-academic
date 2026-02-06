<?php

class Edunia_Gtk extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_gtk';
}

public function get_title() {
    return esc_html__( 'GTK', 'edunia' );
}

public function get_icon() {
    return 'eicon-person';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'gtk' ];
}

public function get_script_depends() {
    wp_register_script( 'edunia-gtk', get_template_directory_uri() . '/assets/js/elementor-gtk.js', [ 'jquery', 'slick-script' ] );
    return [
        'edunia-gtk',
    ];
}

protected function register_controls() {

    $this->start_controls_section(
        'gtk_section',
        [
            'label' => esc_html__( 'GTK', 'edunia' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
    'gtk_bgcolor',
    [
        'label' => esc_html__( 'Background Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gtk_background',
    [
        'label' => esc_html__( 'Select Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'alpha' => false,
        'default' => '#ffffff',
    ]
    );

    $this->add_control(
    'gtk_background_style',
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
    'gtk_bgimage',
    [
        'label' => esc_html__( 'Background Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gtk_background_image',
    [
        'label' => esc_html__( 'Select Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'gtk_background_fixed',
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
    'gtk_padtop',
    [
        'label' => esc_html__( 'Padding Top', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gtk_padtop_desktop',
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
            ':root' => '--gtk-padtop: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'gtk_padtop_mobile',
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
            ':root' => '--gtk-padtop-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gtk_padbot',
    [
        'label' => esc_html__( 'Padding Bottom', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gtk_padbot_desktop',
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
            ':root' => '--gtk-padbot: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'gtk_padbot_mobile',
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
            ':root' => '--gtk-padbot-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gtk_title_heading',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gtk_title',
    [
        'label' => esc_html__( 'Main', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
    ]
    );

    $this->add_control(
    'gtk_description_heading',
    [
        'label' => esc_html__( 'Description', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gtk_description',
    [
        'label' => esc_html__( 'Insert', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 4,
        'show_label' => false,
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gtk_limit_home',
    [
        'label' => esc_html__( 'Number Displayed', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => '12',
        'options' => [
            '6' => '6',
            '8' => '8',
            '10' => '10',
            '12' => '12',
            '14' => '14',
            '16' => '16',
            '18' => '18',
            '20' => '20',
            'nolimit' => __( 'No Limit', 'edunia' ),
        ],
    ]
    );

    $this->end_controls_section();

}

protected function render() {
    $settings = $this->get_settings_for_display();
    $gtk_background = ((!empty($settings['gtk_background']))?$settings['gtk_background']:'#ffffff');
    $gtk_background_style = ((!empty($settings['gtk_background_style']))?$settings['gtk_background_style']:'solid');
    $gtk_background_image = $settings['gtk_background_image']['url'];
    $gtk_background_fixed = (($settings['gtk_background_fixed'] == 'yes')?1:0);
?>
<div class="gtk-wrapper section-wrapper" style="<?php get_section_background($gtk_background, $gtk_background_style, $gtk_background_image, $gtk_background_fixed);?>">
    <div class="container">
        <?php
        if ( ! empty( $settings['gtk_title'] ) ) {
        ?>
        <div class="section-heading">
        <h2 class="title"><?php echo $settings['gtk_title'];?></h2>
        <?php echo ((!empty($settings['gtk_description']))?'<div class="description">'.$settings['gtk_description'].'</div>':'');?>
        </div>
        <?php
        }
        ?>

        <div class="gtk-slider">
        <?php
        $gtk_limit = ((!empty($settings['gtk_limit_home']))?$settings['gtk_limit_home']:'12');
        if ( $gtk_limit == 'nolimit' ) {
            $gtk_limit = '-1';
        }

        $args = array(
            'posts_per_page' => $gtk_limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'gtk',
            'post_status' => 'publish'
        );
        $the_query = new WP_Query( $args ); 
        if ( $the_query->have_posts() ) : 
            while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'template-parts/contents/archive', 'gtk' );
            endwhile;
        else:
            echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
        endif;
        wp_reset_postdata();
        ?>
        </div>
    </div>
</div><!-- .gtk-wrapper -->
<?php
}

}