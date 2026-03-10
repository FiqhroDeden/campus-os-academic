<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Mitra_Industri_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_mitra_industri'; }
    public function get_title() { return __( 'Mitra Industri', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-gallery-grid'; }

    protected function register_controls() {
        // Content
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->register_content_count_control( 12 );
        $this->register_columns_control( 5, [ '3' => '3', '4' => '4', '5' => '5', '6' => '6' ] );

        $this->end_controls_section();

        // Style
        $this->register_style_image_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_scoped_id( 'campusos-mitra' );
        $cols     = intval( $settings['columns'] );

        $args = [
            'post_type'      => 'mitra_industri',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];

        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data mitra industri.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> .campusos-w-card {
                background: transparent; border-radius: 8px; overflow: hidden;
                box-shadow: none; text-align: center; padding: 16px;
                display: flex; flex-direction: column; align-items: center; justify-content: center;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-image img {
                max-height: 80px; width: auto; max-width: 100%; object-fit: contain;
                filter: grayscale(100%); opacity: 0.7;
                transition: filter .3s, opacity .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card:hover .campusos-w-image img {
                filter: grayscale(0%); opacity: 1;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-title {
                margin: 10px 0 0; font-size: 0.8rem; font-weight: 500; color: #666;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid     = get_the_ID();
                $logo_id = get_post_meta( $pid, '_mitra_industri_logo_mitra_di', true );
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-image">
                        <?php if ( $logo_id ) : ?>
                            <?php echo wp_get_attachment_image( $logo_id, 'medium', false, [ 'alt' => get_the_title() ] ); ?>
                        <?php elseif ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'medium' ); ?>
                        <?php endif; ?>
                    </div>
                    <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
