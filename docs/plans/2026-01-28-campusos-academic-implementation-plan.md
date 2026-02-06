# CampusOS Academic Theme - Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a single WordPress theme + companion plugin for all 115 faculty/study program websites at Perguruan Tinggi.

**Architecture:** Classic WordPress theme (`campusos-academic`) for presentation + companion plugin (`campusos-academic-core`) for data/logic. Elementor for page building. OAuth2 SSO via Laravel Passport. Single-site installs with auto-update mechanism.

**Tech Stack:** WordPress 6.9, PHP 8.x, Elementor, vanilla CSS (custom properties), vanilla JS, OAuth2/Laravel Passport SSO

**Design Doc:** `docs/plans/2026-01-28-campusos-academic-theme-design.md`

---

## Phase 1: Foundation — Plugin Scaffold

### Task 1.1: Create Companion Plugin Bootstrap

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/campusos-academic-core.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/class-plugin.php`

**Step 1: Create plugin main file**

```php
<?php
/**
 * Plugin Name: CampusOS Academic Core
 * Description: Core data and functionality for CampusOS Academic theme
 * Version: 1.0.0
 * Author: CampusOS Team
 * Text Domain: campusos-academic
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CampusOS_CORE_VERSION', '1.0.0' );
define( 'CampusOS_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'CampusOS_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once CampusOS_CORE_PATH . 'includes/class-plugin.php';

function campusos_core() {
    return CampusOS\Core\Plugin::instance();
}

campusos_core();
```

**Step 2: Create main plugin class**

```php
<?php
namespace CampusOS\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

final class Plugin {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        // Will load CPTs, admin, security, etc. as we build them
    }

    private function init_hooks() {
        add_action( 'init', [ $this, 'load_textdomain' ] );
        add_action( 'init', [ $this, 'register_post_types' ] );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'campusos-academic', false, dirname( plugin_basename( CampusOS_CORE_PATH ) ) . '/languages' );
    }

    public function register_post_types() {
        // Will be populated in Phase 2
    }
}
```

**Step 3: Create directory structure**

```bash
mkdir -p wp-content/plugins/campusos-academic-core/{includes/{cpt,admin/meta-boxes,security,sso,integrations,updater,export-import},languages}
```

**Step 4: Activate plugin in WordPress admin and verify no errors**

**Step 5: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/
git commit -m "feat: scaffold companion plugin campusos-academic-core"
```

---

## Phase 2: Foundation — Theme Scaffold

### Task 2.1: Create Theme Base Files

**Files:**
- Create: `wp-content/themes/campusos-academic/style.css`
- Create: `wp-content/themes/campusos-academic/functions.php`
- Create: `wp-content/themes/campusos-academic/theme.json`
- Create: `wp-content/themes/campusos-academic/index.php`
- Create: `wp-content/themes/campusos-academic/screenshot.png` (placeholder)

**Step 1: Create style.css with theme header**

```css
/*
Theme Name: CampusOS Academic
Theme URI: https://campusos.ac.id
Author: CampusOS Team
Author URI: https://campusos.ac.id
Description: Tema WordPress untuk Fakultas dan Program Studi Perguruan Tinggi. Clean, modern, dan aman.
Version: 1.0.0
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 8.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: campusos-academic
Domain Path: /languages
*/
```

**Step 2: Create theme.json with design tokens**

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 3,
  "settings": {
    "color": {
      "palette": [
        { "slug": "primary", "color": "#003d82", "name": "Primary" },
        { "slug": "secondary", "color": "#e67e22", "name": "Secondary" },
        { "slug": "text", "color": "#1a1a1a", "name": "Text" },
        { "slug": "text-light", "color": "#6b7280", "name": "Text Light" },
        { "slug": "background", "color": "#ffffff", "name": "Background" },
        { "slug": "background-alt", "color": "#f9fafb", "name": "Background Alt" },
        { "slug": "border", "color": "#e5e7eb", "name": "Border" }
      ]
    },
    "typography": {
      "fontFamilies": [
        {
          "fontFamily": "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
          "slug": "body",
          "name": "Body"
        }
      ]
    },
    "layout": {
      "contentSize": "1200px",
      "wideSize": "1400px"
    }
  }
}
```

**Step 3: Create functions.php**

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CampusOS_THEME_VERSION', '1.0.0' );
define( 'CampusOS_THEME_PATH', get_template_directory() );
define( 'CampusOS_THEME_URI', get_template_directory_uri() );

// Theme setup
add_action( 'after_setup_theme', function() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );

    register_nav_menus( [
        'primary'   => __( 'Menu Utama', 'campusos-academic' ),
        'footer'    => __( 'Menu Footer', 'campusos-academic' ),
    ] );

    // Image sizes
    add_image_size( 'campusos-card', 400, 300, true );
    add_image_size( 'campusos-hero', 1920, 600, true );
    add_image_size( 'campusos-profile', 300, 300, true );
} );

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'campusos-academic', CampusOS_THEME_URI . '/assets/css/main.css', [], CampusOS_THEME_VERSION );
    wp_enqueue_script( 'campusos-academic', CampusOS_THEME_URI . '/assets/js/main.js', [], CampusOS_THEME_VERSION, true );

    // Pass dynamic CSS variables from Customizer
    $primary = get_theme_mod( 'campusos_primary_color', '#003d82' );
    $secondary = get_theme_mod( 'campusos_secondary_color', '#e67e22' );
    $css = ":root {
        --campusos-primary: {$primary};
        --campusos-secondary: {$secondary};
    }";
    wp_add_inline_style( 'campusos-academic', $css );
} );

// Load includes
require_once CampusOS_THEME_PATH . '/inc/customizer/customizer.php';
require_once CampusOS_THEME_PATH . '/inc/template-functions.php';
```

**Step 4: Create minimal index.php**

```php
<?php get_header(); ?>
<main id="primary" class="site-main">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="entry-summary"><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
```

**Step 5: Create directory structure**

```bash
mkdir -p wp-content/themes/campusos-academic/{inc/{customizer,elementor/widgets,setup-wizard},templates,template-parts/{header,footer,content,sidebar},assets/{css,js,images,fonts},languages}
```

**Step 6: Commit**

```bash
git add wp-content/themes/campusos-academic/
git commit -m "feat: scaffold campusos-academic theme with base files"
```

---

### Task 2.2: Create Customizer Settings (Color, Site Mode)

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/customizer/customizer.php`

