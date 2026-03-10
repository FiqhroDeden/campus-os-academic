<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Fasilitas_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_fasilitas'; }
    public function get_title() { return __( 'Fasilitas', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-image-box'; }

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
        $id       = $this->get_scoped_id( 'campusos-fasilitas' );
        $cols     = intval( $settings['columns'] );

        $args = [
            'post_type'      => 'fasilitas',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data fasilitas.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        $this->render_base_card_css( $id );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> .campusos-w-meta-row {
                display: flex; align-items: center; gap: 6px;
                font-size: 0.8rem; color: #666; margin-top: 6px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-meta-row .dashicons {
                font-size: 16px; width: 16px; height: 16px; color: var(--campusos-primary, #003d82);
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid       = get_the_ID();
                $lokasi    = get_post_meta( $pid, '_fasilitas_lokasi', true );
                $kapasitas = get_post_meta( $pid, '_fasilitas_kapasitas', true );
                $desc      = $this->get_description( $pid, '_fasilitas_deskripsi_fasilitas', 15 );
            ?>
                <div class="campusos-w-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="campusos-w-image">
                            <?php the_post_thumbnail( 'campusos-card' ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                        <?php if ( $desc ) : ?>
                            <p class="campusos-w-body"><?php echo esc_html( $desc ); ?></p>
                        <?php endif; ?>
                        <?php if ( $lokasi ) : ?>
                            <div class="campusos-w-meta-row">
                                <span class="dashicons dashicons-location"></span>
                                <span><?php echo esc_html( $lokasi ); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ( $kapasitas ) : ?>
                            <div class="campusos-w-meta-row">
                                <span class="dashicons dashicons-groups"></span>
                                <span><?php echo esc_html( $kapasitas ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
