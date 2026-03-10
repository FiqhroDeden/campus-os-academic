<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Sambutan_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_sambutan'; }
    public function get_title() { return __( 'Sambutan Kaprodi', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-blockquote'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'style', [
            'label'   => __( 'Style', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'full',
            'options' => [
                'full'    => 'Full',
                'compact' => 'Compact',
            ],
        ] );

        $this->add_control( 'show_button', [
            'label'        => __( 'Tampilkan Tombol', 'campusos-academic' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Ya', 'campusos-academic' ),
            'label_off'    => __( 'Tidak', 'campusos-academic' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'button_text', [
            'label'     => __( 'Teks Tombol', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::TEXT,
            'default'   => 'Selengkapnya',
            'condition' => [ 'show_button' => 'yes' ],
        ] );

        $this->add_control( 'button_url', [
            'label'       => __( 'URL Tombol', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::URL,
            'placeholder' => 'https://',
            'condition'   => [ 'show_button' => 'yes' ],
        ] );

        $this->add_control( 'excerpt_length', [
            'label'   => __( 'Panjang Kutipan (kata)', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 50,
            'min'     => 10,
            'max'     => 300,
        ] );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_button_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-sambutan' );
        $style    = $settings['style'];

        $pimpinan = get_option( 'campusos_pimpinan_settings', [] );

        if ( empty( $pimpinan ) || empty( $pimpinan['nama'] ) ) {
            $this->render_empty_state( __( 'Data pimpinan belum dikonfigurasi.', 'campusos-academic' ) );
            return;
        }

        // Build name
        $nama_parts = array_filter( [
            isset( $pimpinan['gelar_depan'] ) ? $pimpinan['gelar_depan'] : '',
            isset( $pimpinan['nama'] ) ? $pimpinan['nama'] : '',
            isset( $pimpinan['gelar_belakang'] ) ? $pimpinan['gelar_belakang'] : '',
        ] );
        $nama = implode( ' ', $nama_parts );

        // Get foto
        $foto_id  = isset( $pimpinan['foto_id'] ) ? $pimpinan['foto_id'] : '';
        $foto_url = $foto_id ? wp_get_attachment_image_url( $foto_id, 'medium_large' ) : '';

        // Get jabatan
        $jabatan = isset( $pimpinan['jabatan'] ) ? $pimpinan['jabatan'] : '';

        // Get sambutan text
        $sambutan_raw = isset( $pimpinan['sambutan'] ) ? $pimpinan['sambutan'] : '';
        $excerpt_length = intval( $settings['excerpt_length'] );
        $sambutan = $sambutan_raw ? wp_trim_words( wp_strip_all_tags( $sambutan_raw ), $excerpt_length ) : '';

        // Button
        $show_button = $settings['show_button'] === 'yes';
        $button_text = $settings['button_text'];
        $button_url  = ! empty( $settings['button_url']['url'] ) ? $settings['button_url']['url'] : '';
        $btn_target  = ! empty( $settings['button_url']['is_external'] ) ? ' target="_blank"' : '';
        $btn_nofollow = ! empty( $settings['button_url']['nofollow'] ) ? ' rel="nofollow"' : '';

        $this->render_base_card_css( $id );
        ?>
        <style>
            <?php if ( $style === 'full' ) : ?>
                #<?php echo esc_attr( $id ); ?> .campusos-sambutan-inner {
                    display: grid; grid-template-columns: 280px 1fr; gap: 32px; align-items: start;
                }
                @media (max-width: 768px) {
                    #<?php echo esc_attr( $id ); ?> .campusos-sambutan-inner {
                        grid-template-columns: 1fr; text-align: center;
                    }
                }
                #<?php echo esc_attr( $id ); ?> .campusos-sambutan-photo img {
                    width: 100%; border-radius: 8px; object-fit: cover;
                }
                #<?php echo esc_attr( $id ); ?> .campusos-sambutan-section-title {
                    font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;
                    color: var(--campusos-primary, #003d82); font-weight: 600; margin: 0 0 8px;
                }
                #<?php echo esc_attr( $id ); ?> .campusos-sambutan-text {
                    font-size: 0.95rem; color: #555; line-height: 1.7; margin: 12px 0 0;
                }
            <?php endif; ?>

            #<?php echo esc_attr( $id ); ?> .campusos-w-title { font-size: 1.15rem; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-body { margin: 0; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-btn { margin-top: 16px; }
        </style>

        <div id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $style === 'full' ) : ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-card-content">
                        <div class="campusos-sambutan-inner">
                            <?php if ( $foto_url ) : ?>
                                <div class="campusos-sambutan-photo campusos-w-image">
                                    <img src="<?php echo esc_url( $foto_url ); ?>" alt="<?php echo esc_attr( $nama ); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="campusos-sambutan-content">
                                <div class="campusos-sambutan-section-title">
                                    <?php esc_html_e( 'Sambutan Ketua Program Studi', 'campusos-academic' ); ?>
                                </div>
                                <h3 class="campusos-w-title"><?php echo esc_html( $nama ); ?></h3>
                                <?php if ( $jabatan ) : ?>
                                    <p class="campusos-w-body"><?php echo esc_html( $jabatan ); ?></p>
                                <?php endif; ?>
                                <?php if ( $sambutan ) : ?>
                                    <div class="campusos-sambutan-text"><?php echo esc_html( $sambutan ); ?></div>
                                <?php endif; ?>
                                <?php if ( $show_button && $button_url ) : ?>
                                    <a href="<?php echo esc_url( $button_url ); ?>" class="campusos-w-btn"<?php echo $btn_target . $btn_nofollow; ?>>
                                        <?php echo esc_html( $button_text ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-card-content">
                        <h3 class="campusos-w-title"><?php echo esc_html( $nama ); ?></h3>
                        <?php if ( $jabatan ) : ?>
                            <p class="campusos-w-body"><?php echo esc_html( $jabatan ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