**Step 1: Create Customizer with color pickers and site mode**

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'customize_register', function( $wp_customize ) {

    // --- Panel: CampusOS Settings ---
    $wp_customize->add_panel( 'campusos_settings', [
        'title'    => __( 'CampusOS Academic', 'campusos-academic' ),
        'priority' => 10,
    ] );

    // --- Section: Site Identity ---
    $wp_customize->add_section( 'campusos_identity', [
        'title' => __( 'Identitas Situs', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    // Site Mode
    $wp_customize->add_setting( 'campusos_site_mode', [
        'default'           => 'prodi',
        'sanitize_callback' => function( $val ) {
            return in_array( $val, [ 'fakultas', 'prodi' ], true ) ? $val : 'prodi';
        },
    ] );
    $wp_customize->add_control( 'campusos_site_mode', [
        'label'   => __( 'Mode Situs', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'radio',
        'choices' => [
            'fakultas' => __( 'Fakultas', 'campusos-academic' ),
            'prodi'    => __( 'Program Studi', 'campusos-academic' ),
        ],
    ] );

    // Institution name
    $wp_customize->add_setting( 'campusos_institution_name', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'campusos_institution_name', [
        'label'   => __( 'Nama Fakultas / Program Studi', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'text',
    ] );

    // Parent link (for prodi → link to faculty, for faculty → link to university)
    $wp_customize->add_setting( 'campusos_parent_url', [
        'default'           => 'https://campusos.ac.id',
        'sanitize_callback' => 'esc_url_raw',
    ] );
    $wp_customize->add_control( 'campusos_parent_url', [
        'label'       => __( 'URL Induk (Fakultas / Universitas)', 'campusos-academic' ),
        'section'     => 'campusos_identity',
        'type'        => 'url',
    ] );

    // Address
    $wp_customize->add_setting( 'campusos_address', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ] );
    $wp_customize->add_control( 'campusos_address', [
        'label'   => __( 'Alamat', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'textarea',
    ] );

    // Phone
    $wp_customize->add_setting( 'campusos_phone', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'campusos_phone', [
        'label'   => __( 'Telepon', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'text',
    ] );

    // Email
    $wp_customize->add_setting( 'campusos_email', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ] );
    $wp_customize->add_control( 'campusos_email', [
        'label'   => __( 'Email', 'campusos-academic' ),
        'section' => 'campusos_identity',
        'type'    => 'email',
    ] );

    // --- Section: Colors ---
    $wp_customize->add_section( 'campusos_colors', [
        'title' => __( 'Warna Tema', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    // Primary color
    $wp_customize->add_setting( 'campusos_primary_color', [
        'default'           => '#003d82',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'campusos_primary_color', [
        'label'   => __( 'Warna Primary', 'campusos-academic' ),
        'section' => 'campusos_colors',
    ] ) );

    // Secondary color
    $wp_customize->add_setting( 'campusos_secondary_color', [
        'default'           => '#e67e22',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'campusos_secondary_color', [
        'label'   => __( 'Warna Secondary', 'campusos-academic' ),
        'section' => 'campusos_colors',
    ] ) );

    // --- Section: Social Media ---
    $wp_customize->add_section( 'campusos_social', [
        'title' => __( 'Media Sosial', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $socials = [
        'facebook'  => 'Facebook URL',
        'instagram' => 'Instagram URL',
        'youtube'   => 'YouTube URL',
        'twitter'   => 'Twitter / X URL',
        'tiktok'    => 'TikTok URL',
    ];
    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( "campusos_social_{$key}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ] );
        $wp_customize->add_control( "campusos_social_{$key}", [
            'label'   => $label,
            'section' => 'campusos_social',
            'type'    => 'url',
        ] );
    }

    // --- Section: Footer ---
    $wp_customize->add_section( 'campusos_footer', [
        'title' => __( 'Footer', 'campusos-academic' ),
        'panel' => 'campusos_settings',
    ] );

    $wp_customize->add_setting( 'campusos_footer_text', [
        'default'           => '© ' . date('Y') . ' Perguruan Tinggi',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'campusos_footer_text', [
        'label'   => __( 'Teks Footer', 'campusos-academic' ),
        'section' => 'campusos_footer',
        'type'    => 'textarea',
    ] );
} );
```

**Step 2: Create template-functions.php helper**

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function campusos_is_fakultas() {
    return get_theme_mod( 'campusos_site_mode', 'prodi' ) === 'fakultas';
}

function campusos_is_prodi() {
    return get_theme_mod( 'campusos_site_mode', 'prodi' ) === 'prodi';
}

function campusos_get_institution_name() {
    return get_theme_mod( 'campusos_institution_name', get_bloginfo( 'name' ) );
}

function campusos_primary_color() {
    return get_theme_mod( 'campusos_primary_color', '#003d82' );
}

function campusos_secondary_color() {
    return get_theme_mod( 'campusos_secondary_color', '#e67e22' );
}
```

**Step 3: Verify in Customizer — colors and site mode should appear under "CampusOS Academic" panel**

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/
git commit -m "feat: add Customizer settings for colors, site mode, identity"
```

---

### Task 2.3: Create Header & Footer Template Parts

**Files:**
- Create: `wp-content/themes/campusos-academic/header.php`
- Create: `wp-content/themes/campusos-academic/footer.php`
- Create: `wp-content/themes/campusos-academic/template-parts/header/site-header.php`
- Create: `wp-content/themes/campusos-academic/template-parts/footer/site-footer.php`
- Create: `wp-content/themes/campusos-academic/assets/css/main.css`
- Create: `wp-content/themes/campusos-academic/assets/js/main.js`

**Step 1: Create header.php**

Standard WordPress header with `<!DOCTYPE html>`, wp_head(), dynamic colors via inline CSS custom properties, responsive mega menu markup. Header displays:
- Logo (custom-logo support)
- Institution name from Customizer
- Primary navigation (wp_nav_menu with 'primary' location)
- Mobile hamburger toggle

**Step 2: Create footer.php**

Footer with 3-column widget area:
- Column 1: Address, phone, email from Customizer
- Column 2: Footer menu (wp_nav_menu with 'footer' location)
- Column 3: Social media icons from Customizer
- Bottom bar: copyright text from Customizer

**Step 3: Create main.css**

Base styles using CSS custom properties:
- CSS reset (minimal)
- Typography (Inter font via Google Fonts or local)
- 12-column grid utility (.container, .row, .col-*)
- Header styles (sticky, mega menu dropdowns)
- Footer styles
- Responsive breakpoints (480, 768, 1024, 1280)
- Mobile hamburger menu
- Button styles (.btn-primary, .btn-secondary)
- Card component (.card)
- Utility classes (.text-center, .sr-only, etc.)

**Step 4: Create main.js**

- Mobile menu toggle
- Sticky header on scroll
- Smooth scroll for anchor links
- Mega menu keyboard accessibility

**Step 5: Test — activate theme, verify header/footer render correctly, mobile menu works**

**Step 6: Commit**

```bash
git add wp-content/themes/campusos-academic/
git commit -m "feat: add header, footer, base CSS, and JS"
```

---

### Task 2.4: Create Page Templates (Single, Archive, 404, Search)

**Files:**
- Create: `wp-content/themes/campusos-academic/single.php`
- Create: `wp-content/themes/campusos-academic/archive.php`
- Create: `wp-content/themes/campusos-academic/page.php`
- Create: `wp-content/themes/campusos-academic/404.php`
- Create: `wp-content/themes/campusos-academic/search.php`
- Create: `wp-content/themes/campusos-academic/sidebar.php`
- Create: `wp-content/themes/campusos-academic/templates/template-fullwidth.php`

**Step 1:** Create each template using get_header/get_footer, the_content, standard WordPress loop. `template-fullwidth.php` has Template Name header comment and no sidebar. `page.php` checks for Elementor and renders accordingly.

**Step 2: Test all templates**

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/
git commit -m "feat: add single, archive, page, 404, search templates"
```

---

## Phase 3: Custom Post Types & CRUD

### Task 3.1: Create CPT Registration Base Class

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-base.php`

**Step 1: Create abstract base class for CPT registration**

```php
<?php
namespace CampusOS\Core\CPT;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class CPT_Base {
    abstract protected function get_slug(): string;
    abstract protected function get_labels(): array;
    abstract protected function get_args(): array;
    abstract protected function get_meta_fields(): array;

    public function register() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post_' . $this->get_slug(), [ $this, 'save_meta' ], 10, 2 );
    }

    public function register_post_type() {
        register_post_type( $this->get_slug(), $this->get_args() );
    }

    public function add_meta_boxes() {
        add_meta_box(
            $this->get_slug() . '_details',
            __( 'Detail', 'campusos-academic' ),
            [ $this, 'render_meta_box' ],
            $this->get_slug(),
            'normal',
            'high'
        );
    }

    public function render_meta_box( $post ) {
        wp_nonce_field( $this->get_slug() . '_nonce_action', $this->get_slug() . '_nonce' );
        $fields = $this->get_meta_fields();
        foreach ( $fields as $field ) {
            $value = get_post_meta( $post->ID, '_' . $field['id'], true );
            $this->render_field( $field, $value );
        }
    }

    protected function render_field( array $field, $value ) {
        $id = esc_attr( $field['id'] );
        $label = esc_html( $field['label'] );
        echo '<div class="campusos-field" style="margin-bottom:15px;">';
        echo "<label for='{$id}' style='display:block;font-weight:600;margin-bottom:4px;'>{$label}</label>";

        switch ( $field['type'] ) {
            case 'text':
            case 'url':
            case 'email':
            case 'number':
                $type = esc_attr( $field['type'] );
                $val = esc_attr( $value );
                echo "<input type='{$type}' id='{$id}' name='{$id}' value='{$val}' class='widefat' />";
                break;
            case 'textarea':
                echo "<textarea id='{$id}' name='{$id}' rows='4' class='widefat'>" . esc_textarea( $value ) . "</textarea>";
                break;
            case 'select':
                echo "<select id='{$id}' name='{$id}' class='widefat'>";
                foreach ( $field['options'] as $k => $v ) {
                    $sel = selected( $value, $k, false );
                    echo "<option value='" . esc_attr($k) . "' {$sel}>" . esc_html($v) . "</option>";
                }
                echo "</select>";
                break;
            case 'image':
                $img_url = $value ? wp_get_attachment_url( $value ) : '';
                echo "<div class='campusos-image-field'>";
                echo "<input type='hidden' id='{$id}' name='{$id}' value='" . esc_attr($value) . "' />";
                echo "<img src='" . esc_url($img_url) . "' style='max-width:200px;display:" . ($img_url ? 'block' : 'none') . ";margin-bottom:8px;' id='{$id}_preview' />";
                echo "<button type='button' class='button campusos-upload-btn' data-target='{$id}'>" . __('Pilih Gambar', 'campusos-academic') . "</button> ";
                echo "<button type='button' class='button campusos-remove-btn' data-target='{$id}' style='display:" . ($img_url ? 'inline-block' : 'none') . ";'>" . __('Hapus', 'campusos-academic') . "</button>";
                echo "</div>";
                break;
            case 'file':
                $file_url = $value ? wp_get_attachment_url( $value ) : '';
                echo "<input type='hidden' id='{$id}' name='{$id}' value='" . esc_attr($value) . "' />";
                echo "<span id='{$id}_name'>" . ($file_url ? basename($file_url) : '') . "</span> ";
                echo "<button type='button' class='button campusos-upload-btn' data-target='{$id}' data-type='file'>" . __('Pilih File', 'campusos-academic') . "</button> ";
                echo "<button type='button' class='button campusos-remove-btn' data-target='{$id}' style='display:" . ($value ? 'inline-block' : 'none') . ";'>" . __('Hapus', 'campusos-academic') . "</button>";
                break;
            case 'date':
                $val = esc_attr( $value );
                echo "<input type='date' id='{$id}' name='{$id}' value='{$val}' class='widefat' />";
                break;
        }
        if ( ! empty( $field['desc'] ) ) {
            echo "<p class='description'>" . esc_html( $field['desc'] ) . "</p>";
        }
        echo '</div>';
    }

    public function save_meta( $post_id, $post ) {
        if ( ! isset( $_POST[ $this->get_slug() . '_nonce' ] ) ) return;
        if ( ! wp_verify_nonce( $_POST[ $this->get_slug() . '_nonce' ], $this->get_slug() . '_nonce_action' ) ) return;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        foreach ( $this->get_meta_fields() as $field ) {
            $key = '_' . $field['id'];
            $value = isset( $_POST[ $field['id'] ] ) ? $_POST[ $field['id'] ] : '';

            switch ( $field['type'] ) {
                case 'text':
                case 'date':
                    $value = sanitize_text_field( $value );
                    break;
                case 'textarea':
                    $value = sanitize_textarea_field( $value );
                    break;
                case 'url':
                    $value = esc_url_raw( $value );
                    break;
                case 'email':
                    $value = sanitize_email( $value );
                    break;
                case 'number':
                case 'image':
                case 'file':
                    $value = absint( $value );
                    break;
                case 'select':
                    $value = sanitize_text_field( $value );
                    break;
            }
            update_post_meta( $post_id, $key, $value );
        }
    }
}
```

**Step 2: Create admin JS for media uploader**

Create `wp-content/plugins/campusos-academic-core/assets/js/admin-media.js`:

```js
(function($) {
    $(document).on('click', '.campusos-upload-btn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var targetId = btn.data('target');
        var isFile = btn.data('type') === 'file';
        var frame = wp.media({
            title: isFile ? 'Pilih File' : 'Pilih Gambar',
            multiple: false,
            library: isFile ? {} : { type: 'image' },
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#' + targetId).val(attachment.id);
            if (isFile) {
                $('#' + targetId + '_name').text(attachment.filename);
            } else {
                $('#' + targetId + '_preview').attr('src', attachment.url).show();
            }
            btn.siblings('.campusos-remove-btn').show();
        });
        frame.open();
    });

    $(document).on('click', '.campusos-remove-btn', function(e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        $('#' + targetId).val('');
        $('#' + targetId + '_preview').attr('src', '').hide();
        $('#' + targetId + '_name').text('');
        $(this).hide();
    });
})(jQuery);
```

Enqueue in Plugin class: `add_action('admin_enqueue_scripts', ...)` → enqueue `admin-media.js` with dependency on `jquery` and `wp-media`.

**Step 3: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/
git commit -m "feat: add CPT base class with meta box rendering and media upload"
```

---

### Task 3.2: Register All 14 Custom Post Types

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-pimpinan.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-tenaga-pendidik.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-kerjasama.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-fasilitas.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-prestasi.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-dokumen.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-agenda.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-faq.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-mata-kuliah.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-organisasi-mhs.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-mitra-industri.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-publikasi.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-beasiswa.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/cpt/class-cpt-galeri.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/class-plugin.php` — load all CPTs

**Step 1:** Create each CPT class extending `CPT_Base`. Each implements:
- `get_slug()` — e.g. `'pimpinan'`
- `get_labels()` — Indonesian labels (singular/plural)
- `get_args()` — `public`, `has_archive`, `supports` (title, thumbnail), `menu_icon` (dashicon), `show_in_rest` for Gutenberg
- `get_meta_fields()` — array of fields per design doc section 3.1

Example for Pimpinan:
```php
protected function get_meta_fields(): array {
    return [
        ['id' => 'pimpinan_nip', 'label' => 'NIP', 'type' => 'text'],
        ['id' => 'pimpinan_jabatan', 'label' => 'Jabatan', 'type' => 'text'],
        ['id' => 'pimpinan_email', 'label' => 'Email', 'type' => 'email'],
        ['id' => 'pimpinan_periode', 'label' => 'Periode', 'type' => 'text', 'desc' => 'Contoh: 2022-2026'],
        ['id' => 'pimpinan_bio', 'label' => 'Bio Singkat', 'type' => 'textarea'],
        ['id' => 'pimpinan_urutan', 'label' => 'Urutan Tampil', 'type' => 'number', 'desc' => 'Angka kecil tampil duluan'],
    ];
}
```

Full field list per CPT — follow the table from design doc section 3.1:

| CPT | Meta Fields (id, type) |
|-----|----------------------|
| `pimpinan` | nip (text), jabatan (text), email (email), periode (text), bio (textarea), urutan (number) |
| `tenaga_pendidik` | nidn (text), jabatan_fungsional (select: Guru Besar/Lektor Kepala/Lektor/Asisten Ahli), sertifikasi (text), bidang_keahlian (text), pendidikan (text), email (email), link_profil (url) |
| `kerjasama` | logo_mitra (image), jenis (select: DN/LN), tanggal_mulai (date), tanggal_akhir (date), dokumen_mou (file) |
| `fasilitas` | kapasitas (text), lokasi (text) — gallery via WP featured image + gallery post format |
| `prestasi` | tanggal (date), kategori (select: mahasiswa/dosen), tingkat (select: lokal/nasional/internasional), nama_peraih (text) |
| `dokumen` | file (file), kategori (select: peraturan/kalender/kurikulum/SOP/mutu/akreditasi), tahun (text), sumber (select: universitas/fakultas/jurusan) |
| `agenda` | tanggal_mulai (date), tanggal_akhir (date), lokasi (text), poster (image) |
| `faq` | urutan (number), kategori_faq (text) |
| `mata_kuliah` | kode (text), sks (number), semester (number), konsentrasi (text), link_rps (url), link_silabus (url) |
| `organisasi_mhs` | logo (image), struktur (image), tupoksi (textarea), program_kerja (textarea) |
| `mitra_industri` | logo (image), jenis_kerjasama (text) |
| `publikasi` | penulis (text), jenis (select: jurnal/prosiding/buku/HKI), tahun (text), link (url), doi (text), kategori_pub (select: dosen/mahasiswa) |
| `beasiswa` | persyaratan (textarea), deadline (date), link_pendaftaran (url) |
| `galeri` | kategori_galeri (text), tanggal (date) — photos via WP gallery |

**Step 2:** Update `class-plugin.php` to require and instantiate all 14 CPT classes in `load_dependencies()`.

**Step 3: Activate plugin, verify all 14 CPTs appear in admin sidebar with correct labels and icons**

**Step 4: Test creating an entry for each CPT — verify meta fields save and load correctly**

**Step 5: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/
git commit -m "feat: register all 14 custom post types with meta fields"
```

---

### Task 3.3: Create Page Meta Boxes for Structured Pages

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/admin/meta-boxes/class-mb-base.php`
- Create: 10 meta box files (sejarah, visi-misi, struktur-org, sambutan, akreditasi, cpl, penerimaan, biaya-ukt, statistik, tracer-study)
- Modify: `wp-content/plugins/campusos-academic-core/includes/class-plugin.php`

**Step 1: Create meta box base class**

Similar to CPT_Base but for pages. Attaches meta box when the page uses a specific template (via `page_template` meta). Includes a repeater field type for lists.

Repeater field render:
```php
case 'repeater':
    // Render existing rows with "Remove" button
    // "Add Row" button at bottom
    // Each row has sub-fields defined in $field['sub_fields']
    // Uses JS to clone template row
    break;
```

**Step 2:** Create each meta box class. Each specifies:
- Which page template it attaches to
- Fields array (following design doc section 3.2)

Key meta boxes:
- **Visi Misi**: 4 separate fields (visi=textarea, misi=repeater of text, tujuan=repeater, sasaran=repeater)
- **CPL**: 4 group repeaters (sikap, pengetahuan, keterampilan_umum, keterampilan_khusus)
- **Penerimaan**: Repeater (nama jalur, persyaratan textarea, link url) + biaya text
- **Biaya UKT**: Repeater (kategori text, nominal text)
- **Tracer Study**: Repeater (nama survey, link url) + dokumen file upload + statistik fields

**Step 3: Create admin JS for repeater fields**

`wp-content/plugins/campusos-academic-core/assets/js/admin-repeater.js`:
- Clone hidden template row
- Remove row
- Re-index field names on add/remove

**Step 4: Test — create a page, assign template "Visi Misi", verify meta box appears and repeater works**

**Step 5: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/
git commit -m "feat: add structured page meta boxes with repeater fields"
```

---

### Task 3.4: Create Admin Settings Page

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/admin/class-admin-settings.php`

**Step 1:** Register admin menu page under Settings: "CampusOS Academic".

Tabs:
- **Umum**: Site mode display (read from Customizer), quick links to Customizer
- **Keamanan**: Toggle security features, content scanner settings, whitelist domains
- **SSO**: Client ID, secret, base URL, role mapping, enable/disable (Phase 5)
- **Integrasi API**: SIAKAD/SIGAP settings (Phase 7, placeholder)
- **Export/Import**: Export & import buttons (Phase 6)

Use WordPress Settings API (`register_setting`, `add_settings_section`, `add_settings_field`) with proper sanitization.

**Step 2: Test settings save/load**

**Step 3: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/admin/
git commit -m "feat: add admin settings page with tabs"
```

---

## Phase 4: Theme Page Templates (Frontend)

### Task 4.1: Create Frontend Templates for CPTs and Structured Pages

**Files:**
- Create: `wp-content/themes/campusos-academic/templates/template-sejarah.php`
- Create: `wp-content/themes/campusos-academic/templates/template-visi-misi.php`
- Create: `wp-content/themes/campusos-academic/templates/template-struktur-organisasi.php`
- Create: `wp-content/themes/campusos-academic/templates/template-pimpinan.php`
- Create: `wp-content/themes/campusos-academic/templates/template-tenaga-pendidik.php`
- Create: `wp-content/themes/campusos-academic/templates/template-kerjasama.php`
- Create: `wp-content/themes/campusos-academic/templates/template-fasilitas.php`
- Create: `wp-content/themes/campusos-academic/templates/template-akreditasi.php`
- Create: `wp-content/themes/campusos-academic/templates/template-dokumen.php`
- Create: `wp-content/themes/campusos-academic/templates/template-statistik.php`
- Create: CPT archive & single templates in theme root (`archive-pimpinan.php`, `single-tenaga_pendidik.php`, etc.)

**Step 1:** Each page template reads meta box data and renders it in clean HTML using CSS classes tied to the design system. Uses `get_post_meta()` for static pages, `WP_Query` for CPT listing pages.

Example `template-tenaga-pendidik.php`:
- Query all `tenaga_pendidik` CPT ordered by jabatan_fungsional
- Render grid of profile cards (photo, name, jabatan, email)
- Filter by jabatan_fungsional dropdown (JS-based client-side filter)

**Step 2: Create CSS for all template layouts**

Add to `assets/css/main.css`:
- `.page-hero` — page header with title + breadcrumb
- `.profile-grid` — responsive grid for people
- `.profile-card` — card with photo, name, subtitle
- `.timeline` — for sejarah
- `.document-list` — for dokumen with download buttons
- `.stat-grid` — for statistik numbers
- `.accordion` — for FAQ and expandable content

**Step 3: Test all templates with sample data**

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/
git commit -m "feat: add frontend page templates and CPT templates"
```

---

## Phase 5: Elementor Integration

### Task 5.1: Register Elementor Widget Category and Base

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/elementor-init.php`
- Create: `wp-content/themes/campusos-academic/inc/elementor/class-widget-base.php`

**Step 1:** Check if Elementor is active. Register custom widget category "CampusOS Academic". Create abstract base widget class.

```php
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    // Register each custom widget
});
add_action( 'elementor/elements/categories_registered', function( $elements_manager ) {
    $elements_manager->add_category( 'campusos-academic', [
        'title' => 'CampusOS Academic',
        'icon'  => 'fa fa-university',
    ] );
} );
```

**Step 2: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/
git commit -m "feat: register Elementor widget category"
```

---

### Task 5.2: Create All 11 Elementor Widgets

**Files:**
- Create 11 widget files in `wp-content/themes/campusos-academic/inc/elementor/widgets/`

**Step 1: Implement each widget** with `_register_controls()` (Elementor controls for admin) and `render()` (frontend HTML). Each widget:

1. **Hero Slider** (`hero-slider.php`): Repeater control for slides (image, title, subtitle, button text, button url). Frontend: CSS-only carousel or lightweight JS slider.

2. **Stats Counter** (`stats-counter.php`): Repeater (icon, number, label). Frontend: animated count-up on scroll (IntersectionObserver + vanilla JS).

3. **Team Grid** (`team-grid.php`): Controls: select CPT source (pimpinan or tenaga_pendidik), number of items, columns. Frontend: WP_Query → profile cards grid.

4. **News Grid** (`news-grid.php`): Controls: number of posts, category filter. Frontend: WP_Query on posts → card grid.

5. **Announcement List** (`announcement-list.php`): Controls: category, number. Frontend: list with date + title.

6. **Agenda Calendar** (`agenda-calendar.php`): Controls: number of events. Frontend: WP_Query on `agenda` CPT → list with date badge.

7. **FAQ Accordion** (`faq-accordion.php`): Controls: select FAQ category, number. Frontend: WP_Query on `faq` CPT → accordion markup.

8. **Partner Logos** (`partner-logos.php`): Repeater (logo image, link). Frontend: logo grid/row.

9. **Gallery Grid** (`gallery-grid.php`): Controls: select galeri category, number, columns. Frontend: filterable grid with lightbox.

10. **Prestasi Carousel** (`prestasi-carousel.php`): Controls: number, kategori filter. Frontend: WP_Query on `prestasi` CPT → horizontal scroll carousel.

11. **Why Choose Us** (`why-choose-us.php`): Repeater (icon, title, description). Frontend: feature boxes grid.

**Step 2: Test each widget in Elementor editor — verify controls and frontend render**

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/
git commit -m "feat: add all 11 custom Elementor widgets"
```

---

### Task 5.3: Create Elementor Template Kit

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/elementor/templates/` (JSON template files)

**Step 1:** Create Elementor template JSON exports for:
- Homepage (Prodi variant)
- Homepage (Fakultas variant)

These are pre-built page layouts using the custom widgets above, importable via Elementor's template import.

**Step 2: Document how to import templates in admin**

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/elementor/templates/
git commit -m "feat: add Elementor starter template kit"
```

---

## Phase 6: Security Hardening

### Task 6.1: WordPress Hardening

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/security/class-hardening.php`

**Step 1:** Implement all Layer 1 and Layer 3 security measures:

```php
<?php
namespace CampusOS\Core\Security;

class Hardening {
    public function init() {
        // Disable XML-RPC
        add_filter( 'xmlrpc_enabled', '__return_false' );
        add_filter( 'xmlrpc_methods', function() { return []; } );

        // Disable REST API user enumeration
        add_filter( 'rest_endpoints', function( $endpoints ) {
            if ( isset( $endpoints['/wp/v2/users'] ) ) unset( $endpoints['/wp/v2/users'] );
            if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
            return $endpoints;
        } );

        // Remove WP version
        remove_action( 'wp_head', 'wp_generator' );
        add_filter( 'the_generator', '__return_empty_string' );

        // Security headers
        add_action( 'send_headers', function() {
            if ( ! is_admin() ) {
                header( 'X-Frame-Options: SAMEORIGIN' );
                header( 'X-Content-Type-Options: nosniff' );
                header( 'Referrer-Policy: strict-origin-when-cross-origin' );
                header( "Permissions-Policy: camera=(), microphone=(), geolocation=()" );
            }
        } );

        // Disable file editing
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }

        // Block author enumeration via ?author=N
        add_action( 'template_redirect', function() {
            if ( is_author() && ! is_admin() ) {
                wp_redirect( home_url(), 301 );
                exit;
            }
        } );

        // Rate limit login attempts
        add_filter( 'authenticate', [ $this, 'check_login_rate_limit' ], 30, 3 );
        add_action( 'wp_login_failed', [ $this, 'record_failed_login' ] );
    }

    public function check_login_rate_limit( $user, $username, $password ) {
        if ( empty( $username ) ) return $user;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $key = 'campusos_login_attempts_' . md5( $ip );
        $attempts = (int) get_transient( $key );
        if ( $attempts >= 5 ) {
            return new \WP_Error( 'too_many_attempts',
                __( 'Terlalu banyak percobaan login. Silakan coba lagi dalam 15 menit.', 'campusos-academic' )
            );
        }
        return $user;
    }

    public function record_failed_login( $username ) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $key = 'campusos_login_attempts_' . md5( $ip );
        $attempts = (int) get_transient( $key );
        set_transient( $key, $attempts + 1, 15 * MINUTE_IN_SECONDS );
    }
}
```

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/security/
git commit -m "feat: add WordPress hardening and security headers"
```

---

### Task 6.2: Content Scanner (Anti Judi Online)

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/security/class-content-scanner.php`

**Step 1:** Implement scheduled scanner:

```php
<?php
namespace CampusOS\Core\Security;

class Content_Scanner {
    private $keywords = [
        'slot gacor', 'slot online', 'togel', 'casino', 'judi online',
        'poker online', 'sbobet', 'pragmatic play', 'slot88', 'joker123',
        'slot deposit', 'bandar togel', 'agen judi', 'taruhan', 'jackpot',
        'scatter', 'maxwin', 'rtp slot', 'bocoran slot', 'slot hari ini',
    ];

    public function init() {
        // Schedule cron
        add_action( 'campusos_content_scan', [ $this, 'run_scan' ] );
        if ( ! wp_next_scheduled( 'campusos_content_scan' ) ) {
            wp_schedule_event( time(), 'six_hours', 'campusos_content_scan' );
        }

        // Register custom cron interval
        add_filter( 'cron_schedules', function( $schedules ) {
            $schedules['six_hours'] = [
                'interval' => 6 * HOUR_IN_SECONDS,
                'display'  => __( 'Setiap 6 Jam', 'campusos-academic' ),
            ];
            return $schedules;
        } );

        // Admin notice for quarantined content
        add_action( 'admin_notices', [ $this, 'quarantine_notice' ] );
    }

    public function run_scan() {
        global $wpdb;
        $flagged = [];

        // Build regex pattern
        $pattern = implode( '|', array_map( 'preg_quote', $this->keywords ) );

        // Scan posts, pages, and all public CPTs
        $results = $wpdb->get_results(
            "SELECT ID, post_title, post_content, post_type FROM {$wpdb->posts}
             WHERE post_status = 'publish'"
        );

        foreach ( $results as $post ) {
            $content = strtolower( $post->post_title . ' ' . $post->post_content );
            if ( preg_match( '/(' . $pattern . ')/i', $content, $matches ) ) {
                // Flag post
                update_post_meta( $post->ID, '_campusos_quarantined', 1 );
                update_post_meta( $post->ID, '_campusos_quarantine_reason', 'Keyword terdeteksi: ' . $matches[1] );
                update_post_meta( $post->ID, '_campusos_quarantine_date', current_time( 'mysql' ) );

                // Set to draft
                wp_update_post( [ 'ID' => $post->ID, 'post_status' => 'draft' ] );

                $flagged[] = $post;
            }
        }

        // Also scan for hidden content patterns (SEO spam)
        $hidden_results = $wpdb->get_results(
            "SELECT ID, post_content FROM {$wpdb->posts}
             WHERE post_status = 'publish'
             AND (post_content LIKE '%display:none%' OR post_content LIKE '%font-size:0%' OR post_content LIKE '%visibility:hidden%')"
        );

        foreach ( $hidden_results as $post ) {
            // Extract hidden text and check for spam keywords
            if ( preg_match( '/(display\s*:\s*none|font-size\s*:\s*0|visibility\s*:\s*hidden)[^<]*(' . $pattern . ')/i', $post->post_content ) ) {
                update_post_meta( $post->ID, '_campusos_quarantined', 1 );
                update_post_meta( $post->ID, '_campusos_quarantine_reason', 'Hidden spam content terdeteksi' );
                wp_update_post( [ 'ID' => $post->ID, 'post_status' => 'draft' ] );
                $flagged[] = $post;
            }
        }

        // Send email if flagged
        if ( ! empty( $flagged ) ) {
            $admin_email = get_option( 'admin_email' );
            $site_name = get_bloginfo( 'name' );
            $message = "Content Scanner menemukan " . count($flagged) . " konten mencurigakan:\n\n";
            foreach ( $flagged as $post ) {
                $message .= "- [{$post->post_type}] {$post->post_title} (ID: {$post->ID})\n";
            }
            $message .= "\nKonten telah di-quarantine (draft). Silakan review di admin panel.";
            wp_mail( $admin_email, "[{$site_name}] Peringatan: Konten Mencurigakan Terdeteksi", $message );
        }

        // Log scan result
        update_option( 'campusos_last_scan', [
            'time'    => current_time( 'mysql' ),
            'flagged' => count( $flagged ),
        ] );
    }

    public function quarantine_notice() {
        global $wpdb;
        $count = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_campusos_quarantined' AND meta_value = '1'"
        );
        if ( $count > 0 ) {
            echo '<div class="notice notice-warning"><p>';
            printf(
                __( '⚠ CampusOS Security: %d konten di-quarantine karena terdeteksi spam. <a href="%s">Review sekarang</a>.', 'campusos-academic' ),
                $count,
                admin_url( 'edit.php?meta_key=_campusos_quarantined&meta_value=1' )
            );
            echo '</p></div>';
        }
    }
}
```

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/security/class-content-scanner.php
git commit -m "feat: add content scanner for gambling spam detection"
```

---

### Task 6.3: File Integrity Monitor

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/security/class-file-integrity.php`

**Step 1:** On plugin activation, hash all theme + plugin files (SHA-256), store in option. Daily cron checks for changed/new/deleted files. Alert admin.

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/security/class-file-integrity.php
git commit -m "feat: add file integrity monitor"
```

---

### Task 6.4: Activity Log

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/security/class-activity-log.php`

**Step 1:** Create custom DB table `{prefix}campusos_activity_log` on plugin activation. Hook into: `save_post`, `delete_post`, `wp_login`, `wp_logout`, `activated_plugin`, `deactivated_plugin`, `updated_option`. Log: user_id, ip, action, object_type, object_id, details, timestamp.

Admin page under "CampusOS Academic → Activity Log" with filterable list table. Auto-cleanup entries older than 90 days via daily cron.

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/security/class-activity-log.php
git commit -m "feat: add admin activity log"
```

---

## Phase 7: SSO Integration

### Task 7.1: Implement OAuth2 SSO with Laravel Passport

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/sso/class-sso-auth.php`
- Modify: `wp-content/plugins/campusos-academic-core/includes/admin/class-admin-settings.php` (SSO tab)

**Step 1: Create SSO auth class**

```php
<?php
namespace CampusOS\Core\SSO;

class SSO_Auth {
    private $base_url;
    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function init() {
        $settings = get_option( 'campusos_sso_settings', [] );
        if ( empty( $settings['enabled'] ) ) return;

        $this->base_url      = $settings['base_url'] ?? 'https://sso.campusos.ac.id';
        $this->client_id     = $settings['client_id'] ?? '';
        $this->client_secret = $settings['client_secret'] ?? '';
        $this->redirect_uri  = admin_url( 'admin-ajax.php?action=campusos_sso_callback' );

        // Intercept wp-login.php → redirect to SSO
        add_action( 'login_init', [ $this, 'redirect_to_sso' ] );

        // AJAX callback handler (nopriv because user is not logged in yet)
        add_action( 'wp_ajax_nopriv_campusos_sso_callback', [ $this, 'handle_callback' ] );
        add_action( 'wp_ajax_campusos_sso_callback', [ $this, 'handle_callback' ] );

        // On WP logout → also logout from SSO
        add_action( 'wp_logout', [ $this, 'sso_logout' ] );
    }

    public function redirect_to_sso() {
        // Allow fallback login if ?fallback=1 and user is the emergency admin
        if ( isset( $_GET['fallback'] ) && $_GET['fallback'] === '1' ) return;
        if ( isset( $_POST['log'] ) ) return; // Allow POST login for fallback

        $state = wp_generate_password( 40, false );
        set_transient( 'campusos_sso_state_' . $state, true, 10 * MINUTE_IN_SECONDS );

        $url = $this->base_url . '/oauth/authorize?' . http_build_query( [
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'response_type' => 'code',
            'scope'         => '',
            'state'         => $state,
        ] );

        wp_redirect( $url );
        exit;
    }

    public function handle_callback() {
        // Validate state
        $state = sanitize_text_field( $_GET['state'] ?? '' );
        $code  = sanitize_text_field( $_GET['code'] ?? '' );

        if ( ! $state || ! get_transient( 'campusos_sso_state_' . $state ) ) {
            wp_die( __( 'Invalid state. Possible CSRF attack.', 'campusos-academic' ), 403 );
        }
        delete_transient( 'campusos_sso_state_' . $state );

        if ( ! $code ) {
            wp_die( __( 'No authorization code received.', 'campusos-academic' ), 400 );
        }

        // Exchange code for token
        $token_response = wp_remote_post( $this->base_url . '/oauth/token', [
            'body' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->redirect_uri,
                'code'          => $code,
            ],
            'timeout' => 30,
        ] );

        if ( is_wp_error( $token_response ) ) {
            wp_die( __( 'SSO token exchange failed.', 'campusos-academic' ), 500 );
        }

        $token_data = json_decode( wp_remote_retrieve_body( $token_response ), true );
        $access_token = $token_data['access_token'] ?? null;

        if ( ! $access_token ) {
            wp_die( __( 'No access token received.', 'campusos-academic' ), 500 );
        }

        // Get user info
        $user_response = wp_remote_get( $this->base_url . '/api/me/roles', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept'        => 'application/json',
            ],
            'body' => [
                'client_id' => $this->client_id,
            ],
            'timeout' => 30,
        ] );

        if ( is_wp_error( $user_response ) ) {
            wp_die( __( 'Failed to get user info from SSO.', 'campusos-academic' ), 500 );
        }

        $user_info = json_decode( wp_remote_retrieve_body( $user_response ), true );

        if ( empty( $user_info['user_id'] ) || empty( $user_info['email'] ) ) {
            wp_die( __( 'Invalid user data from SSO.', 'campusos-academic' ), 500 );
        }

        // Find or create WP user
        $wp_user = $this->find_or_create_user( $user_info );

        if ( is_wp_error( $wp_user ) ) {
            wp_die( $wp_user->get_error_message(), 500 );
        }

        // Store token in user meta (encrypted)
        update_user_meta( $wp_user->ID, '_sso_access_token', $this->encrypt_token( $access_token ) );
        update_user_meta( $wp_user->ID, '_sso_user_id', sanitize_text_field( $user_info['user_id'] ) );

        // Log in
        wp_set_auth_cookie( $wp_user->ID, true );
        wp_set_current_user( $wp_user->ID );

        wp_redirect( admin_url() );
        exit;
    }

    private function find_or_create_user( array $info ) {
        $sso_user_id = sanitize_text_field( $info['user_id'] );
        $email       = sanitize_email( $info['email'] );
        $name        = sanitize_text_field( $info['name'] ?? '' );
        $sso_roles   = $info['roles'] ?? [];

        // Find by SSO user_id meta
        $users = get_users( [ 'meta_key' => '_sso_user_id', 'meta_value' => $sso_user_id, 'number' => 1 ] );
        if ( ! empty( $users ) ) {
            $user = $users[0];
            // Update display name and role
            wp_update_user( [ 'ID' => $user->ID, 'display_name' => $name ] );
            $this->map_roles( $user, $sso_roles );
            return $user;
        }

        // Find by email
        $user = get_user_by( 'email', $email );
        if ( $user ) {
            update_user_meta( $user->ID, '_sso_user_id', $sso_user_id );
            $this->map_roles( $user, $sso_roles );
            return $user;
        }

        // Create new user
        $username = sanitize_user( strtolower( explode( '@', $email )[0] ) );
        // Ensure unique username
        $base = $username;
        $i = 1;
        while ( username_exists( $username ) ) {
            $username = $base . $i;
            $i++;
        }

        $user_id = wp_insert_user( [
            'user_login'   => $username,
            'user_email'   => $email,
            'display_name' => $name,
            'user_pass'    => wp_generate_password( 32, true, true ), // Random, unused
            'role'         => 'subscriber', // Default, will be overridden by role mapping
        ] );

        if ( is_wp_error( $user_id ) ) return $user_id;

        update_user_meta( $user_id, '_sso_user_id', $sso_user_id );
        $user = get_user_by( 'id', $user_id );
        $this->map_roles( $user, $sso_roles );

        return $user;
    }

    private function map_roles( \WP_User $user, array $sso_roles ) {
        $settings = get_option( 'campusos_sso_settings', [] );
        $mapping = $settings['role_mapping'] ?? [
            'Admin'  => 'administrator',
            'Editor' => 'editor',
        ];

        foreach ( $sso_roles as $sso_role ) {
            if ( isset( $mapping[ $sso_role ] ) ) {
                $user->set_role( $mapping[ $sso_role ] );
                return; // Use first matched role
            }
        }
        // Default: editor
        $user->set_role( 'editor' );
    }

    public function sso_logout() {
        $user_id = get_current_user_id();
        $encrypted_token = get_user_meta( $user_id, '_sso_access_token', true );
        if ( $encrypted_token ) {
            $token = $this->decrypt_token( $encrypted_token );
            wp_remote_get( $this->base_url . '/api/logmeout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json',
                ],
                'timeout' => 10,
            ] );
            delete_user_meta( $user_id, '_sso_access_token' );
        }
    }

    private function encrypt_token( string $token ): string {
        $key = wp_salt( 'auth' );
        return base64_encode( openssl_encrypt( $token, 'AES-256-CBC', $key, 0, substr( md5( $key ), 0, 16 ) ) );
    }

    private function decrypt_token( string $encrypted ): string {
        $key = wp_salt( 'auth' );
        return openssl_decrypt( base64_decode( $encrypted ), 'AES-256-CBC', $key, 0, substr( md5( $key ), 0, 16 ) );
    }
}
```

**Step 2:** Add SSO settings tab to admin settings page — fields for base_url, client_id, client_secret, enable/disable toggle, role mapping repeater.

**Step 3: Test SSO flow with sso.campusos.ac.id (requires valid client credentials)**

**Step 4: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/sso/
git commit -m "feat: implement SSO OAuth2 integration with Laravel Passport"
```

---

## Phase 8: Export/Import & Auto-Update

### Task 8.1: Setup Wizard

**Files:**
- Create: `wp-content/themes/campusos-academic/inc/setup-wizard/class-setup-wizard.php`
- Create: `wp-content/themes/campusos-academic/inc/setup-wizard/views/` (step templates)

**Step 1:** On theme activation, redirect to setup wizard (one-time, via `after_switch_theme` hook + transient flag).

Wizard steps:
1. Welcome — choose site mode (fakultas/prodi)
2. Colors — primary & secondary color pickers
3. Identity — logo upload, institution name, address
4. Demo Content — one-click import of starter pages (Sejarah, Visi Misi, etc.), menu, widgets
5. Done — link to Customizer and admin

Uses WordPress admin-post.php for form submissions. Stores settings via Customizer API (`set_theme_mod`).

Demo content import: creates pages with correct page templates assigned, creates primary menu with standard structure.

**Step 2: Commit**

```bash
git add wp-content/themes/campusos-academic/inc/setup-wizard/
git commit -m "feat: add first-time setup wizard"
```

---

### Task 8.2: JSON Export/Import

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/export-import/class-exporter.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/export-import/class-importer.php`

**Step 1: Exporter** — collects all data into JSON:
- `theme_mods` — all Customizer settings
- `options` — plugin settings (security, SSO config minus secrets)
- `cpt_data` — all CPT posts with meta (each CPT type as array)
- `pages` — all pages with meta, template assignments, content
- `menus` — nav menu structure with items
- `widgets` — widget settings per sidebar

Generates downloadable `.json` file via admin-post.php handler.

**Step 2: Importer** — reads JSON, creates/updates:
- Set theme_mods
- Set options
- Create CPT posts with meta
- Create pages with templates
- Create menus with items
- Set widgets

Shows progress and result summary.

**Step 3: Add Export/Import buttons to admin settings page**

**Step 4: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/export-import/
git commit -m "feat: add JSON site export/import"
```

---

### Task 8.3: Auto-Update System

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/updater/class-theme-updater.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/updater/class-plugin-updater.php`

**Step 1:** Hook into `pre_set_site_transient_update_themes` and `pre_set_site_transient_update_plugins`. Check remote server for new version:

```php
$response = wp_remote_get( $update_server_url . '/api/check?' . http_build_query([
    'slug'    => 'campusos-academic',
    'version' => CampusOS_THEME_VERSION,
    'type'    => 'theme', // or 'plugin'
]) );
```

If new version available, inject into WordPress update transient so admin sees "Update Available" notification. Download URL points to ZIP on update server.

Update server URL configurable in admin settings.

**Step 2: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/updater/
git commit -m "feat: add auto-update system for theme and plugin"
```

---

## Phase 9: API Integration Framework

### Task 9.1: Create Abstract API Connector

**Files:**
- Create: `wp-content/plugins/campusos-academic-core/includes/integrations/class-api-connector.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/integrations/class-siakad-connector.php`
- Create: `wp-content/plugins/campusos-academic-core/includes/integrations/class-sigap-connector.php`

**Step 1:** Create abstract connector with `fetch()` (cached HTTP via WP transients), `test_connection()`, configurable base URL and auth headers. Create stub SIAKAD and SIGAP connectors.

**Step 2:** Add "Integrasi API" tab to admin settings with connection fields and test button (AJAX).

**Step 3:** Create shortcode `[campusos_data]` and Elementor widget "CampusOS Data" — both show placeholder "Belum terhubung" when API not configured.

**Step 4: Commit**

```bash
git add wp-content/plugins/campusos-academic-core/includes/integrations/
git commit -m "feat: add API integration framework with SIAKAD/SIGAP stubs"
```

---

## Phase 10: Polish & Documentation

### Task 10.1: Create Admin Dashboard Widget

**Files:**
- Modify: `wp-content/plugins/campusos-academic-core/includes/class-plugin.php`

**Step 1:** Add WordPress dashboard widget showing:
- Site mode & institution name
- Content stats (count of each CPT)
- Last security scan result
- SSO status (connected/disconnected)
- Update status

**Step 2: Commit**

```bash
git commit -m "feat: add admin dashboard widget"
```

---

### Task 10.2: Internationalization

**Step 1:** Run through all PHP files — ensure all user-facing strings use `__()` or `_e()` with 'campusos-academic' text domain.

**Step 2:** Generate .pot file:

```bash
wp i18n make-pot wp-content/themes/campusos-academic/ wp-content/themes/campusos-academic/languages/campusos-academic.pot
wp i18n make-pot wp-content/plugins/campusos-academic-core/ wp-content/plugins/campusos-academic-core/languages/campusos-academic.pot
```

**Step 3: Commit**

```bash
git commit -m "chore: ensure i18n for all user-facing strings"
```

---

### Task 10.3: Final Security Review

**Step 1:** Review checklist:
- [ ] All `$_POST`/`$_GET` inputs sanitized
- [ ] All outputs escaped (esc_html, esc_attr, esc_url, wp_kses_post)
- [ ] All forms have nonce verification
- [ ] All database queries use `$wpdb->prepare()`
- [ ] No `eval()`, `serialize()`/`unserialize()` on user input
- [ ] File uploads restricted to whitelist
- [ ] SSO tokens encrypted at rest
- [ ] No secrets in exported JSON

**Step 2: Fix any findings**

**Step 3: Commit**

```bash
git commit -m "security: final review and fixes"
```

---

## Summary: Task Count by Phase

| Phase | Tasks | Description |
|-------|-------|-------------|
| 1 | 1.1 | Plugin scaffold |
| 2 | 2.1 - 2.4 | Theme scaffold, customizer, header/footer, templates |
| 3 | 3.1 - 3.4 | CPT base, 14 CPTs, page meta boxes, admin settings |
| 4 | 4.1 | Frontend templates for all pages |
| 5 | 5.1 - 5.3 | Elementor init, 11 widgets, template kit |
| 6 | 6.1 - 6.4 | Hardening, content scanner, file integrity, activity log |
| 7 | 7.1 | SSO OAuth2 integration |
| 8 | 8.1 - 8.3 | Setup wizard, export/import, auto-update |
| 9 | 9.1 | API framework stubs |
| 10 | 10.1 - 10.3 | Dashboard widget, i18n, security review |

**Total: 20 tasks across 10 phases**
