<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class UNPATTI_Team_Grid extends \Elementor\Widget_Base {

    public function get_name() { return 'unpatti_team_grid'; }
    public function get_title() { return __( 'Team Grid', 'unpatti-academic' ); }
    public function get_icon() { return 'eicon-person'; }
    public function get_categories() { return [ 'unpatti-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'unpatti-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'source', [
            'label'   => __( 'Source', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'pimpinan',
            'options' => [
                'pimpinan'         => __( 'Pimpinan', 'unpatti-academic' ),
                'tenaga_pendidik'  => __( 'Tenaga Pendidik', 'unpatti-academic' ),
            ],
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min'     => 1,
            'max'     => 50,
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [ '3' => '3', '4' => '4' ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cols     = intval( $settings['columns'] );
        $id       = 'unpatti-team-' . $this->get_id();

        $query = new \WP_Query( [
            'post_type'      => $settings['source'],
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
        ] );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: grid; grid-template-columns: repeat(<?php echo $cols; ?>, 1fr);
                gap: 30px; padding: 20px 0;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-team-card {
                background: #fff; border-radius: 8px; overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08); text-align: center; transition: transform .3s;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-team-card:hover { transform: translateY(-5px); }
            #<?php echo esc_attr( $id ); ?> .unpatti-team-card img {
                width: 100%; height: 280px; object-fit: cover;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-team-info { padding: 15px; }
            #<?php echo esc_attr( $id ); ?> .unpatti-team-info h4 { margin: 0 0 5px; font-size: 1rem; color: #222; }
            #<?php echo esc_attr( $id ); ?> .unpatti-team-info p { margin: 0; font-size: 0.85rem; color: #666; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> { grid-template-columns: repeat(2, 1fr); }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                $jabatan = get_post_meta( get_the_ID(), '_jabatan', true );
                $nip     = get_post_meta( get_the_ID(), '_nip', true );
            ?>
                <div class="unpatti-team-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'unpatti-profile' ); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/placeholder-profile.png' ); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    <div class="unpatti-team-info">
                        <h4><?php the_title(); ?></h4>
                        <?php if ( $jabatan ) : ?><p><?php echo esc_html( $jabatan ); ?></p><?php endif; ?>
                        <?php if ( $nip ) : ?><p>NIP: <?php echo esc_html( $nip ); ?></p><?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p><?php esc_html_e( 'No team members found.', 'unpatti-academic' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
