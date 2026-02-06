<?php

class Edunia_Gallery extends \Elementor\Widget_Base {

public function get_name() {
    return 'edunia_gallery';
}

public function get_title() {
    return esc_html__( 'Gallery', 'edunia' );
}

public function get_icon() {
    return 'eicon-photo-library';
}

public function get_categories() {
    return [ 'general' ];
}

public function get_keywords() {
    return [ 'edunia', 'gallery', 'galeri' ];
}

protected function register_controls() {

    $this->start_controls_section(
        'gallery_section',
        [
            'label' => esc_html__( 'Gallery', 'edunia' ),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

    $this->add_control(
    'gallery_bgcolor',
    [
        'label' => esc_html__( 'Background Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gallery_background',
    [
        'label' => esc_html__( 'Select Color', 'edunia' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'alpha' => false,
        'default' => '#d4ebf1',
    ]
    );

    $this->add_control(
    'gallery_background_style',
    [
        'label' => esc_html__( 'Select Style', 'edunia' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
            'solid' => esc_html__( 'Solid', 'edunia' ),
            'gradient_one' => esc_html__( 'Gradient 1', 'edunia' ),
            'gradient_two' => esc_html__( 'Gradient 2', 'edunia' ),
        ],
        'default' => 'gradient_two',
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gallery_bgimage',
    [
        'label' => esc_html__( 'Background Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gallery_background_image',
    [
        'label' => esc_html__( 'Select Image', 'edunia' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
    ]
    );

    $this->add_control(
    'gallery_background_fixed',
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
    'gallery_padtop',
    [
        'label' => esc_html__( 'Padding Top', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gallery_padtop_desktop',
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
            ':root' => '--gallery-padtop: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'gallery_padtop_mobile',
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
            ':root' => '--gallery-padtop-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gallery_padbot',
    [
        'label' => esc_html__( 'Padding Bottom', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gallery_padbot_desktop',
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
            ':root' => '--gallery-padbot: {{SIZE}}px;',
        ],
    ]
    );

    $this->add_control(
    'gallery_padbot_mobile',
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
            ':root' => '--gallery-padbot-mobile: {{SIZE}}px;',
        ],
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gallery_title',
    [
        'label' => esc_html__( 'Title', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gallery_maintitle',
    [
        'label' => esc_html__( 'Main', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
    ]
    );

    $this->add_control(
    'gallery_secondtitle',
    [
        'label' => esc_html__( 'Second', 'edunia' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'separator' => 'after'
    ]
    );

    $this->add_control(
    'gallery_description_heading',
    [
        'label' => esc_html__( 'Description', 'edunia' ),
        'type' => \Elementor\Controls_Manager::HEADING,
    ]
    );

    $this->add_control(
    'gallery_description',
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
    $gallery_background = ((!empty($settings['gallery_background']))?$settings['gallery_background']:'#d4ebf1');
    $gallery_background_style = ((!empty($settings['gallery_background_style']))?$settings['gallery_background_style']:'gradient_two');
    $gallery_background_image = $settings['gallery_background_image']['url'];
    $gallery_background_fixed = (($settings['gallery_background_fixed'] == 'yes')?1:0);
?>
<div class="gallery-wrapper section-wrapper" style="<?php get_section_background($gallery_background, $gallery_background_style, $gallery_background_image, $gallery_background_fixed);?>">
    <div class="container">
        <?php
        if ( ! empty( $settings['gallery_maintitle'] ) || ! empty( $settings['gallery_secondtitle'] ) ) {
        ?>
        <div class="section-heading">
            <h2 class="title"><?php echo $settings['gallery_maintitle'] . ((!empty($settings['gallery_secondtitle']))?' <span>'.$settings['gallery_secondtitle'].'</span>':'');?></h2>
            <?php echo ((!empty($settings['gallery_description']))?'<div class="description">'.$settings['gallery_description'].'</div>':'');?>
            <a class="more-link" href="<?php echo get_post_type_archive_link('galeri');?>"><span class="text"><?php echo __('View All', 'edunia');?></span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg></span></a>
        </div>
        <?php
        }
        ?>

        <div class="gallery-content">
            <?php
            $args = array(
                'posts_per_page' => 8,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'galeri',
                'post_status' => 'publish'
            );
            $the_query = new WP_Query( $args ); 
            if ( $the_query->have_posts() ) : 
                while ( $the_query->have_posts() ) : $the_query->the_post();
                get_template_part( 'template-parts/contents/archive', 'galeri' );
                endwhile;
            else:
                echo '<div class="na-text">'.__('Not Added', 'edunia').'</div>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div><!-- .gallery-wrapper -->
<?php
}

}