# CampusOS Academic Frontend Visual Overhaul — Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Transform CampusOS Academic frontend from functional to ThemeForest marketplace quality with Modern & Clean aesthetic.

**Architecture:** CSS-first approach — maximize impact through stylesheet changes, template changes only where HTML structure must change. All changes in the theme directory (`wp-content/themes/campusos-academic/`). Plugin code untouched.

**Tech Stack:** Vanilla CSS (CSS custom properties), vanilla JS (no frameworks), PHP templates (WordPress)

**Design Doc:** `docs/plans/2026-03-04-frontend-visual-overhaul-design.md`

---

## Task 1: Foundation — Design Tokens

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css:1-28` (replace `:root` and reset section)
- Modify: `wp-content/themes/campusos-academic/functions.php:46-54` (expand inline CSS variables)

**Step 1: Replace the CSS reset and add design tokens**

Replace lines 1-28 of `main.css` with expanded design token system. The existing `:root` variables are injected via `functions.php` inline styles; we add the rest as defaults in the CSS file itself.

```css
/* ============================================
   CampusOS Academic Theme - Main Stylesheet
   ============================================ */

/* --- Design Tokens --- */
:root {
    /* Colors — primary/secondary injected via functions.php */
    --campusos-primary: #003d82;
    --campusos-primary-light: #e8f0fe;
    --campusos-primary-dark: #002855;
    --campusos-secondary: #e67e22;
    --campusos-secondary-light: #fef3e2;
    --campusos-secondary-dark: #c96b1b;

    /* Surfaces */
    --campusos-bg: #ffffff;
    --campusos-bg-alt: #f8fafc;
    --campusos-surface: #ffffff;
    --campusos-surface-alt: #f1f5f9;

    /* Text */
    --campusos-text: #1a1a1a;
    --campusos-text-secondary: #4b5563;
    --campusos-text-light: #6b7280;
    --campusos-text-muted: #9ca3af;

    /* Status */
    --campusos-success: #059669;
    --campusos-warning: #d97706;
    --campusos-error: #dc2626;

    /* Borders */
    --campusos-border: #e5e7eb;
    --campusos-border-light: #f3f4f6;

    /* Spacing (8px grid) */
    --space-1: 0.25rem;   /* 4px */
    --space-2: 0.5rem;    /* 8px */
    --space-3: 0.75rem;   /* 12px */
    --space-4: 1rem;      /* 16px */
    --space-5: 1.5rem;    /* 24px */
    --space-6: 2rem;      /* 32px */
    --space-7: 3rem;      /* 48px */
    --space-8: 4rem;      /* 64px */

    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);

    /* Transitions */
    --transition-fast: 150ms ease;
    --transition-normal: 250ms ease;
    --transition-slow: 350ms ease;

    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 16px;
    --radius-full: 9999px;

    /* Font */
    --campusos-font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* --- Reset --- */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
    font-family: var(--campusos-font-family);
    font-size: 16px;
    line-height: 1.6;
    color: var(--campusos-text);
    background-color: var(--campusos-bg);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
img { max-width: 100%; height: auto; display: block; }
a { color: var(--campusos-primary); text-decoration: none; transition: color var(--transition-fast); }
a:hover { color: var(--campusos-secondary); }
ul, ol { list-style: none; }
h1, h2, h3, h4, h5, h6 {
    color: var(--campusos-text);
    line-height: 1.3;
    font-weight: 700;
}
h1 { font-size: clamp(1.75rem, 3vw, 2.25rem); }
h2 { font-size: clamp(1.375rem, 2.5vw, 1.75rem); }
h3 { font-size: 1.25rem; }
h4 { font-size: 1.125rem; }
```

**Step 2: Update functions.php inline CSS to inject light/dark variants**

In `functions.php`, expand the inline CSS (around line 46-54) to compute and inject color variants:

```php
$primary = get_theme_mod( 'campusos_primary_color', '#003d82' );
$secondary = get_theme_mod( 'campusos_secondary_color', '#e67e22' );

// Compute light variants (mix with white at 90%)
$p_rgb = sscanf( $primary, '#%02x%02x%02x' );
$s_rgb = sscanf( $secondary, '#%02x%02x%02x' );
$p_light = sprintf( '#%02x%02x%02x', 232 + ($p_rgb[0]-232)*0.1, 240 + ($p_rgb[1]-240)*0.1, 254 + ($p_rgb[2]-254)*0.1 );
$p_dark  = sprintf( '#%02x%02x%02x', max(0,$p_rgb[0]-20), max(0,$p_rgb[1]-20), max(0,$p_rgb[2]-20) );
$s_light = sprintf( '#%02x%02x%02x', 254 + ($s_rgb[0]-254)*0.1, 243 + ($s_rgb[1]-243)*0.1, 226 + ($s_rgb[2]-226)*0.1 );
$s_dark  = sprintf( '#%02x%02x%02x', max(0,$s_rgb[0]-30), max(0,$s_rgb[1]-30), max(0,$s_rgb[2]-30) );

$css = ":root {
    --campusos-primary: {$primary};
    --campusos-primary-light: {$p_light};
    --campusos-primary-dark: {$p_dark};
    --campusos-secondary: {$secondary};
    --campusos-secondary-light: {$s_light};
    --campusos-secondary-dark: {$s_dark};
    --campusos-font-family: '{$font_family}', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
