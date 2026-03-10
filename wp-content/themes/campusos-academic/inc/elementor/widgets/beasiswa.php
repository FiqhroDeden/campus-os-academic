<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Beasiswa_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_beasiswa'; }
    public function get_title() { return __( 'Beasiswa', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-price-table'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 6 );
        $this->register_columns_control( 2 );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_badge_section();
        $this->register_style_button_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-beasiswa' );
        $cols     = intval( $settings['columns'] );

        $args = [
            'post_type'      => 'beasiswa',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data beasiswa.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        $this->render_base_card_css( $id );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> .campusos-w-badge {
                display: inline-flex; align-items: center; gap: 4px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-badge .dashicons {
                font-size: 14px; width: 14px; height: 14px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-btn {
                margin-top: 12px;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid       = get_the_ID();
                $deadline  = get_post_meta( $pid, '_beasiswa_deadline_beasiswa', true );
                $link      = get_post_meta( $pid, '_beasiswa_link_pendaftaran', true );
                $desc      = $this->get_description( $pid, '_beasiswa_deskripsi_beasiswa', 20 );
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <?php if ( $deadline ) : ?>
                            <span class="campusos-w-badge">
                                <span class="dashicons dashicons-calendar-alt"></span>
                                <?php echo esc_html( date_i18n( 'j F Y', strtotime( $deadline ) ) ); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ( $desc ) : ?>
                            <p class="campusos-w-body" style="margin-top:10px;"><?php echo esc_html( $desc ); ?></p>
                        <?php endif; ?>
                        <?php if ( $link ) : ?>
                            <a href="<?php echo esc_url( $link ); ?>" class="campusos-w-btn" target="_blank" rel="noopener">
                                <?php esc_html_e( 'Daftar Sekarang', 'campusos-academic' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
