<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Stats_Counter extends \Elementor\Widget_Base {

    public function get_name() { return 'campusos_stats_counter'; }
    public function get_title() { return __( 'Stats Counter', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-counter'; }
    public function get_categories() { return [ 'campusos-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Stats', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [ '2' => '2', '3' => '3', '4' => '4' ],
        ] );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control( 'icon', [
            'label'   => __( 'Icon (CSS class or emoji)', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => '🎓',
        ] );

        $repeater->add_control( 'number', [
            'label'   => __( 'Number', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 100,
        ] );

        $repeater->add_control( 'label', [
            'label'   => __( 'Label', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __( 'Students', 'campusos-academic' ),
        ] );

        $this->add_control( 'stats', [
            'label'   => __( 'Stats', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [ 'icon' => '🎓', 'number' => 30000, 'label' => __( 'Mahasiswa', 'campusos-academic' ) ],
                [ 'icon' => '👨‍🏫', 'number' => 1200, 'label' => __( 'Dosen', 'campusos-academic' ) ],
                [ 'icon' => '📚', 'number' => 80, 'label' => __( 'Program Studi', 'campusos-academic' ) ],
                [ 'icon' => '🏛️', 'number' => 12, 'label' => __( 'Fakultas', 'campusos-academic' ) ],
            ],
            'title_field' => '{{{ label }}}',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cols     = intval( $settings['columns'] );
        $stats    = $settings['stats'];
        if ( empty( $stats ) ) return;

        $id = 'campusos-stats-' . $this->get_id();
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: grid;
                grid-template-columns: repeat(<?php echo $cols; ?>, 1fr);
                gap: 30px; text-align: center; padding: 40px 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-stat-item .campusos-stat-icon { font-size: 2.5rem; margin-bottom: 10px; }
            #<?php echo esc_attr( $id ); ?> .campusos-stat-number {
                font-size: 2.5rem; font-weight: 700; color: var(--campusos-primary, #003d82);
            }
            #<?php echo esc_attr( $id ); ?> .campusos-stat-label { font-size: 1rem; color: #555; margin-top: 5px; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> { grid-template-columns: repeat(2, 1fr); }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php foreach ( $stats as $stat ) : ?>
                <div class="campusos-stat-item">
                    <div class="campusos-stat-icon"><?php echo wp_kses_post( $stat['icon'] ); ?></div>
                    <div class="campusos-stat-number" data-count="<?php echo intval( $stat['number'] ); ?>">0</div>
                    <div class="campusos-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <script>
        (function(){
            const el = document.getElementById('<?php echo esc_js( $id ); ?>');
            if (!el) return;
            const counters = el.querySelectorAll('.campusos-stat-number');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        counters.forEach(counter => {
                            const target = parseInt(counter.getAttribute('data-count'));
                            const duration = 2000;
                            const step = target / (duration / 16);
                            let current = 0;
                            const timer = setInterval(() => {
                                current += step;
                                if (current >= target) { current = target; clearInterval(timer); }
                                counter.textContent = Math.floor(current).toLocaleString('id-ID');
                            }, 16);
                        });
                        observer.disconnect();
                    }
                });
            }, { threshold: 0.3 });
            observer.observe(el);
        })();
        </script>
        <?php
    }
}