body { font-family: var(--campusos-font-family); }";
wp_add_inline_style( 'campusos-academic', $css );
```

**Step 3: Verify in browser**

Navigate to `http://wp-unpatti.local/` — all existing styles should still work. Colors, fonts, spacing all intact. The page should look identical or very slightly refined (clamp typography).

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css wp-content/themes/campusos-academic/functions.php
git commit -m "feat(theme): add design token system with CSS custom properties"
```

---

## Task 2: Foundation — Forms, Focus States & Accessibility

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (add after reset, before Layout section ~line 41)

**Step 1: Add form styling and focus states**

Insert after the reset section, before `/* --- Layout --- */`:

```css
/* --- Form Elements --- */
input[type="text"],
input[type="email"],
input[type="url"],
input[type="password"],
input[type="search"],
input[type="number"],
input[type="tel"],
input[type="date"],
textarea,
select {
    display: block;
    width: 100%;
    padding: 0.625rem 0.875rem;
    font-family: inherit;
    font-size: 0.9375rem;
    line-height: 1.5;
    color: var(--campusos-text);
    background-color: var(--campusos-bg);
    border: 1px solid var(--campusos-border);
    border-radius: var(--radius-sm);
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    appearance: none;
}
input[type="text"]:focus,
input[type="email"]:focus,
input[type="url"]:focus,
input[type="password"]:focus,
input[type="search"]:focus,
input[type="number"]:focus,
input[type="tel"]:focus,
input[type="date"]:focus,
textarea:focus,
select:focus {
    outline: none;
    border-color: var(--campusos-primary);
    box-shadow: 0 0 0 3px var(--campusos-primary-light);
}
textarea { min-height: 120px; resize: vertical; }
select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    padding-right: 2.5rem;
}
label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--campusos-text-secondary);
    margin-bottom: var(--space-1);
}
input::placeholder,
textarea::placeholder {
    color: var(--campusos-text-muted);
}

/* --- Focus States (WCAG 2.1 AA) --- */
:focus-visible {
    outline: 2px solid var(--campusos-primary);
    outline-offset: 2px;
}
a:focus-visible {
    outline: 2px solid var(--campusos-primary);
    outline-offset: 2px;
    border-radius: 2px;
}
button:focus-visible,
.btn:focus-visible {
    outline: 2px solid var(--campusos-primary);
    outline-offset: 2px;
}

