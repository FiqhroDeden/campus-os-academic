<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Kerjasama_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_kerjasama'; }
    public function get_title() { return __( 'Kerjasama', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-flow'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 6 );
        $this->register_columns_control( 3 );
        $this->register_meta_filter_control(
            '_kerjasama_jenis_kerjasama',
            __( 'Jenis Kerjasama', 'campusos-academic' ),
            [ 'dn' => 'Dalam Negeri', 'ln' => 'Luar Negeri' ]
        );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_badge_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-kerjasama' );
        $cols     = intval( $settings['columns'] );

        $args = [
            'post_type'      => 'kerjasama',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if ( ! empty( $settings['meta_filter'] ) && $settings['meta_filter'] !== 'all' ) {
            $args['meta_query'] = [ [
                'key'   => '_kerjasama_jenis_kerjasama',
                'value' => sanitize_text_field( $settings['meta_filter'] ),
            ] ];
        }

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data kerjasama.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        $this->render_base_card_css( $id );
        ?>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid        = get_the_ID();
                $logo_id    = get_post_meta( $pid, '_kerjasama_logo_mitra', true );
                $jenis      = get_post_meta( $pid, '_kerjasama_jenis_kerjasama', true );
                $tgl_mulai  = get_post_meta( $pid, '_kerjasama_tanggal_mulai', true );
                $tgl_akhir  = get_post_meta( $pid, '_kerjasama_tanggal_akhir', true );

                $jenis_labels = [ 'dn' => 'Dalam Negeri', 'ln' => 'Luar Negeri' ];
                $jenis_label  = isset( $jenis_labels[ $jenis ] ) ? $jenis_labels[ $jenis ] : $jenis;

                $periode = '';
                if ( $tgl_mulai ) {
                    $periode = date_i18n( 'M Y', strtotime( $tgl_mulai ) );
                    if ( $tgl_akhir ) {
                        $periode .= ' - ' . date_i18n( 'M Y', strtotime( $tgl_akhir ) );
                    } else {
                        $periode .= ' - ' . __( 'Sekarang', 'campusos-academic' );
                    }
                }
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
                        <h4 class="campusos-w-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        <?php if ( $jenis_label ) : ?>
                            <span class="campusos-w-badge"><?php echo esc_html( $jenis_label ); ?></span>
                        <?php endif; ?>
                        <?php if ( $periode ) : ?>
                            <p class="campusos-w-body" style="margin-top:8px;"><?php echo esc_html( $periode ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
