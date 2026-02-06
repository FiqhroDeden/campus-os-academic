<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Why_Choose_Us extends \Elementor\Widget_Base {

    public function get_name() { return 'campusos_why_choose_us'; }
    public function get_title() { return __( 'Why Choose Us', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-info-box'; }
    public function get_categories() { return [ 'campusos-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Features', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '2' => '2', '3' => '3', '4' => '4' ],
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'selected_icon', [
            'label'   => __( 'Icon', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fas fa-star',
                'library' => 'fa-solid',
            ],
        ] );

        $repeater->add_control( 'title', [
            'label'   => __( 'Title', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Feature Title', 'campusos-academic' ),
        ] );

        $repeater->add_control( 'description', [
            'label'   => __( 'Description', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __( 'Feature description goes here.', 'campusos-academic' ),
        ] );

        $this->add_control( 'features', [
            'label'   => __( 'Features', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [ 'title' => __( 'Akreditasi Unggul', 'campusos-academic' ), 'description' => __( 'Terakreditasi A oleh BAN-PT.', 'campusos-academic' ) ],
                [ 'title' => __( 'Dosen Berkualitas', 'campusos-academic' ), 'description' => __( 'Tenaga pendidik berpengalaman dan berkualifikasi tinggi.', 'campusos-academic' ) ],
                [ 'title' => __( 'Fasilitas Modern', 'campusos-academic' ), 'description' => __( 'Laboratorium dan fasilitas kampus berstandar internasional.', 'campusos-academic' ) ],
            ],
            'title_field' => '{{{ title }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cols     = intval( $settings['columns'] );
        $features = $settings['features'];
        if ( empty( $features ) ) return;

        $id = 'campusos-why-' . $this->get_id();
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: grid; grid-template-columns: repeat(<?php echo $cols; ?>, 1fr);
                gap: 30px; padding: 30px 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-feature-box {
                background: #fff; border-radius: 8px; padding: 30px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.06); text-align: center; transition: transform .3s, box-shadow .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-feature-box:hover {
                transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            }
            #<?php echo esc_attr( $id ); ?> .campusos-feature-icon {
                font-size: 2.5rem; color: var(--campusos-primary, #003d82); margin-bottom: 15px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-feature-box h4 { margin: 0 0 10px; font-size: 1.15rem; color: #222; }
            #<?php echo esc_attr( $id ); ?> .campusos-feature-box p { margin: 0; font-size: 0.9rem; color: #666; line-height: 1.6; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> { grid-template-columns: 1fr; }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php foreach ( $features as $feature ) : ?>
                <div class="campusos-feature-box">
                    <div class="campusos-feature-icon">
                        <?php
                        if ( ! empty( $feature['selected_icon']['value'] ) ) {
                            \Elementor\Icons_Manager::render_icon( $feature['selected_icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </div>
                    <h4><?php echo esc_html( $feature['title'] ); ?></h4>
                    <p><?php echo esc_html( $feature['description'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