/* --- Reduced Motion --- */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* --- Print Styles --- */
@media print {
    .site-header, .site-footer, .campusos-breadcrumbs,
    .page-hero, .menu-toggle, .social-share,
    nav, aside, .widget-area { display: none !important; }
    body { color: #000; background: #fff; font-size: 12pt; }
    a { color: #000; text-decoration: underline; }
    a[href]::after { content: " (" attr(href) ")"; font-size: 0.8em; }
    .container { max-width: 100%; padding: 0; }
    img { max-width: 100% !important; }
}
```

**Step 2: Verify in browser**

1. Navigate to any page — check that focus rings appear when tabbing through links/buttons
2. Check search form (if visible) — inputs should have consistent styling
3. Check FAQ — buttons should have focus-visible rings

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): add form styling, focus states, reduced-motion and print styles"
```

---

## Task 3: Foundation — Dark Mode

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (append at end of file, before any closing comment)

**Step 1: Add dark mode overrides**

Append to end of `main.css`:

```css
/* ============================================
   Dark Mode
   ============================================ */
@media (prefers-color-scheme: dark) {
    :root {
        --campusos-bg: #0f172a;
        --campusos-bg-alt: #1e293b;
        --campusos-surface: #1e293b;
        --campusos-surface-alt: #334155;
        --campusos-text: #f1f5f9;
        --campusos-text-secondary: #cbd5e1;
        --campusos-text-light: #94a3b8;
        --campusos-text-muted: #64748b;
        --campusos-border: #334155;
        --campusos-border-light: #1e293b;
        --campusos-primary-light: rgba(0, 61, 130, 0.2);
        --campusos-secondary-light: rgba(230, 126, 34, 0.2);
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.4);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.5);
        --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.5);
    }

    body { background-color: var(--campusos-bg); color: var(--campusos-text); }

    /* Cards */
    .card, .profile-card, .stat-item, .agenda-info-box,
    .beasiswa-info-box, .pengumuman-attachment, .faq-question,
    .faq-answer { background: var(--campusos-surface); }

    /* Header */
    .header-main { background: var(--campusos-surface); border-color: var(--campusos-border); }
    .main-navigation .sub-menu { background: var(--campusos-surface); }
    .main-navigation .sub-menu li a:hover { background: var(--campusos-surface-alt); }

    /* Breadcrumbs */
    .campusos-breadcrumbs { background: var(--campusos-bg-alt); border-color: var(--campusos-border); }

    /* Content */
    .entry-content blockquote { background: var(--campusos-bg-alt); }

    /* Placeholders */
    .profile-placeholder, .card-img-placeholder, .kerjasama-placeholder,
    .galeri-placeholder, .staff-card-placeholder { background: var(--campusos-surface-alt); }

    /* Tables */
    .ukt-table tbody tr:nth-child(even) { background: var(--campusos-bg-alt); }
    .ukt-table th, .ukt-table td { border-color: var(--campusos-border); }

    /* Forms */
    input[type="text"], input[type="email"], input[type="url"],
    input[type="password"], input[type="search"], input[type="number"],
    input[type="tel"], input[type="date"], textarea, select {
        background-color: var(--campusos-surface);
        border-color: var(--campusos-border);
        color: var(--campusos-text);
    }

    /* Search results */
    .search-result { border-color: var(--campusos-border); }

    /* Document table */
    .doc-table { border-color: var(--campusos-border); }
    .doc-table-row { border-color: var(--campusos-border); }
    .doc-table-row:hover { background: var(--campusos-bg-alt); }

    /* Timeline */
    .timeline-content { background: var(--campusos-bg-alt); border-color: var(--campusos-border); }

    /* Publikasi */
    .publikasi-item { border-color: var(--campusos-border); }
    .publikasi-icon { background: var(--campusos-surface-alt); }

    /* Misc */
    img { opacity: 0.9; }
}
```

**Step 2: Verify in browser**

Use browser DevTools to emulate dark mode: DevTools → Rendering → Emulate CSS media feature `prefers-color-scheme: dark`. Check homepage, interior pages, cards, forms.

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): add dark mode support via prefers-color-scheme"
```

---

## Task 4: Navigation — Desktop Menu & Sticky Header

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (Navigation section ~line 108-460)
- Modify: `wp-content/themes/campusos-academic/assets/js/main.js:1-42` (header scroll, mobile breakpoint)

**Step 1: Update navigation CSS**

The key change: show the desktop navigation at `≥1024px` instead of hiding it at `≤1280px`. Replace the navigation CSS sections.

Replace the `/* --- Main Navigation --- */` through the end of `/* --- Hamburger / Mobile Toggle --- */` styles (current lines ~108-200) — update the desktop nav to show by default with underline hover animation.

Replace the responsive breakpoint `@media (max-width: 1280px)` for navigation (around lines 396-460) to use `@media (max-width: 1023px)` so desktop nav shows at 1024px+.

Key CSS changes:
```css
/* Desktop nav: visible by default, hidden below 1024px */
.main-navigation .primary-menu > li > a {
    position: relative;
    padding: 0.75rem 0.75rem;
}
/* Hover underline animation */
.main-navigation .primary-menu > li > a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0.75rem;
    right: 0.75rem;
    height: 2px;
    background: var(--campusos-primary);
    transform: scaleX(0);
    transition: transform var(--transition-normal);
}
.main-navigation .primary-menu > li > a:hover::after,
.main-navigation .primary-menu > li.current-menu-item > a::after,
.main-navigation .primary-menu > li.current-menu-ancestor > a::after {
    transform: scaleX(1);
}

