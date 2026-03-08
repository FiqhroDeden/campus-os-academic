# CampusOS Academic - Finalization & Distribution Design

**Date:** 2026-03-08
**Status:** Approved

## Overview

Finalize CampusOS Academic theme + plugin for commercial distribution to Indonesian universities (Fakultas/Program Studi). Four workstreams: optimization, licensing, packaging, documentation.

## Decisions

- **Distribution model:** Fully paid (license key required)
- **License server:** Separate plain PHP server (not Laravel)
- **Expiry behavior:** Theme keeps working, auto-updates stop
- **Documentation language:** Bahasa Indonesia
- **Build approach:** Shell script, no node/npm dependency

---

## 1. Optimization

### 1.1 CSS & JS Minification
- PHP-based minifier in `build.sh` (no npm required)
- CSS: Strip comments, whitespace → target ~50KB from 72KB
- JS: Strip comments, whitespace → target ~5KB from 8.7KB
- Output: `main.min.css` and `main.min.js`
- `functions.php`: enqueue `.min` files when `WP_DEBUG` is false

### 1.2 Asset Loading
- Add `preconnect` hint for Google Fonts
- Add `defer` attribute to script enqueue
- Extend lazy loading to all image-displaying templates (gallery, partner, staff, etc.)

### 1.3 Version Sync
- Bump plugin from 1.1.0 → 1.2.2 to match theme
- Build script ensures versions stay in sync across all files

### 1.4 Distribution Cleanup
- Exclude from ZIP: `.git/`, `CLAUDE.md`, `docs/plans/`, `agent-skills/`, dev screenshots, `build.sh`

---

## 2. License System

### 2.1 License Server (Plain PHP, standalone)

**Structure:**
```
license-server/
├── index.php          # Router
├── config.php         # Database config & secret key
├── db.php             # Database helper (MySQL)
├── api/
│   ├── activate.php   # POST: Activate license
│   ├── validate.php   # POST: Check license status
│   ├── deactivate.php # POST: Deactivate (allow domain move)
│   └── check-update.php # GET: Check latest version
├── admin/
│   ├── index.php      # Simple dashboard
│   ├── login.php      # Admin login
│   └── licenses.php   # CRUD licenses
├── schema.sql         # Database schema
└── .htaccess          # Security & routing
```

**Database:**
```sql
licenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  license_key VARCHAR(64) UNIQUE NOT NULL,
  customer_email VARCHAR(255),
  customer_name VARCHAR(255),
  product_type ENUM('theme','bundle') DEFAULT 'bundle',
  max_activations INT DEFAULT 1,
  activated_domain VARCHAR(255),
  activated_at DATETIME,
  expires_at DATETIME,
  status ENUM('active','expired','revoked') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

**API Endpoints:**
1. `POST /api/activate` — Validate key, bind domain, set expires_at = now + 1 year
2. `POST /api/validate` — Return valid/expired/invalid + expiry date
3. `POST /api/deactivate` — Clear domain binding
4. `GET /api/check-update` — Return latest version + download URL if licensed

**Security:** HMAC signature per request, rate limiting, IP logging.

### 2.2 WordPress Client (Plugin Side)

- New "Lisensi" tab in Settings (first position)
- License Key input + Activate/Deactivate buttons
- Status display: Active (until date), Expired, Not Activated
- Admin notice if not activated or expired
- Weekly cron re-validation
- Updater checks license before serving updates

**Flow:**
1. Install theme + plugin
2. Settings → Lisensi → Enter key → Activate
3. Plugin calls `/api/activate` with key + domain
4. If valid: store status + expiry in `campusos_settings['license']`
5. Auto-update active while license valid
6. After expiry: theme works, admin notice shows, updates stop

---

## 3. Packaging

### 3.1 Build Script (`build.sh`)

Single command: `./build.sh [version]`

Steps:
1. Sync version across: `style.css`, `functions.php`, `campusos-academic-core.php`
2. Minify CSS → `main.min.css`
3. Minify JS → `main.min.js`
4. Copy theme to temp dir (excluding dev files)
5. Copy plugin to temp dir (excluding dev files)
6. Generate ZIPs in `dist/`:
   - `campusos-academic-theme-v{version}.zip`
   - `campusos-academic-core-v{version}.zip`
7. Cleanup temp

### 3.2 Excluded Files
- `.git/`, `.gitignore`, `CLAUDE.md`, `docs/plans/`
- Dev screenshots (`*.png` in root)
- `agent-skills/`, `build.sh`, `.claude/`

### 3.3 ZIP Structure

**Theme:**
```
campusos-academic/
├── style.css, functions.php, header.php, footer.php, 404.php, ...
├── templates/, template-parts/, inc/
├── assets/css/main.css + main.min.css
├── assets/js/main.js + main.min.js
├── screenshot.png
└── readme.txt
```

**Plugin:**
```
campusos-academic-core/
├── campusos-academic-core.php
├── includes/
└── languages/
```

---

## 4. Documentation (HTML)

### 4.1 Structure
Single-page HTML: `docs/panduan-penggunaan.html`

Sections:
1. Pendahuluan
2. Persyaratan Sistem
3. Instalasi
4. Konfigurasi Awal (Setup Wizard, Customizer)
5. Manajemen Konten (per CPT)
6. Halaman Statis (meta box pages)
7. Shortcodes (all 18)
8. Elementor Widgets (all 11)
9. Pengaturan Keamanan
10. SSO
11. API Integration (SIAKAD & SIGAP)
12. Export/Import
13. Troubleshooting

### 4.2 Screenshots
Captured via Playwright from live admin panel:
- Dashboard, Settings tabs, CPT editors, Customizer, Frontend pages

### 4.3 Design
- Modern single-page layout with sticky sidebar navigation
- Responsive
- Images in `docs/images/`
- Self-contained CSS

---

## Implementation Order

1. Optimization (CSS/JS minify, lazy loading, version sync)
2. License system - WordPress client side
3. License server (standalone PHP)
4. Build script + ZIP packaging
5. Documentation HTML + screenshots
6. Final testing & ZIP generation
