<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class UNPATTI_Hero_Slider extends \Elementor\Widget_Base {

    public function get_name() { return 'unpatti_hero_slider'; }
    public function get_title() { return __( 'Hero Slider', 'unpatti-academic' ); }
    public function get_icon() { return 'eicon-slider-push'; }
    public function get_categories() { return [ 'unpatti-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Slides', 'unpatti-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'image', [
            'label'   => __( 'Background Image', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [ 'url' => \Elementor\Utils::get_placeholder_image_src() ],
        ] );

        $repeater->add_control( 'title', [
            'label'   => __( 'Title', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Slide Title', 'unpatti-academic' ),
        ] );

        $repeater->add_control( 'subtitle', [
            'label'   => __( 'Subtitle', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Slide subtitle text', 'unpatti-academic' ),
        ] );

        $repeater->add_control( 'button_text', [
            'label'   => __( 'Button Text', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Learn More', 'unpatti-academic' ),
        ] );

        $repeater->add_control( 'button_url', [
            'label'       => __( 'Button URL', 'unpatti-academic' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => __( 'https://example.com', 'unpatti-academic' ),
        ] );

        $this->add_control( 'slides', [
            'label'   => __( 'Slides', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [ 'title' => __( 'Welcome to UNPATTI', 'unpatti-academic' ), 'subtitle' => __( 'Universitas Pattimura Ambon', 'unpatti-academic' ) ],
            ],
            'title_field' => '{{{ title }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides   = $settings['slides'];
        if ( empty( $slides ) ) return;

        $id       = 'unpatti-hero-' . $this->get_id();
        $count    = count( $slides );
        $duration = $count * 5;
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                position: relative; width: 100%; height: 500px; overflow: hidden;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-track {
                display: flex; width: <?php echo $count * 100; ?>%;
                animation: <?php echo esc_attr( $id ); ?>-scroll <?php echo $duration; ?>s infinite;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-slide {
                width: <?php echo 100 / $count; ?>%; height: 500px; flex-shrink: 0;
                position: relative; display: flex; align-items: center; justify-content: center;
                background-size: cover; background-position: center; color: #fff; text-align: center;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-slide::before {
                content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.5);
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-content {
                position: relative; z-index: 2; max-width: 700px; padding: 20px;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-content h2 {
                font-size: 2.5rem; margin: 0 0 10px; color: #fff;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-content p {
                font-size: 1.2rem; margin: 0 0 20px; color: rgba(255,255,255,0.9);
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-btn {
                display: inline-block; padding: 12px 30px; background: var(--unpatti-primary, #003d82);
                color: #fff; text-decoration: none; border-radius: 4px; font-weight: 600; transition: background .3s;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-hero-btn:hover { background: var(--unpatti-secondary, #e67e22); }
            @keyframes <?php echo esc_attr( $id ); ?>-scroll {
                <?php for ( $i = 0; $i < $count; $i++ ) :
                    $start = ( $i / $count ) * 100;
                    $hold  = ( ( $i + 0.8 ) / $count ) * 100;
                    $end   = ( ( $i + 1 ) / $count ) * 100;
                ?>
                <?php echo $start; ?>%  { transform: translateX(-<?php echo $i * ( 100 / $count ); ?>%); }
                <?php echo $hold; ?>%   { transform: translateX(-<?php echo $i * ( 100 / $count ); ?>%); }
                <?php if ( $i < $count - 1 ) : ?>
                <?php echo $end; ?>%    { transform: translateX(-<?php echo ( $i + 1 ) * ( 100 / $count ); ?>%); }
                <?php endif; ?>
                <?php endfor; ?>
                100% { transform: translateX(0%); }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <div class="unpatti-hero-track">
                <?php foreach ( $slides as $slide ) :
                    $img_url = ! empty( $slide['image']['url'] ) ? $slide['image']['url'] : '';
                    $btn_url = ! empty( $slide['button_url']['url'] ) ? $slide['button_url']['url'] : '#';
                    $btn_target = ! empty( $slide['button_url']['is_external'] ) ? ' target="_blank"' : '';
                    $btn_nofollow = ! empty( $slide['button_url']['nofollow'] ) ? ' rel="nofollow"' : '';
                ?>
                <div class="unpatti-hero-slide" style="background-image:url('<?php echo esc_url( $img_url ); ?>')">
                    <div class="unpatti-hero-content">
                        <?php if ( ! empty( $slide['title'] ) ) : ?>
                            <h2><?php echo esc_html( $slide['title'] ); ?></h2>
                        <?php endif; ?>
                        <?php if ( ! empty( $slide['subtitle'] ) ) : ?>
                            <p><?php echo esc_html( $slide['subtitle'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $slide['button_text'] ) ) : ?>
                            <a href="<?php echo esc_url( $btn_url ); ?>" class="unpatti-hero-btn"<?php echo $btn_target . $btn_nofollow; ?>><?php echo esc_html( $slide['button_text'] ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
