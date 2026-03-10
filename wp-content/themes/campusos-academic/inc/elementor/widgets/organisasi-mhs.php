<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Organisasi_Mhs_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_organisasi_mhs'; }
    public function get_title() { return __( 'Organisasi Mahasiswa', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-person'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 6 );
        $this->register_columns_control( 3 );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-orgmhs' );
        $cols     = intval( $settings['columns'] );

        $args = [
            'post_type'      => 'organisasi_mhs',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data organisasi mahasiswa.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        $this->render_base_card_css( $id );
        ?>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid     = get_the_ID();
                $logo_id = get_post_meta( $pid, '_organisasi_mhs_logo_org', true );
                $desc    = $this->get_description( $pid, '_organisasi_mhs_deskripsi_org', 20 );
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-image">
                        <?php if ( $logo_id ) : ?>
                            <?php echo wp_get_attachment_image( $logo_id, 'campusos-card', false, [ 'alt' => get_the_title() ] ); ?>
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'campusos-card' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                        <?php if ( $desc ) : ?>
                            <p class="campusos-w-body"><?php echo esc_html( $desc ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
