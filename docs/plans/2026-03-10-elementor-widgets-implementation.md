# Elementor Widgets & CPT Form-Only Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Convert 11 CPTs to form-only input (remove Gutenberg editor), create a Widget Base Class, build 11 new Elementor widgets with full Style Tabs, and refactor 6 existing widgets.

**Architecture:** All CPTs extend `CPT_Base` in the plugin. All Elementor widgets will extend a new `CampusOS_Widget_Base` class in the theme. Widgets use `WP_Query` to fetch CPT data and render with scoped inline CSS. Style Tabs use Elementor's built-in typography/color Group Controls.

**Tech Stack:** PHP 8.0+, WordPress 6.9, Elementor (free), vanilla CSS, `CPT_Base` pattern, `\Elementor\Widget_Base` API.

---

## Phase 1: CPT Form-Only Conversion

### Task 1: Remove editor from 11 CPTs and add description meta fields

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-agenda.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-kerjasama.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-mata-kuliah.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-mitra-industri.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-beasiswa.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-pengumuman.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-faq.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-fasilitas.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-organisasi-mhs.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-prestasi.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-video.php`

**Step 1: Update each CPT file**

For each file, make two changes:

**a) Remove `'editor'` from supports array.** Keep `'title'` and `'thumbnail'` where they exist.

Example for agenda (`class-cpt-agenda.php`):
```php
// BEFORE:
'supports' => [ 'title', 'editor' ],
// AFTER:
'supports' => [ 'title' ],
```

Example for kerjasama (`class-cpt-kerjasama.php`):
```php
// BEFORE:
'supports' => [ 'title', 'editor', 'thumbnail' ],
// AFTER:
'supports' => [ 'title', 'thumbnail' ],
```

**b) Add description textarea field to `get_meta_fields()` return array.**

Specific fields to add per CPT (add at the END of the meta_fields array):

| CPT file | Field to add |
|----------|-------------|
| class-cpt-agenda.php | `[ 'id' => 'agenda_deskripsi_agenda', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-kerjasama.php | `[ 'id' => 'kerjasama_deskripsi_kerjasama', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-mata-kuliah.php | `[ 'id' => 'mata_kuliah_deskripsi_mk', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-mitra-industri.php | `[ 'id' => 'mitra_industri_deskripsi_mitra_di', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-beasiswa.php | `[ 'id' => 'beasiswa_deskripsi_beasiswa', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-pengumuman.php | `[ 'id' => 'pengumuman_deskripsi_pengumuman', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-faq.php | `[ 'id' => 'faq_jawaban_faq', 'label' => 'Jawaban', 'type' => 'textarea' ]` |
| class-cpt-fasilitas.php | `[ 'id' => 'fasilitas_deskripsi_fasilitas', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-organisasi-mhs.php | `[ 'id' => 'organisasi_mhs_deskripsi_org', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-prestasi.php | `[ 'id' => 'prestasi_deskripsi_prestasi', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |
| class-cpt-video.php | `[ 'id' => 'video_deskripsi_video', 'label' => 'Deskripsi', 'type' => 'textarea' ]` |

**Step 2: Verify in browser**

Visit wp-admin → each CPT → Add New. Confirm:
- No Gutenberg block editor visible
- Classic meta box form with all fields including new Deskripsi/Jawaban field
- Fields save and load correctly

**Step 3: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/cpt/
git commit -m "refactor: convert 11 CPTs to form-only input, add description meta fields"
```

---

### Task 2: Update shortcodes to use meta field descriptions with post_content fallback

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/includes/frontend/class-shortcodes.php`

**Step 1: Create a helper method in the Shortcodes class**

Add this private method at the top of the class (after `enqueue_styles`):

```php
/**
 * Get description from meta field, falling back to post_content.
 */
private function get_description( $post_id, $meta_key, $word_count = 20 ) {
    $desc = get_post_meta( $post_id, $meta_key, true );
    if ( empty( $desc ) ) {
        $desc = get_post_field( 'post_content', $post_id );
    }
    return $desc ? wp_trim_words( wp_strip_all_tags( $desc ), $word_count ) : '';
}
```

**Step 2: Update each shortcode render method**

Replace `get_the_content()` / `get_the_excerpt()` calls with `$this->get_description()`:

**agenda()** — around line where `get_the_content()` is used:
```php
// BEFORE:
if ( has_excerpt() || get_the_content() ) {
    echo '<p class="campusos-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 15 ) ) . '</p>';
}
// AFTER:
$desc = $this->get_description( $id, '_agenda_deskripsi_agenda', 15 );
if ( $desc ) {
    echo '<p class="campusos-excerpt">' . esc_html( $desc ) . '</p>';
}
```

**pengumuman()** — same pattern:
```php
// BEFORE:
if ( has_excerpt() || get_the_content() ) {
    echo '<p class="campusos-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ) . '</p>';
}
// AFTER:
$desc = $this->get_description( $id, '_pengumuman_deskripsi_pengumuman', 20 );
if ( $desc ) {
    echo '<p class="campusos-excerpt">' . esc_html( $desc ) . '</p>';
}
```

**faq()** — replace `get_the_content()` with meta field:
```php
// BEFORE:
echo '<div class="campusos-faq-content">' . wp_kses_post( get_the_content() ) . '</div>';
// AFTER:
$jawaban = get_post_meta( get_the_ID(), '_faq_jawaban_faq', true );
if ( empty( $jawaban ) ) {
    $jawaban = get_post_field( 'post_content', get_the_ID() );
}
echo '<div class="campusos-faq-content">' . wp_kses_post( wpautop( $jawaban ) ) . '</div>';
```

**fasilitas()** — same pattern:
```php
// BEFORE:
if ( has_excerpt() || get_the_content() ) {
    echo '<p class="campusos-fasilitas-desc">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 15 ) ) . '</p>';
}
// AFTER:
$desc = $this->get_description( $id, '_fasilitas_deskripsi_fasilitas', 15 );
if ( $desc ) {
    echo '<p class="campusos-fasilitas-desc">' . esc_html( $desc ) . '</p>';
}
```

**beasiswa()** — same pattern:
```php
// BEFORE:
if ( has_excerpt() || get_the_content() ) {
    echo '<p class="campusos-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ) . '</p>';
}
// AFTER:
$desc = $this->get_description( $id, '_beasiswa_deskripsi_beasiswa', 20 );
if ( $desc ) {
    echo '<p class="campusos-excerpt">' . esc_html( $desc ) . '</p>';
}
```

**testimonial()** — uses `get_the_content()` for testimonial text:
```php
// BEFORE:
echo '<p class="campusos-testimonial-text">"' . esc_html( wp_trim_words( get_the_content(), 40 ) ) . '"</p>';
// AFTER:
$teks = get_post_meta( $id, '_testimonial_teks', true );
if ( empty( $teks ) ) {
    $teks = get_post_field( 'post_content', $id );
}
echo '<p class="campusos-testimonial-text">"' . esc_html( wp_trim_words( wp_strip_all_tags( $teks ), 40 ) ) . '"</p>';
```

Note: testimonial CPT already has `supports: ['title', 'thumbnail']` (no editor), but its shortcode uses `get_the_content()`. We should add a `testimonial_teks` meta field.

**organisasi_mhs()** — same pattern:
```php
// BEFORE:
if ( has_excerpt() || get_the_content() ) {
    echo '<p class="campusos-excerpt">' . esc_html( wp_trim_words( get_the_excerpt() ?: get_the_content(), 20 ) ) . '</p>';
}
// AFTER:
$desc = $this->get_description( $id, '_organisasi_mhs_deskripsi_org', 20 );
if ( $desc ) {
    echo '<p class="campusos-excerpt">' . esc_html( $desc ) . '</p>';
}
```

**Step 3: Add missing meta field to testimonial CPT**

Also modify: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-testimonial.php`

Add to `get_meta_fields()`:
```php
[ 'id' => 'testimonial_teks', 'label' => 'Testimonial', 'type' => 'textarea' ],
```

**Step 4: Verify shortcodes still render correctly in browser**

**Step 5: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/frontend/class-shortcodes.php
git add wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-testimonial.php
git commit -m "refactor: update shortcodes to read meta field descriptions with post_content fallback"
```

---

## Phase 2: Widget Base Class

### Task 3: Create CampusOS_Widget_Base

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/widget-base.php`

**Step 1: Create the widget base class**

```php
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

    /**
     * Get description from meta field, falling back to post_content.
     */
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
```

**Step 2: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/widgets/widget-base.php
git commit -m "feat: add CampusOS_Widget_Base class with content and style tab helpers"
```

---

## Phase 3: New Elementor Widgets (11 widgets)

### Task 4: Create Kerjasama Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/kerjasama.php`

**Step 1: Write the widget**

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Kerjasama_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_kerjasama'; }
    public function get_title() { return __( 'Kerjasama', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-flow'; }

    protected function register_controls() {
        // Content Tab
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->register_content_count_control( 6, 1, 24 );
        $this->register_columns_control( 3 );
        $this->register_meta_filter_control( '_kerjasama_jenis_kerjasama', __( 'Jenis Kerjasama', 'campusos-academic' ), [
            'dn' => __( 'Dalam Negeri', 'campusos-academic' ),
            'ln' => __( 'Luar Negeri', 'campusos-academic' ),
        ] );
        $this->end_controls_section();

        // Style Tabs
        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_image_section();
        $this->register_style_badge_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $s   = $this->get_settings_for_display();
        $id  = $this->get_scoped_id( 'campusos-kerjasama' );
        $cols = (int) $s['columns'];

        $args = [
            'post_type'      => 'kerjasama',
            'posts_per_page' => (int) $s['count'],
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];
        if ( ! empty( $s['meta_filter'] ) && $s['meta_filter'] !== 'all' ) {
            $args['meta_query'] = [ [
                'key'   => '_kerjasama_jenis_kerjasama',
                'value' => $s['meta_filter'],
            ] ];
        }
        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada data kerjasama.', 'campusos-academic' ) );
            return;
        }

        $this->render_responsive_grid_css( $id, $cols );
        $this->render_base_card_css( $id );

        $jenis_labels = [ 'dn' => 'Dalam Negeri', 'ln' => 'Luar Negeri' ];
        ?>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-grid">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid       = get_the_ID();
                $logo_id   = get_post_meta( $pid, '_kerjasama_logo_mitra', true );
                $logo_url  = $logo_id ? wp_get_attachment_url( (int) $logo_id ) : get_the_post_thumbnail_url( $pid, 'medium' );
                $jenis_key = get_post_meta( $pid, '_kerjasama_jenis_kerjasama', true );
                $jenis     = $jenis_labels[ $jenis_key ] ?? $jenis_key;
                $tgl_mulai = get_post_meta( $pid, '_kerjasama_tanggal_mulai', true );
                $tgl_akhir = get_post_meta( $pid, '_kerjasama_tanggal_akhir', true );
                $periode   = ( $tgl_mulai && $tgl_akhir ) ? date_i18n( 'Y', strtotime( $tgl_mulai ) ) . ' - ' . date_i18n( 'Y', strtotime( $tgl_akhir ) ) : '';
            ?>
                <div class="campusos-w-card">
                    <?php if ( $logo_url ) : ?>
                        <div class="campusos-w-image"><img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php the_title_attribute(); ?>"></div>
                    <?php endif; ?>
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <?php if ( $jenis ) : ?><p class="campusos-w-body"><span class="campusos-w-badge"><?php echo esc_html( $jenis ); ?></span></p><?php endif; ?>
                        <?php if ( $periode ) : ?><p class="campusos-w-body"><?php echo esc_html( $periode ); ?></p><?php endif; ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
```

**Step 2: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/widgets/kerjasama.php
git commit -m "feat: add Kerjasama Elementor widget with full style tabs"
```

---

### Task 5: Create Dokumen Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/dokumen.php`

**Step 1: Write the widget**

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CampusOS_Dokumen_Widget extends CampusOS_Widget_Base {

    public function get_name() { return 'campusos_dokumen'; }
    public function get_title() { return __( 'Dokumen', 'campusos-academic' ); }
    public function get_icon() { return 'eicon-document-file'; }

    protected function register_controls() {
        $this->start_controls_section( 'content_section', [
            'label' => __( 'Settings', 'campusos-academic' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->register_content_count_control( 10, 1, 50 );
        $this->register_meta_filter_control( '_dokumen_kategori_dokumen', __( 'Kategori', 'campusos-academic' ), [
            'peraturan' => 'Peraturan Akademik', 'kalender' => 'Kalender Akademik',
            'kurikulum' => 'Kurikulum', 'sop' => 'SOP', 'mutu' => 'Dokumen Mutu', 'akreditasi' => 'Akreditasi',
        ] );
        $this->add_control( 'tahun_filter', [
            'label' => __( 'Filter Tahun', 'campusos-academic' ),
            'type'  => \Elementor\Controls_Manager::TEXT,
            'description' => __( 'Isi tahun untuk filter (misal: 2024). Kosongkan untuk semua.', 'campusos-academic' ),
        ] );
        $this->end_controls_section();

        $this->register_style_card_section();
        $this->register_style_typography_section();
        $this->register_style_badge_section();
        $this->register_style_spacing_section();
    }

    protected function render() {
        $s  = $this->get_settings_for_display();
        $id = $this->get_scoped_id( 'campusos-dokumen' );

        $args = [
            'post_type'      => 'dokumen',
            'posts_per_page' => (int) $s['count'],
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];
        $meta_query = [];
        if ( ! empty( $s['meta_filter'] ) && $s['meta_filter'] !== 'all' ) {
            $meta_query[] = [ 'key' => '_dokumen_kategori_dokumen', 'value' => $s['meta_filter'] ];
        }
        if ( ! empty( $s['tahun_filter'] ) ) {
            $meta_query[] = [ 'key' => '_dokumen_tahun_dokumen', 'value' => sanitize_text_field( $s['tahun_filter'] ) ];
        }
        if ( $meta_query ) {
            $args['meta_query'] = $meta_query;
        }
        $query = new \WP_Query( $args );

        if ( ! $query->have_posts() ) {
            $this->render_empty_state( __( 'Belum ada dokumen.', 'campusos-academic' ) );
            return;
        }

        $this->render_base_card_css( $id );
        $kategori_labels = [
            'peraturan' => 'Peraturan Akademik', 'kalender' => 'Kalender Akademik',
            'kurikulum' => 'Kurikulum', 'sop' => 'SOP', 'mutu' => 'Dokumen Mutu', 'akreditasi' => 'Akreditasi',
        ];
        ?>
        <style>
            #<?php echo esc_attr( $id ); ?> .campusos-w-card { display: flex; align-items: center; gap: 15px; padding: 14px 18px; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-doc-icon { font-size: 2rem; color: var(--campusos-primary, #003d82); flex-shrink: 0; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-card-content { flex: 1; padding: 0; }
            #<?php echo esc_attr( $id ); ?> .campusos-w-download { flex-shrink: 0; font-size: 1.2rem; color: var(--campusos-primary, #003d82); }
            #<?php echo esc_attr( $id ); ?> .campusos-w-download:hover { opacity: 0.7; }
        </style>
        <div id="<?php echo esc_attr( $id ); ?>" class="campusos-w-list" style="display:flex;flex-direction:column;">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $pid     = get_the_ID();
                $file_id = get_post_meta( $pid, '_dokumen_file_dokumen', true );
                $file_url = $file_id ? wp_get_attachment_url( (int) $file_id ) : '';
                $kat_key = get_post_meta( $pid, '_dokumen_kategori_dokumen', true );
                $kat     = $kategori_labels[ $kat_key ] ?? $kat_key;
                $tahun   = get_post_meta( $pid, '_dokumen_tahun_dokumen', true );
            ?>
                <div class="campusos-w-card">
                    <div class="campusos-w-doc-icon"><span class="dashicons dashicons-media-document"></span></div>
                    <div class="campusos-w-card-content">
                        <h4 class="campusos-w-title"><?php the_title(); ?></h4>
                        <p class="campusos-w-body">
                            <?php if ( $kat ) : ?><span class="campusos-w-badge"><?php echo esc_html( $kat ); ?></span><?php endif; ?>
                            <?php if ( $tahun ) : ?> <span><?php echo esc_html( $tahun ); ?></span><?php endif; ?>
                        </p>
                    </div>
                    <?php if ( $file_url ) : ?>
                        <a href="<?php echo esc_url( $file_url ); ?>" class="campusos-w-download" target="_blank" title="Download"><span class="dashicons dashicons-download"></span></a>
                    <?php endif; ?>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php
    }
}
```

**Step 2: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/widgets/dokumen.php
git commit -m "feat: add Dokumen Elementor widget"
```

---

### Task 6: Create Fasilitas Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/fasilitas.php`

Follow same pattern as Kerjasama widget. Key differences:
- CPT: `fasilitas`
- Icon: `eicon-image-box`
- Meta fields: `_fasilitas_lokasi`, `_fasilitas_kapasitas`, `_fasilitas_deskripsi_fasilitas`
- Grid layout with image, title, description, location, capacity
- Uses `get_the_post_thumbnail_url()` for image
- Description fallback: `$this->get_description( $pid, '_fasilitas_deskripsi_fasilitas', 15 )`
- Content controls: count, columns
- Style sections: card, typography, image, spacing

**Commit:** `git commit -m "feat: add Fasilitas Elementor widget"`

---

### Task 7: Create Mitra Industri Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/mitra-industri.php`

Key differences from Kerjasama:
- CPT: `mitra_industri`
- Icon: `eicon-gallery-grid`
- Logo-grid layout (similar to partner-logos widget)
- Meta field: `_mitra_industri_logo_mitra_di` for logo, fallback to featured image
- Shows: logo image + name on hover or below
- CSS: grayscale + opacity effect on logos, full color on hover (like partner-logos)
- Content controls: count, columns (default 5)
- Style sections: image, spacing

**Commit:** `git commit -m "feat: add Mitra Industri Elementor widget"`

---

### Task 8: Create Mata Kuliah Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/mata-kuliah.php`

Key differences:
- CPT: `mata_kuliah`
- Icon: `eicon-table`
- Table layout with columns: Kode, Nama MK, SKS, Semester
- Meta fields: `_mata_kuliah_kode_mk`, `_mata_kuliah_sks`, `_mata_kuliah_semester`
- Filter controls: semester (number), konsentrasi (text)
- Ordered by `_mata_kuliah_semester` ASC
- Content controls: count, semester_filter, konsentrasi_filter
- Style sections: card, typography, spacing
- Additional CSS for table styling (striped rows, header bg color control)

**Commit:** `git commit -m "feat: add Mata Kuliah Elementor widget"`

---

### Task 9: Create Publikasi Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/publikasi.php`

Key differences:
- CPT: `publikasi`
- Icon: `eicon-post-list`
- List card layout
- Meta fields: `_publikasi_penulis_pub`, `_publikasi_jenis_pub`, `_publikasi_tahun_pub`, `_publikasi_link_pub`, `_publikasi_doi_pub`
- Filter controls: jenis (select: jurnal/prosiding/buku/hki), tahun (text)
- Content controls: count, meta filters
- Style sections: card, typography, badge, spacing

**Commit:** `git commit -m "feat: add Publikasi Elementor widget"`

---

### Task 10: Create Beasiswa Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/beasiswa.php`

Key differences:
- CPT: `beasiswa`
- Icon: `eicon-price-table`
- Grid card layout
- Meta fields: `_beasiswa_deadline_beasiswa`, `_beasiswa_link_pendaftaran`, `_beasiswa_deskripsi_beasiswa`
- Shows: title, deadline badge, description, registration button
- Content controls: count, columns (default 2)
- Style sections: card, typography, badge, button, spacing

**Commit:** `git commit -m "feat: add Beasiswa Elementor widget"`

---

### Task 11: Create Testimonial Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/testimonial.php`

Key differences:
- CPT: `testimonial`
- Icon: `eicon-testimonial`
- Grid/carousel layout with style selector control
- Meta fields: `_testimonial_jabatan`, `_testimonial_instansi`, `_testimonial_teks`
- Shows: testimonial text (from meta, fallback post_content), avatar, name, jabatan, instansi
- Content controls: count, columns, style (grid/carousel)
- Style sections: card, typography, image, spacing
- If carousel: horizontal scroll, scroll-snap CSS (like prestasi-carousel)

**Commit:** `git commit -m "feat: add Testimonial Elementor widget"`

---

### Task 12: Create Video Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/video-grid.php`

Key differences:
- CPT: `video`
- Icon: `eicon-play`
- Grid layout with play button overlay
- Meta fields: `_video_youtube_url`, `_video_video_duration`
- YouTube thumbnail extraction (same logic as shortcode)
- Shows: thumbnail with play overlay, title, duration
- Content controls: count, columns (default 3)
- Style sections: card, image, spacing

**Commit:** `git commit -m "feat: add Video Grid Elementor widget"`

---

### Task 13: Create Organisasi Mahasiswa Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/organisasi-mhs.php`

Key differences:
- CPT: `organisasi_mhs`
- Icon: `eicon-person`
- Grid card layout
- Meta fields: `_organisasi_mhs_logo_org` (image), `_organisasi_mhs_deskripsi_org`
- Shows: logo/thumbnail, title, description
- Content controls: count, columns (default 3)
- Style sections: card, typography, image, spacing

**Commit:** `git commit -m "feat: add Organisasi Mahasiswa Elementor widget"`

---

### Task 14: Create Sambutan Kaprodi Widget

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/widgets/sambutan.php`

Key differences:
- NOT a CPT query — reads from `get_option('campusos_pimpinan_settings')`
- Icon: `eicon-person`
- Single featured layout (photo left, content right)
- Content controls: style (full/compact), show_button (yes/no), button_text, button_url, excerpt_length
- Shows: photo, name (with gelar), jabatan, sambutan excerpt, CTA button
- Style sections: card, typography, image, button, spacing
- Follows exact same data logic as the `sambutan_kaprodi()` shortcode

**Commit:** `git commit -m "feat: add Sambutan Kaprodi Elementor widget"`

---

## Phase 4: Refactor Existing Widgets

### Task 15: Refactor 6 existing widgets to extend CampusOS_Widget_Base and add Style Tabs

**Files:**
- Modify: `wp-content/themes/campusos-academic/inc/elementor/widgets/team-grid.php`
- Modify: `wp-content/themes/campusos-academic/inc/elementor/widgets/announcement-list.php`
- Modify: `wp-content/themes/campusos-academic/inc/elementor/widgets/agenda-calendar.php`
- Modify: `wp-content/themes/campusos-academic/inc/elementor/widgets/faq-accordion.php`
- Modify: `wp-content/themes/campusos-academic/inc/elementor/widgets/gallery-grid.php`
- Modify: `wp-content/themes/campusos-academic/inc/elementor/widgets/prestasi-carousel.php`

**Step 1: For each widget, change the class extends:**

```php
// BEFORE:
class CampusOS_Team_Grid extends \Elementor\Widget_Base {
// AFTER:
class CampusOS_Team_Grid extends CampusOS_Widget_Base {
```

**Step 2: Remove duplicate `get_categories()` method** (now inherited from base).

**Step 3: Add Style Tab sections at end of `register_controls()`:**

For **team-grid.php** — add after `$this->end_controls_section();`:
```php
$this->register_style_card_section();
$this->register_style_typography_section();
$this->register_style_image_section();
$this->register_style_spacing_section();
```

For **announcement-list.php**:
```php
$this->register_style_card_section();
$this->register_style_typography_section();
$this->register_style_badge_section();
$this->register_style_spacing_section();
```

For **agenda-calendar.php**:
```php
$this->register_style_card_section();
$this->register_style_typography_section();
$this->register_style_badge_section();
$this->register_style_spacing_section();
```

For **faq-accordion.php**:
```php
$this->register_style_card_section();
$this->register_style_typography_section();
$this->register_style_spacing_section();
```

For **gallery-grid.php**:
```php
$this->register_style_image_section();
$this->register_style_spacing_section();
```

For **prestasi-carousel.php**:
```php
$this->register_style_card_section();
$this->register_style_typography_section();
$this->register_style_badge_section();
$this->register_style_spacing_section();
```

**Step 4: Update FAQ widget render to use meta field**

In `faq-accordion.php`, update the render to read from meta field:
```php
// BEFORE:
echo '<div class="campusos-faq-answer"><?php the_content(); ?></div>';
// AFTER:
$jawaban = get_post_meta( get_the_ID(), '_faq_jawaban_faq', true );
if ( empty( $jawaban ) ) {
    $jawaban = get_post_field( 'post_content', get_the_ID() );
}
echo '<div class="campusos-faq-answer">' . wp_kses_post( wpautop( $jawaban ) ) . '</div>';
```

**Step 5: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/widgets/team-grid.php
git add wp-content/themes/campusos-academic/inc/elementor/widgets/announcement-list.php
git add wp-content/themes/campusos-academic/inc/elementor/widgets/agenda-calendar.php
git add wp-content/themes/campusos-academic/inc/elementor/widgets/faq-accordion.php
git add wp-content/themes/campusos-academic/inc/elementor/widgets/gallery-grid.php
git add wp-content/themes/campusos-academic/inc/elementor/widgets/prestasi-carousel.php
git commit -m "refactor: update 6 existing widgets to extend CampusOS_Widget_Base with Style Tabs"
```

---

## Phase 5: Registration

### Task 16: Update elementor-init.php to register all new widgets

**Files:**
- Modify: `wp-content/themes/campusos-academic/inc/elementor/elementor-init.php`

**Step 1: Add require_once for widget-base and 11 new widgets**

Add after `$widgets_dir` line but BEFORE the existing widget requires:
```php
// Base class (must load first)
require_once $widgets_dir . 'widget-base.php';
```

Add after existing widget requires:
```php
// New widgets
require_once $widgets_dir . 'kerjasama.php';
require_once $widgets_dir . 'dokumen.php';
require_once $widgets_dir . 'fasilitas.php';
require_once $widgets_dir . 'mitra-industri.php';
require_once $widgets_dir . 'mata-kuliah.php';
require_once $widgets_dir . 'publikasi.php';
require_once $widgets_dir . 'beasiswa.php';
require_once $widgets_dir . 'testimonial.php';
require_once $widgets_dir . 'video-grid.php';
require_once $widgets_dir . 'organisasi-mhs.php';
require_once $widgets_dir . 'sambutan.php';
```

**Step 2: Register new widgets**

Add after existing `$widgets_manager->register()` calls:
```php
$widgets_manager->register( new \CampusOS_Kerjasama_Widget() );
$widgets_manager->register( new \CampusOS_Dokumen_Widget() );
$widgets_manager->register( new \CampusOS_Fasilitas_Widget() );
$widgets_manager->register( new \CampusOS_Mitra_Industri_Widget() );
$widgets_manager->register( new \CampusOS_Mata_Kuliah_Widget() );
$widgets_manager->register( new \CampusOS_Publikasi_Widget() );
$widgets_manager->register( new \CampusOS_Beasiswa_Widget() );
$widgets_manager->register( new \CampusOS_Testimonial_Widget() );
$widgets_manager->register( new \CampusOS_Video_Grid_Widget() );
$widgets_manager->register( new \CampusOS_Organisasi_Mhs_Widget() );
$widgets_manager->register( new \CampusOS_Sambutan_Widget() );
```

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/elementor-init.php
git commit -m "feat: register widget base class and 11 new Elementor widgets"
```

---

## Phase 6: Final Verification

### Task 17: End-to-end verification

**Step 1:** Open Elementor editor on any page. Verify:
- All 22 widgets appear under "CampusOS Academic" category
- Each new widget loads without errors
- Style tabs are visible and controls function
- Widget renders correct data from CPTs

**Step 2:** Open wp-admin → each CPT → Add New. Verify:
- No Gutenberg editor visible
- Form fields display correctly including new Deskripsi/Jawaban field

**Step 3:** View frontend pages that use shortcodes. Verify:
- Shortcodes still render correctly
- Description fallback to post_content works

**Step 4: Final commit**

```bash
git add -A
git commit -m "feat: complete Elementor widgets and CPT form-only conversion"
```

---

## CSS Class Convention (for all new widgets)

All new widgets use these CSS class prefixes for Elementor style selectors to work:

| Class | Purpose | Used by Style Tab |
|-------|---------|------------------|
| `.campusos-w-grid` | Grid container | Spacing (gap) |
| `.campusos-w-list` | List container | Spacing (gap) |
| `.campusos-w-card` | Card wrapper | Card styles (bg, radius, shadow) |
| `.campusos-w-card-content` | Card body | Card padding |
| `.campusos-w-title` | Title text | Typography (title) |
| `.campusos-w-body` | Body text | Typography (body) |
| `.campusos-w-image` | Image wrapper | Image styles |
| `.campusos-w-badge` | Badge element | Badge styles |
| `.campusos-w-btn` | Button element | Button styles |

This ensures all Style Tab controls work consistently across all widgets.
