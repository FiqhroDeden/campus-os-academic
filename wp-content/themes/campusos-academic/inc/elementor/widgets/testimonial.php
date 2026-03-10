<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Testimonial_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_testimonial'; }
    public function get_title() { return __( 'Testimonial', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-testimonial'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 6 );
        $this->register_columns_control( 3 );

        $this->add_control( 'layout', [
            'label'   => __( 'Layout', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid'     => 'Grid',
                'carousel' => 'Carousel',
            ],
        ] );

        $this->end_controls_section();

        // Style
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-testimonial' );
        $cols     = intval( $settings['columns'] );
        $layout   = $settings['layout'];

        $args = [
            'post_type'      => 'testimonial',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada testimonial.', 'campusos-academic' ) );
            return;
        }

        if ( $layout === 'grid' ) {
            $this->render_responsive_grid_css( $id, $cols );
        }

        $this->render_base_card_css( $id );
        ?>
        <style>
            <?php if ( $layout === 'carousel' ) : ?>
                #<?php echo esc_attr( $id ); ?> {
                    display: flex; gap: 20px; overflow-x: auto; scroll-snap-type: x mandatory;
                    padding: 20px 0; -webkit-overflow-scrolling: touch;
                }
                #<?php echo esc_attr( $id ); ?>::-webkit-scrollbar { height: 6px; }
                #<?php echo esc_attr( $id ); ?>::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
                #<?php echo esc_attr( $id ); ?> .campusos-w-card {
                    flex: 0 0 320px; scroll-snap-align: start;
                }
            <?php endif; ?>
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-quote {
                font-size: 0.9rem; color: #555; line-height: 1.6; font-style: italic;
                margin-bottom: 16px; position: relative; padding-left: 20px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-quote::before {
                content: '\201C'; position: absolute; left: 0; top: -4px;
                font-size: 2rem; color: var(--campusos-primary, #003d82); font-style: normal;
                line-height: 1;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-author {
                display: flex; align-items: center; gap: 12px;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-avatar {
                width: 48px; height: 48px; border-radius: 50%; overflow: hidden; flex-shrink: 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-avatar img {
                width: 100%; height: 100%; object-fit: cover;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-info .campusos-w-title {
                font-size: 0.9rem; margin: 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-testimonial-info .campusos-w-body {
                font-size: 0.8rem; margin: 2px 0 0;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="<?php echo $layout === 'grid' ? 'campusos-w-grid' : ''; ?>">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid      = get_the_ID();
                $jabatan  = get_post_meta( $pid, '_testimonial_jabatan', true );
                $instansi = get_post_meta( $pid, '_testimonial_instansi', true );
                $teks     = get_post_meta( $pid, '_testimonial_teks', true );

                if ( empty( $teks ) ) {
                    $teks = get_post_field( 'post_content', $pid );
                }
                $teks = $teks ? wp_trim_words( wp_strip_all_tags( $teks ), 40 ) : '';

                $subtitle_parts = array_filter( [ $jabatan, $instansi ] );
                $subtitle       = implode( ' - ', $subtitle_parts );
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-card-content">
                        <?php if ( $teks ) : ?>
                            <div class="campusos-testimonial-quote"><?php echo esc_html( $teks ); ?></div>
                        <?php endif; ?>
                        <div class="campusos-testimonial-author">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="campusos-testimonial-avatar campusos-w-image">
                                    <?php the_post_thumbnail( 'thumbnail' ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="campusos-testimonial-info">
                                <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                                <?php if ( $subtitle ) : ?>
                                    <p class="campusos-w-body"><?php echo esc_html( $subtitle ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