/* Dropdown: fade-in + translateY */
.main-navigation .sub-menu {
    opacity: 0;
    visibility: hidden;
    transform: translateY(8px);
    transition: opacity var(--transition-normal), transform var(--transition-normal), visibility var(--transition-normal);
    border-radius: var(--radius-md);
}
.main-navigation .primary-menu li:hover > .sub-menu {
    display: block;
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
```

Update `.menu-toggle` to `display: none` by default (it already is), and only show at `max-width: 1023px`.

**Step 2: Update JS mobile breakpoint**

In `main.js` line 19, change `1280` to `1023`:
```js
if (window.innerWidth <= 1023) {
```

Also update the scroll handler (lines 33-42) to use CSS classes instead of inline styles:
```js
// Sticky header
var header = document.querySelector('.header-main');
if (header) {
    window.addEventListener('scroll', function() {
        header.classList.toggle('is-scrolled', window.scrollY > 10);
    });
}
```

And add the CSS:
```css
.header-main.is-scrolled {
    box-shadow: var(--shadow-md);
}
```

**Step 3: Verify in browser**

1. At 1280px viewport — desktop nav visible (was hidden before)
2. At 1024px — still visible
3. At 1023px and below — hamburger shows, nav hidden
4. Hover over menu items — underline slides in
5. Hover over items with submenus — dropdown fades in
6. Scroll down — header gets shadow

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css wp-content/themes/campusos-academic/assets/js/main.js
git commit -m "feat(theme): fix desktop navigation, add hover underline and dropdown animations"
```

---

## Task 5: Button & Badge System Upgrade

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (Buttons section ~line 201-231, badge sections)

**Step 1: Replace button styles**

Replace the existing `.btn` section with an enhanced version:

```css
/* --- Buttons --- */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    padding: 0.625rem 1.25rem;
    font-family: inherit;
    font-size: 0.875rem;
    font-weight: 600;
    border: 2px solid transparent;
    border-radius: var(--radius-sm);
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: all var(--transition-fast);
    line-height: 1.5;
}
.btn:active { transform: translateY(1px); }
.btn:disabled, .btn[disabled] {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}
.btn-primary {
    background: var(--campusos-primary);
    border-color: var(--campusos-primary);
    color: #fff;
}
.btn-primary:hover {
    background: var(--campusos-primary-dark);
    border-color: var(--campusos-primary-dark);
    color: #fff;
}
.btn-secondary {
    background: var(--campusos-secondary);
    border-color: var(--campusos-secondary);
    color: #fff;
}
.btn-secondary:hover {
    background: var(--campusos-secondary-dark);
    border-color: var(--campusos-secondary-dark);
    color: #fff;
}
.btn-outline {
    background: transparent;
    border-color: var(--campusos-primary);
    color: var(--campusos-primary);
}
.btn-outline:hover {
    background: var(--campusos-primary);
    color: #fff;
}
.btn-outline-white {
    background: transparent;
    border-color: #fff;
    color: #fff;
}
.btn-outline-white:hover {
    background: #fff;
    color: var(--campusos-primary);
}
.btn-sm { padding: 0.4rem 0.75rem; font-size: 0.8125rem; }
.btn-lg { padding: 0.75rem 1.75rem; font-size: 1rem; }
```

**Step 2: Add unified badge system**

Add a badge section (either near the existing badge styles or replace the scattered badge definitions):

```css
/* --- Badges --- */
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.2rem 0.5rem;
    border-radius: var(--radius-sm);
    line-height: 1.4;
}
.badge--primary { background: var(--campusos-primary-light); color: var(--campusos-primary); }
.badge--secondary { background: var(--campusos-secondary-light); color: var(--campusos-secondary-dark); }
.badge--success { background: #d1fae5; color: #065f46; }
.badge--warning { background: #fef3c7; color: #92400e; }
.badge--error { background: #fee2e2; color: #991b1b; }
.badge--neutral { background: var(--campusos-bg-alt); color: var(--campusos-text-light); }
.badge--sm { font-size: 0.6875rem; padding: 0.125rem 0.375rem; }
.badge--lg { font-size: 0.8125rem; padding: 0.3rem 0.625rem; }
```

Keep the existing `badge-tingkat`, `badge-nasional`, etc. for backwards compatibility — they still work fine.

**Step 3: Verify in browser**

Check buttons on homepage (CTA buttons), beasiswa pages (action buttons), and interior pages.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): upgrade button system with active/disabled states and unified badge system"
```

---

## Task 6: Card System Upgrade

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (Cards section ~line 233-245)

**Step 1: Enhance the card base styles**

Replace the existing `.card` block:

```css
/* --- Cards --- */
.card {
    background: var(--campusos-surface);
    border: 1px solid var(--campusos-border);
    border-radius: var(--radius-md);
    overflow: hidden;
    transition: box-shadow var(--transition-normal), transform var(--transition-normal);
}
.card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}
.card-img {
    width: 100%;
    aspect-ratio: 4/3;
    object-fit: cover;
    transition: transform var(--transition-slow);
}
.card:hover .card-img { transform: scale(1.03); }
.card-body { padding: var(--space-4); }
.card-title {
    font-size: 1rem;
    margin-bottom: var(--space-2);
    line-height: 1.4;
}
.card-title a { color: var(--campusos-text); }
.card-title a:hover { color: var(--campusos-primary); }
.card-text {
    font-size: 0.875rem;
    color: var(--campusos-text-light);
    line-height: 1.6;
}
.card-date {
    font-size: 0.75rem;
    color: var(--campusos-text-muted);
    display: inline-block;
    margin-bottom: var(--space-2);
}
.card-meta {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    font-size: 0.8125rem;
    color: var(--campusos-text-light);
    margin-bottom: var(--space-2);
}

