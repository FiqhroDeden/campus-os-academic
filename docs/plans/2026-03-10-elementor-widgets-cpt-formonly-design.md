# Design: Elementor Widgets & CPT Form-Only Conversion

**Date:** 2026-03-10
**Status:** Approved

## Overview

Two improvements to CampusOS Academic:
1. Convert 11 CPTs from Gutenberg editor to form-only input (like tenaga_pendidik)
2. Create 11 new Elementor widgets with full Style Tabs, using a shared base class

## Part 1: CPT Form-Only Conversion

### Changes

Remove `'editor'` from `supports` array in these 11 CPTs:
- agenda, kerjasama, mata_kuliah, mitra_industri, beasiswa, pengumuman, faq, fasilitas, organisasi_mhs, prestasi, video

### New Description Fields

Add textarea meta field for CPTs that previously used editor for content:

| CPT | New Meta Field | Type |
|-----|---------------|------|
| agenda | deskripsi_agenda | textarea |
| kerjasama | deskripsi_kerjasama | textarea |
| mata_kuliah | deskripsi_mk | textarea |
| mitra_industri | deskripsi_mitra_di | textarea |
| beasiswa | deskripsi_beasiswa | textarea |
| faq | jawaban_faq | textarea |
| fasilitas | deskripsi_fasilitas | textarea |
| organisasi_mhs | deskripsi_org | textarea |
| prestasi | deskripsi_prestasi | textarea |
| video | deskripsi_video | textarea |

Note: pengumuman does not need a new field (already has sufficient meta fields).

### Data Migration

No automatic migration. New meta fields added alongside existing post_content. Shortcodes/widgets will check meta field first, fallback to post_content.

## Part 2: Widget Base Class

### File: `inc/elementor/widgets/widget-base.php`

`CampusOS_Widget_Base` extends `\Elementor\Widget_Base` with:

**Common Metadata:**
- `get_categories()` → `['campusos-academic']`

**Content Tab Helpers:**
- `register_content_count_control($default, $min, $max)` — number of items
- `register_columns_control($default, $options)` — grid columns selector
- `register_taxonomy_filter_control($taxonomy_slug, $label)` — taxonomy dropdown

**Style Tab Helpers:**
- `register_style_card_section()` — border-radius, box-shadow, padding, background color
- `register_style_typography_section()` — title/body font family, size, weight, color
- `register_style_spacing_section()` — gap between items, section margin/padding
- `register_style_image_section()` — border-radius, aspect-ratio, object-fit
- `register_style_badge_section()` — badge background, text color, border-radius

**Render Helpers:**
- `get_scoped_id($prefix)` — unique CSS id per widget instance
- `render_card_styles($id, $settings)` — output `<style>` block from style tab values
- `render_empty_state($message)` — "No items found" placeholder for editor
- `render_responsive_grid_css($id, $cols)` — CSS grid with responsive breakpoints

## Part 3: 11 New Elementor Widgets

All extend `CampusOS_Widget_Base`.

| # | Widget Class | CPT Source | Layout | Content Controls | Style Sections |
|---|-------------|-----------|--------|-----------------|----------------|
| 1 | CampusOS_Kerjasama_Widget | kerjasama | Grid cards | count, columns, jenis filter | card, typography, image, badge |
| 2 | CampusOS_Dokumen_Widget | dokumen | List/table | count, kategori filter, tahun filter | card, typography, badge |
| 3 | CampusOS_Fasilitas_Widget | fasilitas | Grid cards + overlay | count, columns | card, typography, image, spacing |
| 4 | CampusOS_Mitra_Industri_Widget | mitra_industri | Logo grid | count, columns | image, spacing, hover |
| 5 | CampusOS_Mata_Kuliah_Widget | mata_kuliah | Table/list | count, semester filter, konsentrasi filter | card, typography, badge |
| 6 | CampusOS_Publikasi_Widget | publikasi | List cards | count, jenis filter, tahun filter | card, typography, badge |
| 7 | CampusOS_Beasiswa_Widget | beasiswa | Grid cards | count, columns | card, typography, badge |
| 8 | CampusOS_Testimonial_Widget | testimonial | Carousel/grid | count, columns, style (grid/carousel) | card, typography, image, spacing |
| 9 | CampusOS_Video_Widget | video | Grid + play overlay | count, columns | card, image, spacing |
| 10 | CampusOS_Organisasi_Mhs_Widget | organisasi_mhs | Grid cards | count, columns | card, typography, image, spacing |
| 11 | CampusOS_Sambutan_Widget | option data | Single feature | style (full/compact), show_button | card, typography, image, button |

### Per-Widget Principles:
- Query via `WP_Query` with meta fields
- Description fallback: meta field deskripsi → post_content
- Empty state in editor preview
- Responsive: 1 col mobile, 2 col tablet, N col desktop

## Part 4: Update Existing Widgets

Refactor 6 existing widgets to extend `CampusOS_Widget_Base` and add Style Tabs:

1. **team-grid** → card, typography, image styles
2. **announcement-list** → card, typography, badge styles
3. **agenda-calendar** → card, typography, badge styles
4. **faq-accordion** → card, typography, spacing styles
5. **gallery-grid** → image, spacing, overlay styles
6. **prestasi-carousel** → card, typography, badge styles

Keep unchanged: hero-slider, stats-counter, news-grid, partner-logos, why-choose-us.

## Part 5: Shortcode Updates

- Shortcodes remain functional as fallback
- Update render methods to read meta field deskripsi first, fallback to post_content
- Update `elementor-init.php` to register widget-base.php and all 11 new widgets
- No new shortcodes needed

## Architecture

```
inc/elementor/
├── elementor-init.php          (updated: register base + 11 new widgets)
└── widgets/
    ├── widget-base.php          (NEW: base class)
    ├── hero-slider.php          (unchanged)
    ├── stats-counter.php        (unchanged)
    ├── news-grid.php            (unchanged)
    ├── partner-logos.php        (unchanged)
    ├── why-choose-us.php        (unchanged)
    ├── team-grid.php            (UPDATED: extend base, add style tab)
    ├── announcement-list.php    (UPDATED: extend base, add style tab)
    ├── agenda-calendar.php      (UPDATED: extend base, add style tab)
    ├── faq-accordion.php        (UPDATED: extend base, add style tab)
    ├── gallery-grid.php         (UPDATED: extend base, add style tab)
    ├── prestasi-carousel.php    (UPDATED: extend base, add style tab)
    ├── kerjasama.php            (NEW)
    ├── dokumen.php              (NEW)
    ├── fasilitas.php            (NEW)
    ├── mitra-industri.php       (NEW)
    ├── mata-kuliah.php          (NEW)
    ├── publikasi.php            (NEW)
    ├── beasiswa.php             (NEW)
    ├── testimonial.php          (NEW)
    ├── video.php                (NEW)
    ├── organisasi-mhs.php       (NEW)
    └── sambutan.php             (NEW)
```
