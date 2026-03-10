<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Tenaga_Pendidik_Carousel extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_tenaga_pendidik_carousel'; }
    public function get_title() { return __( 'Tenaga Pendidik Carousel', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-slides'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Jumlah', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 20,
            'min'     => 1,
            'max'     => 50,
        ] );

        $this->add_control( 'auto_scroll', [
            'label'   => __( 'Auto Scroll', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ] );

        $this->add_control( 'scroll_speed', [
            'label'     => __( 'Kecepatan Scroll (ms)', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::NUMBER,
            'default'   => 3000,
            'min'       => 1000,
            'max'       => 10000,
            'step'      => 500,
            'condition' => [ 'auto_scroll' => 'yes' ],
        ] );

        $this->end_controls_section();

        // Style Tabs
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings     = $this->get_settings_for_display();
        $id           = 'campusos-tdp-carousel-' . $this->get_id();
        $auto_scroll  = $settings['auto_scroll'] === 'yes';
        $scroll_speed = intval( $settings['scroll_speed'] );

        $jabatan_labels = [
            'guru_besar'    => 'Guru Besar',
            'lektor_kepala' => 'Lektor Kepala',
            'lektor'        => 'Lektor',
            'asisten_ahli'  => 'Asisten Ahli',
        ];

        $query = new \WP_Query( [
            'post_type'      => 'tenaga_pendidik',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
        ] );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?>-section {
                position: relative;
                overflow: hidden;
                background: linear-gradient(160deg, #002855 0%, #003d82 50%, #001a3d 100%);
                padding: 3.5rem 0;
                border-radius: 12px;
            }
            #<?php echo esc_attr( $id ); ?>-section::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(230, 126, 34, 0.08) 0%, transparent 70%);
                pointer-events: none;
            }
            #<?php echo esc_attr( $id ); ?>-section .tdp-section-header {
                text-align: center;
                margin-bottom: 2rem;
                position: relative;
                z-index: 1;
            }
            #<?php echo esc_attr( $id ); ?>-section .tdp-section-header .tdp-decorator {
                width: 40px;
                height: 4px;
                background: #e67e22;
                margin: 0 auto 0.75rem;
                border-radius: 2px;
            }
            #<?php echo esc_attr( $id ); ?>-section .tdp-section-header h2 {
                color: #fff;
                font-size: 1.5rem;
                letter-spacing: 2px;
                margin: 0;
            }
            #<?php echo esc_attr( $id ); ?>-wrapper {
                position: relative;
                padding: 0 2rem;
            }
            #<?php echo esc_attr( $id ); ?> {
                display: flex;
                gap: 1.25rem;
                overflow-x: auto;
                scroll-behavior: smooth;
                scroll-snap-type: x mandatory;
                scrollbar-width: none;
                -ms-overflow-style: none;
                -webkit-overflow-scrolling: touch;
                padding: 1rem 0;
            }
            #<?php echo esc_attr( $id ); ?>::-webkit-scrollbar { display: none; }
            #<?php echo esc_attr( $id ); ?> .tdp-card {
                flex: 0 0 180px;
                scroll-snap-align: start;
                background: #fff;
                border-radius: 10px;
                overflow: hidden;
                text-align: center;
                text-decoration: none;
                color: inherit;
                display: block;
                box-shadow: 0 4px 16px rgba(0,0,0,0.15);
                transition: transform 0.3s, box-shadow 0.3s;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.25);
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-photo {
                width: 100%;
                height: 200px;
                overflow: hidden;
                background: #e9ecef;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-photo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card:hover .tdp-card-photo img {
                transform: scale(1.05);
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-placeholder {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f0f2f5;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-placeholder svg {
                width: 48px;
                height: 48px;
                color: #bbb;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-info {
                padding: 12px 10px;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-name {
                font-size: 0.85rem;
                font-weight: 700;
                color: #222;
                margin: 0 0 4px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                line-height: 1.3;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-jabatan {
                font-size: 0.75rem;
                color: #666;
                margin: 0 0 8px;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-email {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: rgba(0,40,85,0.08);
                color: #003d82;
                transition: background 0.2s, color 0.2s;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-email:hover {
                background: #003d82;
                color: #fff;
            }
            #<?php echo esc_attr( $id ); ?> .tdp-card-email svg {
                width: 14px;
                height: 14px;
            }
            #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                width: 40px;
                height: 40px;
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: 50%;
                background: rgba(255,255,255,0.1);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                color: #fff;
                cursor: pointer;
                z-index: 2;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background 0.2s, transform 0.2s, border-color 0.2s;
            }
            #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-btn:hover {
                background: rgba(255,255,255,0.2);
                border-color: rgba(255,255,255,0.4);
                transform: translateY(-50%) scale(1.1);
            }
            #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-btn svg {
                width: 18px;
                height: 18px;
            }
            #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-prev { left: -8px; }
            #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-next { right: -8px; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> .tdp-card { flex: 0 0 150px; }
                #<?php echo esc_attr( $id ); ?> .tdp-card-photo { height: 170px; }
                #<?php echo esc_attr( $id ); ?>-wrapper { padding: 0 1.5rem; }
                #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-prev { left: -4px; }
                #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-next { right: -4px; }
                #<?php echo esc_attr( $id ); ?>-wrapper .tdp-nav-btn { width: 34px; height: 34px; }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>-section">
            <div class="tdp-section-header">
                <div class="tdp-decorator"></div>
                <h2><?php esc_html_e( 'TENAGA PENDIDIK', 'campusos-academic' ); ?></h2>
            </div>
            <div id="<?php echo esc_attr( $id ); ?>-wrapper">
                <div id="<?php echo esc_attr( $id ); ?>">
                    <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                        $jabatan = get_post_meta( get_the_ID(), '_tenaga_pendidik_jabatan_fungsional', true );
                        $email   = get_post_meta( get_the_ID(), '_tenaga_pendidik_email_dosen', true );
                        $jabatan_text = isset( $jabatan_labels[ $jabatan ] ) ? $jabatan_labels[ $jabatan ] : $jabatan;
                    ?>
                        <a href="<?php the_permalink(); ?>" class="tdp-card campusos-w-card">
                            <div class="tdp-card-photo campusos-w-image">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
                                <?php else : ?>
                                    <div class="tdp-card-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="tdp-card-info campusos-w-card-content">
                                <p class="tdp-card-name campusos-w-title" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></p>
                                <?php if ( $jabatan_text ) : ?>
                                    <p class="tdp-card-jabatan campusos-w-body"><?php echo esc_html( $jabatan_text ); ?></p>
                                <?php endif; ?>
                                <?php if ( $email ) : ?>
                                    <span class="tdp-card-email" title="<?php echo esc_attr( $email ); ?>" onclick="event.preventDefault(); window.location='mailto:<?php echo esc_attr( $email ); ?>';">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); else : ?>
                        <p style="color:#fff;"><?php esc_html_e( 'Belum ada data tenaga pendidik.', 'campusos-academic' ); ?></p>
                    <?php endif; ?>
                </div>
                <button class="tdp-nav-btn tdp-nav-prev" aria-label="<?php esc_attr_e( 'Sebelumnya', 'campusos-academic' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button class="tdp-nav-btn tdp-nav-next" aria-label="<?php esc_attr_e( 'Selanjutnya', 'campusos-academic' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>
        </div>
        <script>
        (function(){
            var wrapper = document.getElementById('<?php echo esc_js( $id ); ?>-wrapper');
            if (!wrapper) return;
            var track = document.getElementById('<?php echo esc_js( $id ); ?>');
            var prevBtn = wrapper.querySelector('.tdp-nav-prev');
            var nextBtn = wrapper.querySelector('.tdp-nav-next');
            var card = track.querySelector('.tdp-card');
            if (!card) return;
            var scrollAmount = card.offsetWidth + 20;

            prevBtn.addEventListener('click', function(){ track.scrollBy({ left: -scrollAmount, behavior: 'smooth' }); });
            nextBtn.addEventListener('click', function(){ track.scrollBy({ left: scrollAmount, behavior: 'smooth' }); });

            <?php if ( $auto_scroll ) : ?>
            var interval = null;
            function startAutoScroll() {
                interval = setInterval(function(){
                    if (track.scrollLeft + track.offsetWidth >= track.scrollWidth - 10) {
                        track.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                    }
                }, <?php echo $scroll_speed; ?>);
            }
            startAutoScroll();
            track.addEventListener('mouseenter', function(){ clearInterval(interval); });
            track.addEventListener('mouseleave', function(){ startAutoScroll(); });
            <?php endif; ?>
        })();
        </script>
        <?php
    }
}
