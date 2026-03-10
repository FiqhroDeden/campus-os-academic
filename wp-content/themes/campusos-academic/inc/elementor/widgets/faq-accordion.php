<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_FAQ_Accordion extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_faq_accordion'; }
    public function get_title() { return __( 'FAQ Accordion', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-accordion'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'count', [
            'label'   => __( 'Count', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
            'min'     => 1,
            'max'     => 50,
        ] );

        $this->add_control( 'kategori', [
            'label'       => __( 'Category Slug', 'campusos-academic' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter by FAQ taxonomy slug. Leave empty for all.', 'campusos-academic' ),
        ] );

        $this->end_controls_section();

        // Style Tabs
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = 'campusos-faq-' . $this->get_id();

        $args = [
            'post_type'      => 'faq',
            'posts_per_page' => intval( $settings['count'] ),
            'post_status'    => 'publish',
            'meta_key'       => '_urutan_faq',
            'orderby'        => 'meta_value_num',
            'order'          => 'ASC',
        ];
        if ( ! empty( $settings['kategori'] ) ) {
            $args['tax_query'] = [ [
                'taxonomy' => 'kategori_faq',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $settings['kategori'] ),
            ] ];
        }

        $query = new \WP_Query( $args );
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> details {
                border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 10px;
                overflow: hidden; transition: all .3s;
            }
            #<?php echo esc_attr( $id ); ?> details[open] { border-color: var(--campusos-primary, #003d82); }
            #<?php echo esc_attr( $id ); ?> summary {
                padding: 15px 20px; cursor: pointer; font-weight: 600; font-size: 1rem;
                background: #f9f9f9; list-style: none; display: flex; justify-content: space-between; align-items: center;
            }
            #<?php echo esc_attr( $id ); ?> summary::-webkit-details-marker { display: none; }
            #<?php echo esc_attr( $id ); ?> summary::after { content: '+'; font-size: 1.3rem; font-weight: 300; color: #888; }
            #<?php echo esc_attr( $id ); ?> details[open] summary::after { content: '−'; }
            #<?php echo esc_attr( $id ); ?> details[open] summary { background: var(--campusos-primary, #003d82); color: #fff; }
            #<?php echo esc_attr( $id ); ?> details[open] summary::after { color: #fff; }
            #<?php echo esc_attr( $id ); ?> .campusos-faq-answer { padding: 20px; font-size: 0.95rem; line-height: 1.7; color: #444; }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>">
            <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                <details>
                    <summary><?php the_title(); ?></summary>
                    <?php
                    $jawaban = get_post_meta( get_the_ID(), '_faq_jawaban_faq', true );
                    if ( empty( $jawaban ) ) {
                        $jawaban = get_post_field( 'post_content', get_the_ID() );
                    }
                    ?>
                    <div class="campusos-faq-answer"><?php echo wp_kses_post( wpautop( $jawaban ) ); ?></div>
                </details>
            <?php endwhile; wp_reset_postdata(); else : ?>
                <p><?php esc_html_e( 'No FAQs found.', 'campusos-academic' ); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}
