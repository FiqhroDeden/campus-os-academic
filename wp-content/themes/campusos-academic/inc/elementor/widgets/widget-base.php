<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Base class for CampusOS Elementor widgets.
 * Provides reusable content & style tab helpers.
 */
abstract class CampusOS_Widget_Base extends \Elementor\Widget_Base {

    public function get_categories() {
        return [ 'campusos-academic' ];
    }

    // ─── Content Tab Helpers ───

    protected function register_content_count_control( $default = 6, $min = 1, $max = 24 ) {
        $this->add_control( 'count', [
            'label'   => __( 'Jumlah Item', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => $default,
            'min'     => $min,
            'max'     => $max,
        ] );
    }

    protected function register_columns_control( $default = 3, $options = [ '2' => '2', '3' => '3', '4' => '4' ] ) {
        $this->add_control( 'columns', [
            'label'   => __( 'Kolom', 'campusos-academic' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => (string) $default,
            'options' => $options,
        ] );
    }

    protected function register_taxonomy_filter_control( $taxonomy_slug, $label ) {
        $this->add_control( 'taxonomy_filter', [
            'label'       => $label,
            'type'        => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Filter berdasarkan slug taxonomy. Kosongkan untuk semua.', 'campusos-academic' ),
        ] );
    }

    protected function register_meta_filter_control( $field_key, $label, $options ) {
        $this->add_control( 'meta_filter', [
            'label'   => $label,
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'all',
            'options' => array_merge( [ 'all' => __( 'Semua', 'campusos-academic' ) ], $options ),
        ] );
    }

    // ─── Style Tab Helpers ───

    protected function register_style_card_section() {
        $this->start_controls_section( 'style_card', [
            'label' => __( 'Card', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'card_bg_color', [
            'label'     => __( 'Background Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .campusos-w-card' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'card_border_radius', [
            'label'      => __( 'Border Radius', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'default'    => [ 'top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .campusos-w-card',
        ] );

        $this->add_responsive_control( 'card_padding', [
            'label'      => __( 'Padding', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function register_style_typography_section() {
        $this->start_controls_section( 'style_typography', [
            'label' => __( 'Typography', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'heading_title_typo', [
            'label' => __( 'Title', 'campusos-academic' ),
            'type'  => \Elementor\Controls_Manager::HEADING,
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .campusos-w-title',
        ] );

        $this->add_control( 'title_color', [
            'label'     => __( 'Title Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .campusos-w-title, {{WRAPPER}} .campusos-w-title a' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'heading_body_typo', [
            'label'     => __( 'Body Text', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
        ] );

        $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [
            'name'     => 'body_typography',
            'selector' => '{{WRAPPER}} .campusos-w-body',
        ] );

        $this->add_control( 'body_color', [
            'label'     => __( 'Body Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .campusos-w-body' => 'color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function register_style_spacing_section() {
        $this->start_controls_section( 'style_spacing', [
            'label' => __( 'Spacing', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'grid_gap', [
            'label'      => __( 'Gap', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
            'default'    => [ 'size' => 24, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-grid' => 'gap: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .campusos-w-list' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function register_style_image_section() {
        $this->start_controls_section( 'style_image', [
            'label' => __( 'Image', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'image_border_radius', [
            'label'      => __( 'Border Radius', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control( 'image_height', [
            'label'      => __( 'Height', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 100, 'max' => 500 ] ],
            'default'    => [ 'size' => 200, 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function register_style_badge_section() {
        $this->start_controls_section( 'style_badge', [
            'label' => __( 'Badge', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'badge_bg_color', [
            'label'     => __( 'Background Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .campusos-w-badge' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'badge_text_color', [
            'label'     => __( 'Text Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .campusos-w-badge' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'badge_border_radius', [
            'label'      => __( 'Border Radius', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'default'    => [ 'top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    protected function register_style_button_section() {
        $this->start_controls_section( 'style_button', [
            'label' => __( 'Button', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'button_bg_color', [
            'label'     => __( 'Background Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .campusos-w-btn' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_text_color', [
            'label'     => __( 'Text Color', 'campusos-academic' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .campusos-w-btn' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_control( 'button_border_radius', [
            'label'      => __( 'Border Radius', 'campusos-academic' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'selectors'  => [
                '{{WRAPPER}} .campusos-w-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );

        $this->end_controls_section();
    }

    // ─── Render Helpers ───

    protected function get_scoped_id( $prefix ) {
        return $prefix . '-' . $this->get_id();
    }

    protected function get_description( $post_id, $meta_key, $word_count = 20 ) {
        $desc = get_post_meta( $post_id, $meta_key, true );
        if ( empty( $desc ) ) {
            $desc = get_post_field( 'post_content', $post_id );
        }
        return $desc ? wp_trim_words( wp_strip_all_tags( $desc ), $word_count ) : '';
    }

    protected function render_empty_state( $message ) {
        echo '<div class="campusos-w-empty" style="padding:40px;text-align:center;color:#999;background:#f9f9f9;border-radius:8px;">';
        echo '<p>' . esc_html( $message ) . '</p>';
        echo '</div>';
    }

    protected function render_responsive_grid_css( $id, $cols ) {
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?>.campusos-w-grid {
                display: grid;
                grid-template-columns: repeat(<?php echo (int) $cols; ?>, 1fr);
            }
            @media (max-width: 1024px) {
                #<?php echo esc_attr( $id ); ?>.campusos-w-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 640px) {
                #<?php echo esc_attr( $id ); ?>.campusos-w-grid { grid-template-columns: 1fr; }
            }
        </style>
        <?php
    }

    protected function render_base_card_css( $id ) {
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> .campusos-w-card {
                background: #fff; border-radius: 8px; overflow: hidden;
                box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: transform .3s, box-shadow .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card:hover {
                transform: translateY(-4px); box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-image img { width: 100%; height: 200px; object-fit: cover; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card-content { padding: 16px; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-title { margin: 0 0 6px; font-size: 1rem; font-weight: 600; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-title a { color: inherit; text-decoration: none; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-title a:hover { color: var(--campusos-primary, #003d82); }
            #<?php echo esc_attr( $id ); ?> .campusos-w-body { font-size: 0.875rem; color: #666; margin: 0 0 4px; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-badge {
                display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 0.75rem;
                background: var(--campusos-secondary, #e67e22); color: #fff; font-weight: 600;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-btn {
                display: inline-block; padding: 8px 20px; border-radius: 6px; font-size: 0.875rem;
                background: var(--campusos-primary, #003d82); color: #fff; text-decoration: none; font-weight: 500;
                transition: opacity .3s;
            }
            #<?php echo esc_attr( $id ); ?> .campusos-w-btn:hover { opacity: 0.85; }
        </style>
        <?php
    }
}
