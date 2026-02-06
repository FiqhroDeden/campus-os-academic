<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Gallery_Grid extends \Elementor\Widget_Base {

    public function get_name() { return 'campusos_gallery_grid'; }
    public function get_title() { return __( 'Gallery Grid', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-gallery-masonry'; }
    public function get_categories() { return [ 'campusos-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 12,
            'min'     => 1,
            'max'     => 48,
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '4',
            'options' => [ '3' => '3', '4' => '4' ],
        ] );

        $this->add_control( 'kategori', [
            'label'       => __( 'Category Slug', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter by gallery taxonomy slug. Leave empty for all.', 'campusos-academic' ),
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cols     = intval( $settings['columns'] );
        $id       = 'campusos-gallery-' . $this->get_id();

        $args = [
            'post_type'      => 'galeri',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
        ];
        if ( ! empty( $settings['kategori'] ) ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'kategori_galeri',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $settings['kategori'] ),
            ] ];
        }

        $query = new \WP_Query( $args );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: grid; grid-template-columns: repeat(<?php echo $cols; ?>, 1fr);
                gap: 10px; padding: 20px 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-gallery-item {
                position: relative; overflow: hidden; border-radius: 6px;
                aspect-ratio: 1; cursor: pointer;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-gallery-item img {
                width: 100%; height: 100%; object-fit: cover; transition: transform .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-gallery-item:hover img { transform: scale(1.08); }
            #<?php echo esc_attr( $id ); ?> .campusos-gallery-overlay {
                position: absolute; inset: 0; background: rgba(0,61,130,0.6);
                display: flex; align-items: center; justify-content: center;
                opacity: 0; transition: opacity .3s; color: #fff; font-weight: 600; padding: 10px; text-align: center;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-gallery-item:hover .campusos-gallery-overlay { opacity: 1; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> { grid-template-columns: repeat(2, 1fr); }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="campusos-gallery-item">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'campusos-card' ); ?>
                    <?php endif; ?>
                    <div class="campusos-gallery-overlay">
                        <span><?php the_title(); ?></span>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p><?php esc_html_e( 'No gallery items found.', 'campusos-academic' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
