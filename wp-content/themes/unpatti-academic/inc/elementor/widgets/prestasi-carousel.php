<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class UNPATTI_Prestasi_Carousel extends \Elementor\Widget_Base {

    public function get_name() { return 'unpatti_prestasi_carousel'; }
    public function get_title() { return __( 'Prestasi Carousel', 'unpatti-academic' ); }
    public function get_icon() { return 'eicon-slides'; }
    public function get_categories() { return [ 'unpatti-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'unpatti-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
            'max'     => 20,
        ] );

        $this->add_control( 'kategori', [
            'label'   => __( 'Category', 'unpatti-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'all',
            'options' => [
                'all'       => __( 'All', 'unpatti-academic' ),
                'mahasiswa' => __( 'Mahasiswa', 'unpatti-academic' ),
                'dosen'     => __( 'Dosen', 'unpatti-academic' ),
            ],
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = 'unpatti-prestasi-' . $this->get_id();

        $args = [
            'post_type'      => 'prestasi',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
        ];
        if ( $settings['kategori'] !== 'all' ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'kategori_prestasi',
                'field'    => 'slug',
                'terms'    => $settings['kategori'],
            ] ];
        }

        $query = new \WP_Query( $args );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: flex; gap: 20px; overflow-x: auto; scroll-snap-type: x mandatory;
                padding: 20px 0; -webkit-overflow-scrolling: touch;
            }
            #<?php echo esc_attr( $id ); ?>::-webkit-scrollbar { height: 6px; }
            #<?php echo esc_attr( $id ); ?>::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-card {
                flex: 0 0 300px; scroll-snap-align: start;
                background: #fff; border-radius: 8px; overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-card img {
                width: 100%; height: 200px; object-fit: cover;
            }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-body { padding: 15px; }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-body h4 { margin: 0 0 8px; font-size: 1rem; }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-body h4 a { color: #222; text-decoration: none; }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-body h4 a:hover { color: var(--unpatti-primary, #003d82); }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-meta { font-size: 0.85rem; color: #666; margin-bottom: 5px; }
            #<?php echo esc_attr( $id ); ?> .unpatti-prestasi-badge {
                display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem;
                background: var(--unpatti-secondary, #e67e22); color: #fff; font-weight: 600;
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
                $nama_peraih = get_post_meta( get_the_ID(), '_nama_peraih', true );
                $tingkat     = get_post_meta( get_the_ID(), '_tingkat_prestasi', true );
            ?>
                <div class="unpatti-prestasi-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'unpatti-card' ); ?></a>
                    <?php endif; ?>
                    <div class="unpatti-prestasi-body">
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <?php if ( $nama_peraih ) : ?>
                            <div class="unpatti-prestasi-meta"><?php echo esc_html( $nama_peraih ); ?></div>
                        <?php endif; ?>
                        <?php if ( $tingkat ) : ?>
                            <span class="unpatti-prestasi-badge"><?php echo esc_html( $tingkat ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p><?php esc_html_e( 'No achievements found.', 'unpatti-academic' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
