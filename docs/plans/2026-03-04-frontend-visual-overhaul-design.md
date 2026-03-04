# CampusOS Academic — Frontend Visual Overhaul Design

**Date:** 2026-03-04
**Goal:** Marketplace-ready frontend (ThemeForest quality)
**Approach:** Visual Overhaul — redesign CSS + minor template adjustments
**Style:** Modern & Clean

---

## Context

CampusOS Academic (v1.1.0) is functionally mature (18 CPTs, 11 Elementor widgets, security, SSO, export/import) but the frontend needs polish for marketplace distribution. Current overall readiness: 7.5/10. This design addresses the visual/CSS gaps to reach 9/10.

### Key Issues Found
- No form styling (input, textarea, select)
- No focus states (WCAG violation)
- No dark mode, print styles, or reduced-motion support
- Navigation shows hamburger on desktop
- Inconsistent spacing, card patterns, and badge variants
- Footer empty when no content configured
- 404 page too minimal
- 2,174-line monolithic CSS file
- Stats section shows "0" without data
- Page header banner uses solid green instead of brand gradient

---

## Section 1: Foundation — Design Tokens & Base Styles

### CSS Custom Properties (expand from ~6 to ~25)

**Colors:**
- `--campusos-primary` + `--campusos-primary-light`, `--campusos-primary-dark`
- `--campusos-secondary` + `--campusos-secondary-light`, `--campusos-secondary-dark`
- `--campusos-surface`, `--campusos-surface-alt`
- `--campusos-text`, `--campusos-text-secondary`, `--campusos-text-muted`
- `--campusos-success`, `--campusos-warning`, `--campusos-error`
- `--campusos-border`, `--campusos-border-light`

**Spacing (8px grid):**
- `--space-1` (4px) through `--space-8` (64px)

**Shadows (elevation):**
- `--shadow-sm`, `--shadow-md`, `--shadow-lg`, `--shadow-xl`

**Transitions:**
- `--transition-fast` (150ms), `--transition-normal` (250ms), `--transition-slow` (350ms)

**Border radius:**
- `--radius-sm` (4px), `--radius-md` (8px), `--radius-lg` (16px), `--radius-full` (9999px)

### Dark Mode
- `@media (prefers-color-scheme: dark)` overrides on `:root`
- All components auto-adapt via CSS variables

### Form Styling
- Input, textarea, select: consistent border, padding, focus ring
- Label styling with proper spacing
- Validation states: `:valid`, `:invalid`, `:required`

### Accessibility
- Focus: `outline: 2px solid var(--campusos-primary); outline-offset: 2px`
- Focus-visible for keyboard-only users
- `@media (prefers-reduced-motion: reduce)` — disable all animations/transitions

### Print Styles
- `@media print` — hide nav, footer, sidebar; force white bg, black text

---

## Section 2: Navigation & Header

### Structure (3-layer)
1. **Top bar** (optional via Customizer): contact info — email | phone | parent link
2. **Main header**: Logo + Name (left) | Desktop Nav (center-right) | Search icon (right)
3. **Sticky**: shrinks on scroll with box-shadow

### Desktop (≥1024px)
- Horizontal menu with hover underline (bottom border slide-in)
- Dropdown submenus: fade-in + translateY
- Active page: solid bottom border

### Mobile (<1024px)
- Hamburger → full-screen overlay from right
- Stacked menu with accordion submenus
- Close button (X) top-right
- Semi-transparent background overlay

### Breadcrumbs (interior pages)
- Smaller font, muted color
- Separator: `›` (chevron)
- Current page: bold, no link

---

## Section 3: Homepage Sections

### 3a. Hero
- Gradient overlay: `linear-gradient(135deg, rgba(primary, 0.9), rgba(primary-dark, 0.7))`
- Subtle geometric pattern
- Title: clamp(2.5rem, 5vw, 4rem), weight 700
- CTA: primary (solid) + secondary (outline) side by side
- Scroll-down indicator (animated chevron)
- Full viewport height desktop, 70vh mobile