/* Card image placeholder */
.card-img-placeholder {
    width: 100%;
    aspect-ratio: 4/3;
    background: linear-gradient(135deg, var(--campusos-bg-alt), var(--campusos-surface-alt));
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-img-placeholder .dashicons {
    font-size: 48px;
    width: 48px;
    height: 48px;
    color: var(--campusos-text-muted);
}
```

**Step 2: Enhance profile card placeholders**

Update the profile placeholder to use gradient:
```css
.profile-placeholder {
    width: 100%;
    aspect-ratio: 1;
    background: linear-gradient(135deg, var(--campusos-bg-alt), var(--campusos-surface-alt));
    display: flex;
    align-items: center;
    justify-content: center;
}
```

**Step 3: Verify in browser**

Navigate to homepage (Berita section), Tenaga Pendidik archive, any archive page with cards. Cards should have subtle hover lift and image zoom.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): upgrade card system with hover animations and gradient placeholders"
```

---

## Task 7: Page Header Banner & Breadcrumbs Redesign

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (page-hero and breadcrumb sections ~line 340-384)
- Modify: `wp-content/themes/campusos-academic/header.php:65` (move breadcrumbs into page-hero where possible)

**Step 1: Redesign page-hero CSS**

Replace the `.page-hero` section:

```css
/* --- Page Hero (Interior Page Header) --- */
.page-hero {
    background: linear-gradient(135deg, var(--campusos-primary), var(--campusos-primary-dark));
    color: #fff;
    padding: 3rem 0 2.5rem;
    position: relative;
    overflow: hidden;
}
.page-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 50%;
}
.page-hero h1 {
    color: #fff;
    font-size: clamp(1.5rem, 3vw, 2rem);
    margin-bottom: var(--space-2);
}
.page-hero .breadcrumb,
.page-hero-subtitle {
    font-size: 0.9375rem;
    opacity: 0.85;
    margin-top: var(--space-2);
}
.page-hero .breadcrumb a { color: #fff; opacity: 0.8; }
.page-hero .breadcrumb a:hover { opacity: 1; }
```

**Step 2: Update breadcrumbs styling**

Replace the `.campusos-breadcrumbs` section:

```css
/* --- Breadcrumbs --- */
.campusos-breadcrumbs {
    background: var(--campusos-bg-alt);
    border-bottom: 1px solid var(--campusos-border);
    padding: 0.625rem 0;
    font-size: 0.8125rem;
}
.breadcrumb-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}
.breadcrumb-list li {
    display: inline-flex;
    align-items: center;
    color: var(--campusos-text-light);
}
.breadcrumb-list li a {
    color: var(--campusos-primary);
    transition: color var(--transition-fast);
}
.breadcrumb-list li a:hover { color: var(--campusos-secondary); }
.breadcrumb-list li:last-child { font-weight: 600; color: var(--campusos-text); }
.breadcrumb-sep {
    margin: 0 0.4rem;
    color: var(--campusos-text-muted);
    font-size: 0.75rem;
}
```

**Step 3: Verify in browser**

Navigate to `/tenaga-pendidik/` or any interior page. The page hero should now have a gradient background with a subtle decorative circle. Breadcrumbs should be cleaner.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): redesign page header banner with gradient and breadcrumbs"
```

---

## Task 8: Homepage Hero Section

**Files:**
- Modify: `wp-content/themes/campusos-academic/template-parts/homepage-hero.php` (fallback hero when no Elementor)

**Step 1: Enhance the fallback hero**

The fallback hero (when Elementor is not used) is too simple. Enhance it:

```php
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<section class="homepage-section homepage-hero">
    <?php
    $hero_page = get_page_by_path( 'homepage-hero' );
    if ( $hero_page && class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->documents->get( $hero_page->ID )->is_built_with_elementor() ) {
        echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $hero_page->ID );
    } else {
    ?>
    <div class="hero-fallback">
        <div class="container">
            <p class="hero-subtitle"><?php echo esc_html( campusos_get_institution_name() ); ?></p>
            <h1 class="hero-title"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></h1>
            <p class="hero-desc"><?php esc_html_e( 'Membangun generasi unggul, kompeten, dan berakhlak mulia untuk kemajuan bangsa.', 'campusos-academic' ); ?></p>
            <div class="hero-actions">
                <a href="<?php echo esc_url( home_url( '/penerimaan/' ) ); ?>" class="btn btn-primary btn-lg"><?php esc_html_e( 'Daftar Sekarang', 'campusos-academic' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>" class="btn btn-outline-white btn-lg"><?php esc_html_e( 'Profil', 'campusos-academic' ); ?></a>
            </div>
        </div>
    </div>
    <?php } ?>
</section>
```

**Step 2: Add hero CSS**

Add to `main.css` after the homepage-section styles:

```css
/* --- Homepage Hero Fallback --- */
.hero-fallback {
    background: linear-gradient(135deg, var(--campusos-primary), var(--campusos-primary-dark));
    color: #fff;
    padding: 6rem 0 5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
    min-height: 60vh;
    display: flex;
    align-items: center;
}
.hero-fallback::before {
    content: '';
    position: absolute;
    top: -200px;
    right: -100px;
    width: 500px;
    height: 500px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 50%;
}
.hero-fallback::after {
    content: '';
    position: absolute;
    bottom: -150px;
    left: -80px;
    width: 350px;
    height: 350px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 50%;
}
.hero-subtitle {
    font-size: 1rem;
    opacity: 0.8;
    margin-bottom: var(--space-3);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 500;
}
.hero-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    color: #fff;
    margin-bottom: var(--space-4);
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.2;
}
.hero-desc {
    font-size: 1.125rem;
    opacity: 0.85;
    max-width: 550px;
    margin: 0 auto var(--space-6);
    line-height: 1.7;
}
.hero-actions {
    display: flex;
    gap: var(--space-3);
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .hero-fallback { padding: 4rem 0 3.5rem; min-height: 50vh; }
    .hero-desc { font-size: 1rem; }
}
```

**Step 3: Verify in browser**

Navigate to homepage. The fallback hero (if Elementor hero is not built) should show a gradient background with centered text and two CTA buttons.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/template-parts/homepage-hero.php wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): redesign homepage hero section with gradient and CTA buttons"
```

---

## Task 9: Homepage Stats Counter Redesign

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (stats-v2 section ~line 1928-1995)

**Step 1: Enhance stats section CSS**

Replace the stats-v2 section with a more impactful design:

