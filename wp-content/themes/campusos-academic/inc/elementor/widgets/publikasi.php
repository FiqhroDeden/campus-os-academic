<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Publikasi_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_publikasi'; }
    public function get_title() { return __( 'Publikasi', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-post-list'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 10 );
        $this->register_meta_filter_control(
            '_publikasi_jenis_pub',
            __( 'Jenis Publikasi', 'campusos-academic' ),
            [
                'jurnal'   => 'Jurnal',
                'prosiding' => 'Prosiding',
                'buku'     => 'Buku',
                'hki'      => 'HKI',
            ]
        );

        $this->add_control( 'tahun_filter', [
            'label'       => __( 'Filter Tahun', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter berdasarkan tahun publikasi. Kosongkan untuk semua.', 'campusos-academic' ),
        ] );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_badge_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-publikasi' );

        $args = [
            'post_type'      => 'publikasi',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $meta_query = [];

        if ( ! empty( $settings['meta_filter'] ) && $settings['meta_filter'] !== 'all' ) {
            $meta_query[] = [
                'key'   => '_publikasi_jenis_pub',
                'value' => sanitize_text_field( $settings['meta_filter'] ),
            ];
        }

        if ( ! empty( $settings['tahun_filter'] ) ) {
            $meta_query[] = [
                'key'   => '_publikasi_tahun_pub',
                'value' => sanitize_text_field( $settings['tahun_filter'] ),
            ];
        }

        if ( ! empty( $meta_query ) ) {
            $meta_query['relation'] = 'AND';
            $args['meta_query']     = $meta_query;
        }

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data publikasi.', 'campusos-academic' ) );
            return;
        }

        $jenis_labels = [
            'jurnal'    => 'Jurnal',
            'prosiding' => 'Prosiding',
            'buku'      => 'Buku',
            'hki'       => 'HKI',
        ];

        $this->render_base_card_css( $id );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?>.campusos-w-list {
                display: flex; flex-direction: column;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card:hover {
                transform: translateY(-2px);
            }
            #<?php echo esc_attr( $id ); ?> .campusos-pub-meta {
                display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 6px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-pub-tahun {
                font-size: 0.8rem; color: #888; font-weight: 500;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-pub-doi {
                font-size: 0.8rem; color: #999; margin-top: 4px; word-break: break-all;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-pub-doi a {
                color: var(--campusos-primary, #003d82); text-decoration: none;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-pub-doi a:hover { text-decoration: underline; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-btn { margin-top: 10px; }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-list">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid    = get_the_ID();
                $penulis = get_post_meta( $pid, '_publikasi_penulis_pub', true );
                $jenis   = get_post_meta( $pid, '_publikasi_jenis_pub', true );
                $tahun   = get_post_meta( $pid, '_publikasi_tahun_pub', true );
                $link    = get_post_meta( $pid, '_publikasi_link_pub', true );
                $doi     = get_post_meta( $pid, '_publikasi_doi_pub', true );

                $jenis_label = isset( $jenis_labels[ $jenis ] ) ? $jenis_labels[ $jenis ] : $jenis;
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                        <?php if ( $penulis ) : ?>
                            <p class="campusos-w-body"><?php echo esc_html( $penulis ); ?></p>
                        <?php endif; ?>
                        <div class="campusos-pub-meta">
                            <?php if ( $jenis_label ) : ?>
                                <span class="campusos-w-badge"><?php echo esc_html( $jenis_label ); ?></span>
                            <?php endif; ?>
                            <?php if ( $tahun ) : ?>
                                <span class="campusos-pub-tahun"><?php echo esc_html( $tahun ); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ( $doi ) : ?>
                            <div class="campusos-pub-doi">
                                DOI: <a href="https://doi.org/<?php echo esc_attr( $doi ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $doi ); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if ( $link ) : ?>
                            <a href="<?php echo esc_url( $link ); ?>" class="campusos-w-btn" target="_blank" rel="noopener">
                                <?php esc_html_e( 'Lihat Publikasi', 'campusos-academic' ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
