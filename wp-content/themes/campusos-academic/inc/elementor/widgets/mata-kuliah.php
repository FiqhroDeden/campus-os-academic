<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Mata_Kuliah_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_mata_kuliah'; }
    public function get_title() { return __( 'Mata Kuliah', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-table'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 20, 1, 100 );

        $this->add_control( 'semester_filter', [
            'label'       => __( 'Filter Semester', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter semester tertentu', 'campusos-academic' ),
        ] );

        $this->add_control( 'konsentrasi_filter', [
            'label'       => __( 'Filter Konsentrasi', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter berdasarkan konsentrasi. Kosongkan untuk semua.', 'campusos-academic' ),
        ] );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-matkul' );

        $args = [
            'post_type'      => 'mata_kuliah',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_mata_kuliah_semester',
            'order'          => 'ASC',
        ];

        $meta_query = [];

        if ( ! empty( $settings['semester_filter'] ) ) {
            $meta_query[] = [
                'key'   => '_mata_kuliah_semester',
                'value' => sanitize_text_field( $settings['semester_filter'] ),
            ];
        }

        if ( ! empty( $settings['konsentrasi_filter'] ) ) {
            $meta_query[] = [
                'key'     => '_mata_kuliah_konsentrasi',
                'value'   => sanitize_text_field( $settings['konsentrasi_filter'] ),
                'compare' => 'LIKE',
            ];
        }

        if ( ! empty( $meta_query ) ) {
            $meta_query['relation'] = 'AND';
            $args['meta_query']     = $meta_query;
        }

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data mata kuliah.', 'campusos-academic' ) );
            return;
        }

        $this->render_base_card_css( $id );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                overflow-x: auto;
            }
            #<?php echo esc_attr( $id ); ?> table {
                width: 100%; border-collapse: collapse; min-width: 500px;
            }
            #<?php echo esc_attr( $id ); ?> thead th {
                background: var(--campusos-primary, #003d82); color: #fff;
                padding: 12px 16px; text-align: left; font-size: 0.875rem; font-weight: 600;
            }
            #<?php echo esc_attr( $id ); ?> tbody tr:nth-child(even) {
                background: #f8f9fa;
            }
            #<?php echo esc_attr( $id ); ?> tbody tr:hover {
                background: #eef2f7;
            }
            #<?php echo esc_attr( $id ); ?> tbody td {
                padding: 10px 16px; font-size: 0.875rem; color: #444; border-bottom: 1px solid #eee;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-title {
                font-size: 0.875rem; margin: 0;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-card">
            <table>
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Kode', 'campusos-academic' ); ?></th>
                        <th><?php esc_html_e( 'Mata Kuliah', 'campusos-academic' ); ?></th>
                        <th><?php esc_html_e( 'SKS', 'campusos-academic' ); ?></th>
                        <th><?php esc_html_e( 'Semester', 'campusos-academic' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ( $query->have_posts() ) : $query->the_post();
                        $pid      = get_the_ID();
                        $kode     = get_post_meta( $pid, '_mata_kuliah_kode_mk', true );
                        $sks      = get_post_meta( $pid, '_mata_kuliah_sks', true );
                        $semester = get_post_meta( $pid, '_mata_kuliah_semester', true );
                    ?>
                        <tr>
                            <td><?php echo esc_html( $kode ); ?></td>
                            <td><span class="campusos-w-title"><?php the_title(); ?></span></td>
                            <td><?php echo esc_html( $sks ); ?></td>
                            <td><?php echo esc_html( $semester ); ?></td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