```css
/* ============================================
   Homepage: Stats V2 - "Dalam Angka"
   ============================================ */
.homepage-stats-v2 {
    position: relative;
    overflow: hidden;
}
.stats-overlay {
    background: linear-gradient(135deg, rgba(0, 20, 50, 0.92), rgba(0, 40, 85, 0.88));
    padding: var(--space-8) 0;
}
.stats-grid-v2 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-6) var(--space-5);
}
.stats-item-v2 {
    text-align: center;
    padding: var(--space-4) var(--space-2);
    position: relative;
}
/* Vertical dividers */
.stats-item-v2:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 20%;
    height: 60%;
    width: 1px;
    background: rgba(255, 255, 255, 0.15);
}
.stats-icon-circle {
    width: 70px;
    height: 70px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    transition: border-color var(--transition-slow), transform var(--transition-slow), background var(--transition-slow);
}
.stats-item-v2:hover .stats-icon-circle {
    border-color: var(--campusos-secondary);
    background: rgba(255, 255, 255, 0.08);
    transform: scale(1.08);
}
.stats-icon-circle svg {
    width: 28px;
    height: 28px;
}
.stats-number-v2 {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    margin-bottom: var(--space-1);
}
.stats-label-v2 {
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 400;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (max-width: 1024px) {
    .stats-item-v2:nth-child(4n)::after { display: none; }
}
@media (max-width: 768px) {
    .stats-grid-v2 {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-5) var(--space-4);
    }
    .stats-overlay { padding: var(--space-7) 0; }
    .stats-item-v2:nth-child(odd)::after { display: block; }
    .stats-item-v2:nth-child(even)::after { display: none; }
    .stats-icon-circle { width: 56px; height: 56px; }
    .stats-icon-circle svg { width: 24px; height: 24px; }
}
@media (max-width: 480px) {
    .stats-grid-v2 { grid-template-columns: 1fr; }
    .stats-item-v2::after { display: none !important; }
}
```

**Step 2: Verify in browser**

