<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Team_Grid extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_team_grid'; }
    public function get_title() { return __( 'Team Grid', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-person'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'source', [
            'label'   => __( 'Source', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'pimpinan',
            'options' => [
                'pimpinan'         => __( 'Pimpinan', 'campusos-academic' ),
                'tenaga_pendidik'  => __( 'Tenaga Pendidik', 'campusos-academic' ),
            ],
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min'     => 1,
            'max'     => 50,
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [ '3' => '3', '4' => '4' ],
        ] );

        $this->end_controls_section();

        // Style Tabs
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cols     = intval( $settings['columns'] );
        $id       = 'campusos-team-' . $this->get_id();

        $jabatan_labels = [
            'guru_besar'    => 'Guru Besar',
            'lektor_kepala' => 'Lektor Kepala',
            'lektor'        => 'Lektor',
            'asisten_ahli'  => 'Asisten Ahli',
        ];

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
            #<?php echo esc_attr( $id ); ?> .campusos-team-card {
                background: #fff; border-radius: 8px; overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08); text-align: center; transition: transform .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-team-card:hover { transform: translateY(-5px); }
            #<?php echo esc_attr( $id ); ?> a.campusos-team-link { text-decoration: none; color: inherit; display: block; }
            #<?php echo esc_attr( $id ); ?> .campusos-team-card img {
                width: 100%; height: 280px; object-fit: cover;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-team-info { padding: 15px; }
            #<?php echo esc_attr( $id ); ?> .campusos-team-info h4 { margin: 0 0 5px; font-size: 1rem; color: #222; }
            #<?php echo esc_attr( $id ); ?> .campusos-team-info p { margin: 0; font-size: 0.85rem; color: #666; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> { grid-template-columns: repeat(2, 1fr); }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                $keahlian = '';
                $email    = '';
                if ( $settings['source'] === 'pimpinan' ) {
                    $jabatan = get_post_meta( get_the_ID(), '_pimpinan_jabatan', true );
                    $nip     = get_post_meta( get_the_ID(), '_pimpinan_nip', true );
                } else {
                    $jabatan_raw = get_post_meta( get_the_ID(), '_tenaga_pendidik_jabatan_fungsional', true );
                    $jabatan     = isset( $jabatan_labels[ $jabatan_raw ] ) ? $jabatan_labels[ $jabatan_raw ] : $jabatan_raw;
                    $nip         = get_post_meta( get_the_ID(), '_tenaga_pendidik_nidn', true );
                    $keahlian    = get_post_meta( get_the_ID(), '_tenaga_pendidik_bidang_keahlian', true );
                    $email       = get_post_meta( get_the_ID(), '_tenaga_pendidik_email_dosen', true );
                }
            ?>
                <a href="<?php the_permalink(); ?>" class="campusos-team-link">
                <div class="campusos-team-card campusos-w-card">
                    <div class="campusos-w-image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'campusos-profile' ); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/placeholder-profile.png' ); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php endif; ?>
                    </div>
                    <div class="campusos-team-info campusos-w-card-content">
                        <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                        <?php if ( $jabatan ) : ?><p class="campusos-w-body"><?php echo esc_html( $jabatan ); ?></p><?php endif; ?>
                        <?php if ( $keahlian ) : ?><p class="campusos-w-body" style="font-size:0.8rem;color:#888;"><?php echo esc_html( $keahlian ); ?></p><?php endif; ?>
                        <?php if ( $nip ) : ?><p class="campusos-w-body"><?php echo ( $settings['source'] === 'pimpinan' ) ? 'NIP: ' : 'NIDN: '; ?><?php echo esc_html( $nip ); ?></p><?php endif; ?>
                        <?php if ( $email ) : ?><p class="campusos-w-body" style="color:var(--campusos-primary, #003d82);font-size:0.8rem;"><?php echo esc_html( $email ); ?></p><?php endif; ?>
                    </div>
                </div>
                </a>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p><?php esc_html_e( 'No team members found.', 'campusos-academic' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
