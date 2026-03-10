<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Announcement_List extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_announcement_list'; }
    public function get_title() { return __( 'Announcement List', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-post-list'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 5,
            'min'     => 1,
            'max'     => 20,
        ] );

        $this->add_control( 'category_slug', [
            'label'   => __( 'Category Slug', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => 'pengumuman',
        ] );

        $this->end_controls_section();

        // Style Tabs
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_badge_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = 'campusos-announce-' . $this->get_id();

        $args = [
            'post_type'      => 'post',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
        ];
        if ( ! empty( $settings['category_slug'] ) ) {
            $args['category_name'] = sanitize_text_field( $settings['category_slug'] );
        }

        $query = new \WP_Query( $args );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> { list-style: none; margin: 0; padding: 0; }
            #<?php echo esc_attr( $id ); ?> li {
                display: flex; align-items: center; gap: 15px;
                padding: 15px 0; border-bottom: 1px solid #eee;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-announce-date {
                flex-shrink: 0; width: 60px; height: 60px;
                background: var(--campusos-primary, #003d82); color: #fff;
                display: flex; flex-direction: column; align-items: center; justify-content: center;
                border-radius: 6px; font-size: 0.75rem; line-height: 1.3;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-announce-date .day { font-size: 1.4rem; font-weight: 700; }
            #<?php echo esc_attr( $id ); ?> .campusos-announce-title a {
                color: #222; text-decoration: none; font-weight: 500; font-size: 1rem;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-announce-title a:hover { color: var(--campusos-primary, #003d82); }
        </style>
        <ul id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <li>
                    <div class="campusos-announce-date">
                        <span class="day"><?php echo get_the_date( 'j' ); ?></span>
                        <span><?php echo get_the_date( 'M Y' ); ?></span>
                    </div>
                    <div class="campusos-announce-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </div>
                </li>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <li><?php esc_html_e( 'No announcements found.', 'campusos-academic' ); ?></li>
            <?php endif; ?>
        </ul>
        <?php
    }
}