Homepage stats section should now have vertical dividers between items, smoother hover effects, and better responsive behavior.

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): redesign stats counter with dividers and improved hover effects"
```

---

## Task 10: Footer Redesign

**Files:**
- Modify: `wp-content/themes/campusos-academic/footer.php` (entire file)
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (footer section ~line 254-338)

**Step 1: Update footer template**

Replace `footer.php` with enhanced 4-column layout and graceful empty states:

```php
<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<footer id="colophon" class="site-footer">
    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col footer-about">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="footer-logo"><?php the_custom_logo(); ?></div>
                    <?php endif; ?>
                    <h3 class="footer-title"><?php echo esc_html( campusos_get_institution_name() ); ?></h3>
                    <?php if ( $address = get_theme_mod( 'campusos_address' ) ) : ?>
                        <p class="footer-address"><?php echo esc_html( $address ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="footer-col footer-links">
                    <h3 class="footer-title"><?php esc_html_e( 'Tautan', 'campusos-academic' ); ?></h3>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ] );
                    ?>
                </div>

                <div class="footer-col footer-contact">
                    <h3 class="footer-title"><?php esc_html_e( 'Kontak', 'campusos-academic' ); ?></h3>
                    <?php if ( $phone = get_theme_mod( 'campusos_phone' ) ) : ?>
                        <p class="footer-contact-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                            <?php echo esc_html( $phone ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $email = get_theme_mod( 'campusos_email' ) ) : ?>
                        <p class="footer-contact-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                            <?php echo esc_html( $email ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $parent_url = get_theme_mod( 'campusos_parent_url' ) ) : ?>
                        <p class="footer-contact-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <a href="<?php echo esc_url( $parent_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( wp_parse_url( $parent_url, PHP_URL_HOST ) ); ?></a>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="footer-col footer-social">
                    <h3 class="footer-title"><?php esc_html_e( 'Media Sosial', 'campusos-academic' ); ?></h3>
                    <div class="social-links">
                        <?php
                        $socials = [
                            'facebook'  => [ 'Facebook',  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' ],
                            'instagram' => [ 'Instagram', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>' ],
                            'youtube'   => [ 'YouTube',   '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>' ],
                            'twitter'   => [ 'Twitter',   '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' ],
                            'tiktok'    => [ 'TikTok',    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>' ],
                        ];
                        foreach ( $socials as $key => $data ) :
                            $url = get_theme_mod( "campusos_social_{$key}" );
                            if ( $url ) :
                        ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php echo esc_attr( $data[0] ); ?>"><?php echo $data[1]; ?></a>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p><?php echo wp_kses_post( get_theme_mod( 'campusos_footer_text', '&copy; ' . gmdate('Y') . ' ' . campusos_get_institution_name() ) ); ?></p>
                <p class="footer-powered"><?php esc_html_e( 'Powered by CampusOS Academic', 'campusos-academic' ); ?></p>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
```

**Step 2: Update footer CSS**

Replace the footer CSS section:

```css
/* --- Footer --- */
.footer-main {
    background: linear-gradient(180deg, #002855, #001a3d);
    color: #fff;
    padding: var(--space-8) 0 var(--space-7);
}
.footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr;
    gap: var(--space-6);
}
.footer-logo { margin-bottom: var(--space-3); }
.footer-logo img { height: 40px; width: auto; filter: brightness(0) invert(1); }
.footer-title {
    font-size: 1rem;
    margin-bottom: var(--space-4);
    color: #fff;
    position: relative;
    padding-bottom: var(--space-3);
}
.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: var(--campusos-secondary);
    border-radius: 2px;
}
.footer-address {
    font-size: 0.875rem;
    margin-bottom: var(--space-2);
    opacity: 0.8;
    line-height: 1.7;
}
.footer-contact-item {
    font-size: 0.875rem;
    margin-bottom: var(--space-2);
    opacity: 0.8;
    display: flex;
    align-items: center;
    gap: var(--space-2);
}
.footer-contact-item svg { flex-shrink: 0; opacity: 0.7; }
.footer-contact-item a { color: #fff; opacity: 1; }
.footer-contact-item a:hover { color: var(--campusos-secondary); }
.footer-menu li a {
    display: inline-block;
    color: #fff;
    opacity: 0.8;
    font-size: 0.875rem;
    padding: var(--space-1) 0;
    transition: opacity var(--transition-fast), transform var(--transition-fast);
}
.footer-menu li a:hover {
    opacity: 1;
    color: #fff;
    transform: translateX(4px);
}
/* Social links - keep existing styles */

.footer-bottom {
    background: rgba(0, 0, 0, 0.2);
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    padding: var(--space-4) 0;
}
.footer-bottom-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: var(--space-2);
}
.footer-bottom p {
    font-size: 0.8125rem;
    color: rgba(255, 255, 255, 0.5);
    margin: 0;
}
.footer-powered {
    font-size: 0.75rem !important;
    opacity: 0.6;
}

/* Footer responsive */
@media (max-width: 1024px) {
    .footer-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .footer-grid { grid-template-columns: 1fr; gap: var(--space-5); }
    .footer-bottom-inner { justify-content: center; text-align: center; flex-direction: column; }
}
```

**Step 3: Verify in browser**

Footer should now have 4 columns, gradient background, secondary-colored underlines on titles, and a bottom bar with "Powered by CampusOS Academic".

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/footer.php wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): redesign footer with 4-column layout and gradient background"
```

---

## Task 11: Enhanced 404 Page

**Files:**
- Modify: `wp-content/themes/campusos-academic/404.php`
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (add 404 styles)

**Step 1: Replace 404.php**

```php
<?php get_header(); ?>
<div class="page-hero">
    <div class="container">
        <h1><?php esc_html_e( '404 - Halaman Tidak Ditemukan', 'campusos-academic' ); ?></h1>
    </div>
</div>
<main id="primary" class="site-main">
    <div class="container">
        <div class="error-404-content">
            <div class="error-404-number">404</div>
            <h2><?php esc_html_e( 'Halaman yang Anda cari tidak ditemukan', 'campusos-academic' ); ?></h2>
            <p><?php esc_html_e( 'Mungkin halaman telah dipindahkan atau alamat yang Anda masukkan salah. Silakan coba pencarian atau kunjungi halaman lain.', 'campusos-academic' ); ?></p>
            <form role="search" method="get" class="error-404-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" name="s" placeholder="<?php esc_attr_e( 'Cari di situs ini...', 'campusos-academic' ); ?>" />
                <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Cari', 'campusos-academic' ); ?></button>
            </form>
            <div class="error-404-links">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Beranda', 'campusos-academic' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/pengumuman/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Pengumuman', 'campusos-academic' ); ?></a>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>
```

**Step 2: Add 404 CSS**

```css
/* --- 404 Page --- */
.error-404-content {
    text-align: center;
    padding: var(--space-8) 0;
    max-width: 560px;
    margin: 0 auto;
}
.error-404-number {
    font-size: clamp(5rem, 15vw, 10rem);
    font-weight: 800;
    line-height: 1;
    background: linear-gradient(135deg, var(--campusos-primary), var(--campusos-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: var(--space-4);
}
.error-404-content h2 {
    margin-bottom: var(--space-3);
}
.error-404-content > p {
    color: var(--campusos-text-light);
    margin-bottom: var(--space-6);
    line-height: 1.7;
}
.error-404-search {
    display: flex;
    gap: var(--space-2);
    margin-bottom: var(--space-5);
    max-width: 420px;
    margin-left: auto;
    margin-right: auto;
}
.error-404-search input[type="search"] {
    flex: 1;
}
.error-404-links {
    display: flex;
    gap: var(--space-3);
    justify-content: center;
    flex-wrap: wrap;
}
```

**Step 3: Verify in browser**

Navigate to `http://wp-unpatti.local/nonexistent-page/`. The 404 should show a large gradient "404" number, search form, and navigation links.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/404.php wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): redesign 404 page with gradient number, search form and navigation"
```

---

## Task 12: Homepage Section Spacing & FAQ Polish

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (homepage-section and FAQ sections)

**Step 1: Improve homepage section spacing**

Update the `.homepage-section` base styles:

```css
/* --- Homepage Sections --- */
.homepage-section {
    padding: var(--space-8) 0;
}
.homepage-section:nth-child(even):not(.homepage-hero):not(.homepage-stats-v2):not(.homepage-staff) {
    background: var(--campusos-bg-alt);
}
.homepage-section h2 {
    margin-bottom: var(--space-5);
}
```

**Step 2: Polish FAQ accordion**

Update the FAQ styles for smoother animation:

```css
.faq-accordion {
    border: 1px solid var(--campusos-border);
    border-radius: var(--radius-md);
    overflow: hidden;
}
.faq-item {
    border-bottom: 1px solid var(--campusos-border);
}
.faq-item:last-child { border-bottom: none; }
.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: var(--space-4) var(--space-5);
    background: var(--campusos-surface);
    border: none;
    cursor: pointer;
    text-align: left;
    font-size: 0.9375rem;
    font-weight: 500;
    transition: background var(--transition-fast);
    color: var(--campusos-text);
}
.faq-question:hover { background: var(--campusos-bg-alt); }
.faq-question[aria-expanded="true"] {
    background: var(--campusos-bg-alt);
    color: var(--campusos-primary);
}
.faq-toggle img,
.faq-toggle .dashicons {
    transition: transform var(--transition-normal);
}
.faq-question[aria-expanded="true"] .faq-toggle img,
.faq-question[aria-expanded="true"] .faq-toggle .dashicons {
    transform: rotate(180deg);
}
.faq-answer-content {
    padding: 0 var(--space-5) var(--space-5);
    font-size: 0.9375rem;
    line-height: 1.7;
    color: var(--campusos-text-secondary);
}
```

**Step 3: Verify in browser**

Homepage sections should have alternating backgrounds. FAQ accordion should have smoother interactions and highlighted active question.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): improve homepage section spacing and polish FAQ accordion"
```

---

## Task 13: JS — Scroll Fade-in Animations

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/js/main.js` (add scroll animation observer)
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (add animation classes)

**Step 1: Add CSS animation classes**

Add to `main.css`:

```css
/* --- Scroll Animations --- */
.fade-in-up {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-in-up.is-visible {
    opacity: 1;
    transform: translateY(0);
}
@media (prefers-reduced-motion: reduce) {
    .fade-in-up { opacity: 1; transform: none; }
}
```

**Step 2: Add IntersectionObserver for scroll animations**

Add to end of `main.js` (inside the IIFE):

```js
// Scroll fade-in animation
function initScrollAnimations() {
    var elements = document.querySelectorAll('.homepage-section > .container, .profile-grid, .posts-grid, .error-404-content');
    if (!elements.length || !('IntersectionObserver' in window)) return;

    elements.forEach(function(el) { el.classList.add('fade-in-up'); });

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    elements.forEach(function(el) { observer.observe(el); });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollAnimations);
} else {
    initScrollAnimations();
}
```

**Step 3: Verify in browser**

Scroll the homepage — sections should fade in as they enter the viewport. Verify that `prefers-reduced-motion` disables the animations.

**Step 4: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/js/main.js wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "feat(theme): add scroll fade-in animations with reduced-motion support"
```

---

## Task 14: Final Visual Review & Cleanup

**Files:**
- Modify: `wp-content/themes/campusos-academic/assets/css/main.css` (any remaining cleanup)

**Step 1: Full visual review**

Use Playwright or browser to verify ALL the following pages:
1. `http://wp-unpatti.local/` — Homepage (all sections)
2. `http://wp-unpatti.local/tenaga-pendidik/` — Profile grid
3. `http://wp-unpatti.local/nonexistent/` — 404 page
4. `http://wp-unpatti.local/sambutan/` — Interior page with content
5. Any archive page (berita, agenda, beasiswa)

Check at 3 viewport widths:
- 1280px (desktop)
- 768px (tablet)
- 375px (mobile)

Check dark mode via DevTools emulation.

**Step 2: Fix any visual issues found**

Address spacing inconsistencies, color issues, or responsive breakpoints that need adjustment.

**Step 3: Commit**

```bash
git add wp-content/themes/campusos-academic/assets/css/main.css
git commit -m "fix(theme): visual review cleanup and responsive fixes"
```

---

## Task 15: Bump Version & Update style.css

**Files:**
- Modify: `wp-content/themes/campusos-academic/style.css` (version bump)
- Modify: `wp-content/themes/campusos-academic/functions.php:4` (version constant)

**Step 1: Bump version to 1.2.0**

In `style.css`, update `Version: 1.1.0` → `Version: 1.2.0`

In `functions.php`, update `define( 'CAMPUSOS_THEME_VERSION', '1.1.0' )` → `define( 'CAMPUSOS_THEME_VERSION', '1.2.0' )`

**Step 2: Commit**

```bash
git add wp-content/themes/campusos-academic/style.css wp-content/themes/campusos-academic/functions.php
git commit -m "chore(theme): bump version to 1.2.0 for frontend visual overhaul"
```

---

## Summary

| Task | Description | Files Modified |
|------|-------------|----------------|
| 1 | Design tokens (CSS variables, spacing, shadows) | main.css, functions.php |
| 2 | Forms, focus states, reduced-motion, print | main.css |
| 3 | Dark mode | main.css |
| 4 | Desktop nav, sticky header, dropdown animations | main.css, main.js |
| 5 | Button & badge system upgrade | main.css |
| 6 | Card system with hover animations | main.css |
| 7 | Page header banner & breadcrumbs | main.css |
| 8 | Homepage hero section | homepage-hero.php, main.css |
| 9 | Stats counter redesign | main.css |
| 10 | Footer 4-column redesign | footer.php, main.css |
| 11 | Enhanced 404 page | 404.php, main.css |
| 12 | Homepage spacing & FAQ polish | main.css |
| 13 | Scroll fade-in animations | main.js, main.css |
| 14 | Final visual review & cleanup | main.css |
| 15 | Version bump to 1.2.0 | style.css, functions.php |
