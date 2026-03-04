# CampusOS Academic - WordPress Project

## Overview

WordPress theme + plugin system for Indonesian higher education institutions (Fakultas/Program Studi websites). Originally "UNPATTI Academic", rebranded to "CampusOS Academic" v1.1.0.

## Project Structure

```
wp-content/
├── themes/campusos-academic/       # Custom theme (presentation layer)
│   ├── functions.php               # Theme setup, enqueues, menus
│   ├── theme.json                  # Block editor config
│   ├── templates/                  # 28 page templates (template-*.php)
│   ├── template-parts/             # Reusable partials (homepage-*.php)
│   ├── inc/
│   │   ├── elementor/widgets/      # 11 custom Elementor widgets
│   │   ├── customizer/             # Theme Customizer settings
│   │   ├── setup-wizard/           # Initial setup wizard
│   │   ├── breadcrumbs.php
│   │   ├── social-share.php
│   │   └── template-functions.php
│   ├── assets/css/main.css         # Main stylesheet
│   └── assets/js/main.js           # Frontend JS
│
├── plugins/campusos-academic-core/ # Custom plugin (business logic)
│   ├── campusos-academic-core.php  # Main plugin file + migration
│   └── includes/
│       ├── class-plugin.php        # Singleton entry point
│       ├── cpt/                    # 18 Custom Post Types (class-cpt-*.php)
│       ├── admin/                  # Settings, meta boxes, page updater
│       │   └── meta-boxes/         # 10+ meta box classes
│       ├── security/               # Hardening, scanner, integrity, audit log
│       ├── sso/                    # OAuth2 SSO (Laravel Passport)
│       ├── integrations/           # SIAKAD/SIGAP API connectors
│       ├── export-import/          # JSON export/import
│       ├── frontend/               # Shortcodes
│       └── updater/                # Auto-update for theme + plugin
```

## Tech Stack

- **WordPress:** 6.9 / PHP 8.0+
- **Theme:** Pure PHP, vanilla CSS (CSS variables), vanilla JS
- **Page Builder:** Elementor integration with custom widgets
- **Build Tools:** None — plain CSS/JS, no bundler
- **Database:** MySQL (local dev: root/root, db: `local`, prefix: `wp_`)
- **Environment:** Local by Flywheel (development)

## Architecture

- **Theme** handles presentation only (templates, styles, Customizer)
- **Plugin** handles all business logic (CPTs, meta boxes, security, APIs, SSO)
- CPT base class pattern: `class-cpt-base.php` → individual CPT classes
- Meta box base class pattern: `class-mb-base.php` → individual meta box classes
- Singleton pattern for main Plugin class

## Custom Post Types (18)

agenda, beasiswa, dokumen, faq, fasilitas, galeri, kerjasama, mata_kuliah, mitra_industri, organisasi_mhs, pengumuman, pimpinan, prestasi, publikasi, tenaga_pendidik, testimonial, video

## Key Conventions

- **Text domain:** `campusos-academic` (used for both theme and plugin)
- **Option prefix:** `campusos_` (settings, file hashes, scan data)
- **DB table prefix:** `wp_campusos_` (activity_log)
- **Language:** Indonesian UI labels, English code/comments
- **CSS:** Use CSS custom properties defined in `main.css` and `theme.json`
- **PHP:** WordPress coding standards, class-based architecture
- **Naming:** CPT files use `class-cpt-{name}.php`, meta boxes use `class-mb-{name}.php`
- **Templates:** Page templates in `templates/template-{name}.php`, CPT archives in `archive-{cpt}.php`, singles in `single-{cpt}.php`

## Git

- **Main branch:** `main`
- **Current branch:** `master`
- **Commit style:** Conventional commits (`feat:`, `fix:`, `refactor:`, `docs:`)

## Common Tasks

- **Add a new CPT:** Create `class-cpt-{name}.php` extending `CPT_Base` in plugin's `cpt/` dir, register in `class-plugin.php`
- **Add a page template:** Create `templates/template-{name}.php` in theme, add Template Name header comment
- **Add an Elementor widget:** Create widget class in `inc/elementor/widgets/`, register in `elementor-init.php`
- **Add a meta box:** Create `class-mb-{name}.php` extending `MB_Base` in plugin's `admin/meta-boxes/`
- **Modify theme appearance:** Edit CSS variables in `assets/css/main.css` or Customizer settings in `inc/customizer/`

## Security Notes

- Plugin includes security hardening (XML-RPC disabled, login rate limiting, security headers)
- SSO via OAuth2 with Laravel Passport — configurable in plugin settings
- File integrity monitoring and content scanning built in
- Never commit real credentials — `wp-config.php` uses local dev defaults
