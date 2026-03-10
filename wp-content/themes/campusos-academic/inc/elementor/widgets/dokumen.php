<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Dokumen_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_dokumen'; }
    public function get_title() { return __( 'Dokumen', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-document-file'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 10 );
        $this->register_meta_filter_control(
            '_dokumen_kategori_dokumen',
            __( 'Kategori Dokumen', 'campusos-academic' ),
            [
                'peraturan'  => 'Peraturan Akademik',
                'kalender'   => 'Kalender Akademik',
                'kurikulum'  => 'Kurikulum',
                'sop'        => 'SOP',
                'mutu'       => 'Dokumen Mutu',
                'akreditasi' => 'Akreditasi',
            ]
        );

        $this->add_control( 'tahun_filter', [
            'label'       => __( 'Filter Tahun', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter berdasarkan tahun dokumen. Kosongkan untuk semua.', 'campusos-academic' ),
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
        $id       = $this->get_scoped_id( 'campusos-dokumen' );

        $args = [
            'post_type'      => 'dokumen',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $meta_query = [];

        if ( ! empty( $settings['meta_filter'] ) && $settings['meta_filter'] !== 'all' ) {
            $meta_query[] = [
                'key'   => '_dokumen_kategori_dokumen',
                'value' => sanitize_text_field( $settings['meta_filter'] ),
            ];
        }

        if ( ! empty( $settings['tahun_filter'] ) ) {
            $meta_query[] = [
                'key'   => '_dokumen_tahun_dokumen',
                'value' => sanitize_text_field( $settings['tahun_filter'] ),
            ];
        }

        if ( ! empty( $meta_query ) ) {
            $meta_query['relation'] = 'AND';
            $args['meta_query']     = $meta_query;
        }

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada dokumen.', 'campusos-academic' ) );
            return;
        }

        $kategori_labels = [
            'peraturan'  => 'Peraturan Akademik',
            'kalender'   => 'Kalender Akademik',
            'kurikulum'  => 'Kurikulum',
            'sop'        => 'SOP',
            'mutu'       => 'Dokumen Mutu',
            'akreditasi' => 'Akreditasi',
        ];

        $this->render_base_card_css( $id );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?>.campusos-w-list {
                display: flex; flex-direction: column;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card {
                display: flex; flex-direction: row; align-items: center;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card:hover {
                transform: translateY(-2px);
            }
            #<?php echo esc_attr( $id ); ?> .campusos-doc-icon {
                flex: 0 0 56px; display: flex; align-items: center; justify-content: center;
                font-size: 28px; color: var(--campusos-primary, #003d82); padding: 16px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card-content {
                flex: 1; display: flex; flex-direction: column; gap: 4px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-doc-meta {
                display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-doc-tahun {
                font-size: 0.8rem; color: #888;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-doc-download {
                flex: 0 0 auto; padding: 16px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-doc-download a {
                display: inline-flex; align-items: center; gap: 4px;
                color: var(--campusos-primary, #003d82); text-decoration: none; font-size: 0.875rem; font-weight: 500;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-doc-download a:hover { opacity: 0.7; }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-list">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid      = get_the_ID();
                $file_id  = get_post_meta( $pid, '_dokumen_file_dokumen', true );
                $kategori = get_post_meta( $pid, '_dokumen_kategori_dokumen', true );
                $tahun    = get_post_meta( $pid, '_dokumen_tahun_dokumen', true );
                $file_url = $file_id ? wp_get_attachment_url( $file_id ) : '';
                $kat_label = isset( $kategori_labels[ $kategori ] ) ? $kategori_labels[ $kategori ] : $kategori;
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-doc-icon">
                        <span class="dashicons dashicons-media-document"></span>
                    </div>
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                        <div class="campusos-doc-meta">
                            <?php if ( $kat_label ) : ?>
                                <span class="campusos-w-badge"><?php echo esc_html( $kat_label ); ?></span>
                            <?php endif; ?>
                            <?php if ( $tahun ) : ?>
                                <span class="campusos-doc-tahun"><?php echo esc_html( $tahun ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ( $file_url ) : ?>
                        <div class="campusos-doc-download">
                            <a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener">
                                <span class="dashicons dashicons-download"></span>
                                <?php esc_html_e( 'Unduh', 'campusos-academic' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
