<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_News_Grid extends \Elementor\Widget_Base {

    public function get_name() { return 'campusos_news_grid'; }
    public function get_title() { return __( 'News Grid', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-posts-grid'; }
    public function get_categories() { return [ 'campusos-academic' ]; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
            'min'     => 1,
            'max'     => 24,
        ] );

        $this->add_control( 'columns', [
            'label'   => __( 'Columns', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => '3',
            'options' => [ '2' => '2', '3' => '3' ],
        ] );

        $this->add_control( 'category', [
            'label'       => __( 'Category Slug', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter by category slug. Leave empty for all.', 'campusos-academic' ),
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cols     = intval( $settings['columns'] );
        $id       = 'campusos-news-' . $this->get_id();

        $args = [
            'post_type'      => 'post',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
        ];
        if ( ! empty( $settings['category'] ) ) {
            $args['category_name'] = sanitize_text_field( $settings['category'] );
        }

        $query = new \WP_Query( $args );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> {
                display: grid; grid-template-columns: repeat(<?php echo $cols; ?>, 1fr);
                gap: 30px; padding: 20px 0;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-news-card {
                background: #fff; border-radius: 8px; overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-news-card:hover { transform: translateY(-5px); }
            #<?php echo esc_attr( $id ); ?> .campusos-news-card img { width: 100%; height: 200px; object-fit: cover; }
            #<?php echo esc_attr( $id ); ?> .campusos-news-body { padding: 20px; }
            #<?php echo esc_attr( $id ); ?> .campusos-news-date { font-size: 0.8rem; color: #999; margin-bottom: 8px; }
            #<?php echo esc_attr( $id ); ?> .campusos-news-body h4 { margin: 0 0 10px; font-size: 1.1rem; }
            #<?php echo esc_attr( $id ); ?> .campusos-news-body h4 a { color: #222; text-decoration: none; }
            #<?php echo esc_attr( $id ); ?> .campusos-news-body h4 a:hover { color: var(--campusos-primary, #003d82); }
            #<?php echo esc_attr( $id ); ?> .campusos-news-excerpt { font-size: 0.9rem; color: #555; line-height: 1.5; }
            @media (max-width: 768px) {
                #<?php echo esc_attr( $id ); ?> { grid-template-columns: 1fr; }
            }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="campusos-news-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'campusos-card' ); ?></a>
                    <?php endif; ?>
                    <div class="campusos-news-body">
                        <div class="campusos-news-date"><?php echo get_the_date( 'j F Y' ); ?></div>
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <div class="campusos-news-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p><?php esc_html_e( 'No posts found.', 'campusos-academic' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