### 3b. Quick Links (3 cards)
- Negative margin (overlap hero)
- Icon: circle bg with gradient
- Hover: translateY(-4px) + deeper shadow + border-left accent

### 3c. Sambutan (Dean's Welcome)
- Photo: rounded-lg with border/shadow (not full circle)
- Decorative quote mark (") in background
- 40/60 split desktop, stacked mobile
- Alt-color section background

### 3d. Why Choose Us
- 2x2 feature grid with icons in colored circles
- Fade-in animation on scroll
- Background: `var(--campusos-surface-alt)`

### 3e. Stats Counter
- Full-width gradient background
- Numbers: clamp(3rem, 5vw, 5rem), weight 700
- Labels: uppercase, letter-spacing
- Vertical dividers between items

### 3f. News Grid
- First card: 2-column span (featured)
- Placeholder: gradient background
- Date: badge-style
- Hover: image scale(1.05) + shadow
- "Lihat Semua" link

### 3g. Pengumuman + Agenda
- Card wrapper with styled header
- Items: hover background, left border accent
- Date: monospace, smaller
- Max 5 items + "Lihat Semua"

### 3h. Prestasi
- Trophy/medal icon per item
- Alternating background rows
- Category badges (Nasional, Internasional, Regional)

### 3i. Akreditasi Badge
- Card with gold/amber accent
- Large grade text
- SK number in monospace

### 3j. FAQ Accordion
- Smoother max-height transition
- Chevron rotation animation
- Hover background on question
- Focus-visible styles

### 3k. Mitra/Partnership
- Logo grid with placeholders
- Hover: grayscale → color
- "Lihat Semua" link

### 3l. CTA Section
- Gradient background (primary → primary-dark)
- Larger impact text
- Dual CTA buttons
- Subtle decorative elements

---

## Section 4: Interior Pages, Cards & Components

### 4a. Page Header Banner
- Gradient background (primary → primary-dark)
- Breadcrumbs integrated inside banner
- Title: white, large, bold
- Height: ~200px desktop, ~150px mobile

### 4b. Profile/Staff Cards
- Compact: 250px photo area
- Placeholder: avatar icon + gradient bg
- Name centered, position muted
- Hover overlay: "Lihat Profil"
- Grid: 4→2→1 columns

### 4c. Unified Card System
```
.card          — base (padding, radius, shadow, hover)
.card--horizontal  — horizontal layout variant
.card--featured    — larger/highlighted variant
.card__image   — image container
.card__body    — content area
.card__meta    — date/category/author
.card__title   — heading
```

### 4d. Badge System
```
.badge             — base
.badge--primary    — primary color
.badge--secondary  — secondary color
.badge--success    — green
.badge--warning    — amber
.badge--sm / --lg  — size variants
```

### 4e. 404 Page
- Large "404" CSS gradient text
- Friendly Indonesian message
- Search form
- Suggested links (Beranda, Berita, Kontak)

### 4f. Footer
- 4 columns: About | Quick Links | Kontak | Social Media
- Graceful empty state (hide empty columns)
- Bottom bar: copyright + "Powered by CampusOS Academic"
- Dark gradient navy background
- Social icons with hover color

---

## Implementation Notes

### Files to Modify
- `wp-content/themes/campusos-academic/assets/css/main.css` — primary changes
- `wp-content/themes/campusos-academic/assets/js/main.js` — scroll animations, mobile menu
- `wp-content/themes/campusos-academic/header.php` — desktop nav structure
- `wp-content/themes/campusos-academic/footer.php` — 4-column layout
- `wp-content/themes/campusos-academic/404.php` — enhanced 404
- `wp-content/themes/campusos-academic/template-parts/homepage-*.php` — minor adjustments
- `wp-content/themes/campusos-academic/templates/template-homepage.php` — section wrappers

### Files NOT Modified
- Plugin code (no changes needed)
- Elementor widgets (CSS-only improvements)
- CPT/meta box logic (unchanged)

### Approach
- CSS-first: maximize impact through stylesheet changes
- Template changes only where HTML structure must change
- Preserve all existing functionality
- Test across all 28+ page templates
- Verify dark mode, print, reduced-motion, focus states
