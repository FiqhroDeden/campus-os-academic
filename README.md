# CampusOS Academic

WordPress theme and plugin system for Indonesian higher education institutions (Fakultas & Program Studi websites).

## Overview

CampusOS Academic provides a complete website solution for university faculties and study programs. It consists of two main components:

- **CampusOS Academic Theme** — Presentation layer with 28 page templates, Elementor widgets, and a setup wizard
- **CampusOS Academic Core Plugin** — Business logic including 18 custom post types, meta boxes, security hardening, SSO, and API integrations

## Requirements

- WordPress 6.0+ (tested up to 6.9)
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+

## Installation

1. Upload the `campusos-academic` theme to `wp-content/themes/`
2. Upload the `campusos-academic-core` plugin to `wp-content/plugins/`
3. Activate the plugin, then the theme
4. Follow the setup wizard to configure your site

## Project Structure

```
wp-content/
├── themes/campusos-academic/           # Theme (presentation layer)
│   ├── templates/                      # 28 page templates
│   ├── template-parts/                 # Reusable partials
│   ├── inc/
│   │   ├── elementor/widgets/          # 11 custom Elementor widgets
│   │   ├── customizer/                 # Theme Customizer settings
│   │   └── setup-wizard/              # Initial setup wizard
│   ├── assets/css/main.css            # Styles (CSS custom properties)
│   └── assets/js/main.js             # Frontend JS
│
├── plugins/campusos-academic-core/     # Plugin (business logic)
│   └── includes/
│       ├── cpt/                        # 18 Custom Post Types
│       ├── admin/meta-boxes/           # Meta box classes
│       ├── security/                   # Hardening, scanner, audit log
│       ├── sso/                        # OAuth2 SSO (Laravel Passport)
│       ├── integrations/               # SIAKAD/SIGAP API connectors
│       ├── export-import/              # JSON export/import
│       └── updater/                    # Auto-update system
```

## Custom Post Types

| Post Type | Description |
|-----------|-------------|
| `agenda` | Events and schedules |
| `beasiswa` | Scholarships |
| `dokumen` | Documents |
| `faq` | Frequently asked questions |
| `fasilitas` | Facilities |
| `galeri` | Photo galleries |
| `kerjasama` | Partnerships |
| `mata_kuliah` | Courses / subjects |
| `mitra_industri` | Industry partners |
| `organisasi_mhs` | Student organizations |
| `pengumuman` | Announcements |
| `pimpinan` | Leadership / officials |
| `prestasi` | Achievements |
| `publikasi` | Publications |
| `tenaga_pendidik` | Teaching staff |
| `testimonial` | Testimonials |
| `video` | Videos |

## Features

- **Page Templates** — 28 pre-built templates for common academic pages (homepage, accreditation, curriculum, admissions, etc.)
- **Elementor Widgets** — 11 custom widgets for academic content
- **Security** — Login rate limiting, XML-RPC disabled, security headers, file integrity monitoring, content scanning, activity log
- **SSO** — OAuth2 authentication with Laravel Passport
- **API Integrations** — SIAKAD and SIGAP connectors for academic data
- **Export/Import** — Full site content export and import via JSON
- **Auto-Update** — Built-in updater for both theme and plugin
- **Setup Wizard** — Guided initial configuration
- **Responsive Design** — Mobile-friendly layout

## Tech Stack

- Pure PHP, vanilla CSS (CSS custom properties), vanilla JS
- No build tools or bundlers required
- Elementor page builder integration (optional)

## Development

### Local Setup

This project uses [Local by Flywheel](https://localwp.com/) for development.

### Conventions

- **Text domain:** `campusos-academic`
- **Option prefix:** `campusos_`
- **DB table prefix:** `wp_campusos_`
- **UI language:** Indonesian
- **Code language:** English
- **Commit style:** Conventional commits (`feat:`, `fix:`, `refactor:`, `docs:`)

### Adding Components

- **New CPT:** Create `class-cpt-{name}.php` extending `CPT_Base` in `plugins/campusos-academic-core/includes/cpt/`, register in `class-plugin.php`
- **New template:** Create `templates/template-{name}.php` in theme with `Template Name` header
- **New Elementor widget:** Create widget class in `themes/campusos-academic/inc/elementor/widgets/`, register in `elementor-init.php`
- **New meta box:** Create `class-mb-{name}.php` extending `MB_Base` in `plugins/campusos-academic-core/includes/admin/meta-boxes/`

## License

GPL-2.0-or-later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) for details.
