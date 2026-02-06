# Desain Tema WordPress CampusOS Academic

**Tanggal:** 2026-01-28
**Proyek:** Tema WordPress untuk Fakultas & Program Studi Perguruan Tinggi
**Scope:** 11 Fakultas + 104 Program Studi (115 website)

---

## 1. Ringkasan Proyek

Membangun satu tema WordPress custom (`campusos-academic`) beserta companion plugin (`campusos-academic-core`) yang dapat digunakan untuk semua website fakultas dan program studi di Perguruan Tinggi. Menggantikan 115 lisensi tema berbayar (@ Rp 250.000/tahun).

### Keputusan Arsitektur

| Keputusan | Pilihan |
|-----------|---------|
| Arsitektur WP | Single-site terpisah per fakultas/prodi |
| Distribusi update | Auto-update via server internal CampusOS |
| SSO scope | Admin panel saja (frontend publik) |
| API SIAKAD/SIGAP | Framework disiapkan, integrasi menyusul |
| Frontend builder | Elementor |
| Tema vs Plugin | Data/logika di plugin, tampilan di tema |

---

## 2. Arsitektur & Struktur Proyek

```
wp-content/
├── themes/campusos-academic/              # TEMA
│   ├── style.css
│   ├── functions.php
│   ├── theme.json                        # Design tokens, color system
│   ├── inc/
│   │   ├── customizer/                   # WordPress Customizer settings
│   │   ├── elementor/                    # Custom Elementor widgets
│   │   │   ├── widgets/
│   │   │   │   ├── hero-slider.php
│   │   │   │   ├── stats-counter.php
│   │   │   │   ├── team-grid.php
│   │   │   │   ├── news-grid.php
│   │   │   │   ├── announcement-list.php
│   │   │   │   ├── agenda-calendar.php
│   │   │   │   ├── faq-accordion.php
│   │   │   │   ├── partner-logos.php
│   │   │   │   ├── gallery-grid.php
│   │   │   │   ├── prestasi-carousel.php
│   │   │   │   └── why-choose-us.php
│   │   │   └── elementor-init.php
│   │   ├── setup-wizard/                 # First-time setup wizard
│   │   └── template-functions.php
│   ├── templates/                        # Page templates
│   │   ├── template-sejarah.php
│   │   ├── template-visi-misi.php
│   │   ├── template-struktur-organisasi.php
│   │   ├── template-pimpinan.php
│   │   ├── template-tenaga-pendidik.php
│   │   ├── template-kerjasama.php
│   │   ├── template-fasilitas.php
│   │   ├── template-akreditasi.php
│   │   ├── template-dokumen.php
│   │   ├── template-statistik.php
│   │   └── template-fullwidth.php
│   ├── template-parts/
│   │   ├── header/
│   │   ├── footer/
│   │   ├── content/
│   │   └── sidebar/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   ├── images/
│   │   └── fonts/
│   └── languages/
│
└── plugins/campusos-academic-core/        # COMPANION PLUGIN
    ├── campusos-academic-core.php
    ├── includes/
    │   ├── class-plugin.php              # Main plugin class
    │   ├── cpt/                          # Custom Post Types
    │   │   ├── class-cpt-pimpinan.php
    │   │   ├── class-cpt-tenaga-pendidik.php
    │   │   ├── class-cpt-kerjasama.php
    │   │   ├── class-cpt-fasilitas.php
    │   │   ├── class-cpt-prestasi.php
    │   │   ├── class-cpt-dokumen.php
    │   │   ├── class-cpt-agenda.php
    │   │   ├── class-cpt-faq.php
    │   │   ├── class-cpt-mata-kuliah.php
    │   │   ├── class-cpt-organisasi-mhs.php
    │   │   ├── class-cpt-mitra-industri.php
    │   │   ├── class-cpt-publikasi.php
    │   │   ├── class-cpt-beasiswa.php
    │   │   └── class-cpt-galeri.php
    │   ├── admin/                        # Admin CRUD interfaces
    │   │   ├── class-admin-settings.php  # Site mode, colors, general
    │   │   └── meta-boxes/              # Structured page meta boxes
    │   │       ├── class-mb-sejarah.php
    │   │       ├── class-mb-visi-misi.php
    │   │       ├── class-mb-struktur-org.php
    │   │       ├── class-mb-sambutan.php
    │   │       ├── class-mb-akreditasi.php
    │   │       ├── class-mb-cpl.php
    │   │       ├── class-mb-penerimaan.php
    │   │       ├── class-mb-biaya-ukt.php
    │   │       ├── class-mb-statistik.php
    │   │       └── class-mb-tracer-study.php
    │   ├── security/                     # Security hardening
    │   │   ├── class-hardening.php
    │   │   ├── class-content-scanner.php
    │   │   ├── class-file-integrity.php
    │   │   └── class-activity-log.php
    │   ├── sso/                          # SSO OAuth2 integration
    │   │   └── class-sso-auth.php
    │   ├── integrations/                 # API framework (future)
    │   │   ├── class-api-connector.php
    │   │   ├── class-siakad-connector.php
    │   │   └── class-sigap-connector.php
    │   ├── updater/                      # Auto-update system
    │   │   └── class-theme-updater.php
    │   └── export-import/                # Site template export/import
    │       ├── class-exporter.php
    │       └── class-importer.php
    └── languages/
```

---

## 3. Custom Post Types & CRUD

### 3.1 Custom Post Types

| # | CPT Slug | Label | Fields |
|---|----------|-------|--------|
| 1 | `pimpinan` | Pimpinan | Nama, foto, jabatan, NIP, email, periode, bio singkat, urutan tampil |
| 2 | `tenaga_pendidik` | Tenaga Pendidik | Nama, foto, NIDN/NIP, jabatan fungsional, sertifikasi profesional, bidang keahlian, pendidikan terakhir, email, link profil external |
| 3 | `kerjasama` | Kerjasama | Nama mitra, logo mitra, jenis (DN/LN), deskripsi, tanggal mulai/akhir, dokumen MoU (upload) |
| 4 | `fasilitas` | Fasilitas | Nama, foto gallery (multiple), deskripsi, kapasitas, lokasi |
| 5 | `prestasi` | Prestasi | Judul, deskripsi, tanggal, foto, kategori (mahasiswa/dosen), tingkat (lokal/nasional/internasional), nama peraih |
| 6 | `dokumen` | Dokumen | Judul, file upload, kategori (peraturan/kalender/kurikulum/SOP/mutu/akreditasi), tahun, sumber (universitas/fakultas/jurusan) |
| 7 | `agenda` | Agenda | Judul, tanggal mulai/akhir, lokasi, deskripsi, poster image |
| 8 | `faq` | FAQ | Pertanyaan, jawaban, urutan tampil, kategori |
| 9 | `mata_kuliah` | Mata Kuliah | Nama, kode, SKS, semester, konsentrasi, link RPS, link silabus |
| 10 | `organisasi_mhs` | Organisasi Mahasiswa | Nama, logo, struktur organisasi (gambar), tupoksi, program kerja |
| 11 | `mitra_industri` | Mitra DU/DI | Nama perusahaan, logo, jenis kerjasama, deskripsi |
| 12 | `publikasi` | Publikasi | Judul, penulis, jenis (jurnal/prosiding/buku/HKI), tahun, link, DOI, kategori (dosen/mahasiswa) |
| 13 | `beasiswa` | Beasiswa | Nama, deskripsi, persyaratan, deadline, link pendaftaran |
| 14 | `galeri` | Galeri | Judul, foto gallery (multiple), kategori, tanggal |

### 3.2 Halaman Statis dengan Meta Boxes

| # | Page Template | Structured Fields |
|---|---------------|-------------------|
| 1 | Sejarah | Rich text + timeline entries (tahun, event) repeater |
| 2 | Visi Misi Tujuan Sasaran | 4 textarea terpisah: visi, misi (list), tujuan (list), sasaran (list) |
| 3 | Struktur Organisasi | Upload bagan gambar + deskripsi text |
| 4 | Sambutan Pimpinan | Foto, nama, jabatan, isi sambutan (rich text) |
| 5 | Akreditasi | Status, nomor SK, tanggal, upload sertifikat PDF, deskripsi instrumen |
| 6 | CPL | 4 group repeater: sikap, pengetahuan, keterampilan umum, keterampilan khusus |
| 7 | Penerimaan Mahasiswa | Repeater jalur penerimaan (nama, persyaratan, link), biaya pendaftaran |
| 8 | Biaya Pendidikan/UKT | Tabel UKT per kategori (repeater: kategori, nominal) |
| 9 | Statistik | Angka-angka: mahasiswa aktif, lulusan, dosen, beasiswa (input manual, atau auto-count dari CPT) |
| 10 | Tracer Study | Link survey (repeater), upload dokumen hasil, statistik alumni |

### 3.3 CRUD Admin Interface

Setiap CPT menggunakan WordPress native admin UI:
- List table dengan search, filter by taxonomy/kategori, dan sortable columns
- Bulk actions: publish, draft, delete
- Add/Edit form dengan validasi fields
- Image/file upload via WordPress media library
- Drag-and-drop ordering untuk CPT yang butuh urutan (pimpinan, faq, fasilitas)
- Semua fields menggunakan sanitize/escape yang proper

---

## 4. UI/UX & Theming System

### 4.1 Design Principles

- Clean, modern, professional
- Tidak menggunakan AI slop design (no purple gradients, no blob shapes, no glassmorphism)
- Tipografi: Inter (body) + system font fallback
- Whitespace yang cukup, 12-column grid
- Warna solid dan flat
- Mobile-first responsive design

### 4.2 Color System

Admin mengatur 2 warna via WordPress Customizer:

```css
:root {
  --campusos-primary: #003d82;        /* Default: biru CampusOS */
  --campusos-secondary: #e67e22;      /* Default: orange */
  --campusos-primary-light: [auto];   /* 10% lighter */
  --campusos-primary-dark: [auto];    /* 10% darker */
  --campusos-secondary-light: [auto];
  --campusos-secondary-dark: [auto];
  --campusos-text: #1a1a1a;
  --campusos-text-light: #6b7280;
  --campusos-bg: #ffffff;
  --campusos-bg-alt: #f9fafb;
  --campusos-border: #e5e7eb;
}
```

Penggunaan:
- **Primary**: Header background, nav active, headings, primary buttons, links
- **Secondary**: Accent, hover states, badges, highlights, CTA buttons
- **Neutral**: Auto-generated dari primary untuk gray tones

### 4.3 Site Mode

Setting di admin: **Fakultas** atau **Program Studi**

| Aspek | Fakultas | Program Studi |
|-------|----------|---------------|
| Header | Logo + nama fakultas | Logo + nama prodi |
| Navigation | Tambahan menu "Jurusan/Program Studi" | Tanpa menu jurusan |
| Homepage | Highlight semua prodi di fakultas | Fokus satu prodi |
| Pimpinan | Dekan, Wakil Dekan I/II/III | Ketua Prodi, Sekretaris |
| Sidebar | Link ke semua prodi | Link ke fakultas induk |

### 4.4 Responsive Breakpoints

- Mobile: < 480px
- Tablet portrait: 480px - 768px
- Tablet landscape: 768px - 1024px
- Desktop: 1024px - 1280px
- Wide: > 1280px

Mega menu → hamburger menu di < 1024px.

---

## 5. Elementor Integration

### 5.1 Custom Elementor Widgets

| Widget | Fungsi | Data Source |
|--------|--------|-------------|
| Hero Slider | Carousel dengan overlay text, CTA button | Manual input atau CPT slider |
| Stats Counter | Animated counter numbers | Manual atau auto dari CPT/meta |
| Team Grid | Grid card tenaga pendidik | CPT `tenaga_pendidik` |
| News Grid | Grid berita terbaru | WP Posts |
| Announcement List | Daftar pengumuman | WP Posts category "pengumuman" |
| Agenda Calendar | Event upcoming | CPT `agenda` |
| FAQ Accordion | Expandable FAQ | CPT `faq` |
| Partner Logos | Logo carousel/grid | Manual input |
| Gallery Grid | Grid foto filterable | CPT `galeri` |
| Prestasi Carousel | Carousel prestasi | CPT `prestasi` |
| Why Choose Us | Feature boxes | Manual input |

### 5.2 Elementor Template Library

Tema menyertakan template kit lokal:
- Homepage template (Fakultas & Prodi variant)
- Page templates untuk setiap halaman statis
- Section templates (reusable blocks)
- Admin bisa import dari library lokal, bukan Elementor cloud

---

## 6. Security Hardening

### Layer 1: WordPress Hardening

- Disable XML-RPC completely
- Disable REST API user enumeration (`/wp-json/wp/v2/users`)
- Remove WordPress version meta tag
- `DISALLOW_FILE_EDIT` enforced
- Disable PHP execution di `wp-content/uploads/`
- Custom login URL (fallback, ketika SSO dimatikan)
- Force HTTPS check
- Rate limiting pada login endpoint

### Layer 2: Content Protection (Anti Judi Online)

**Content Scanner:**
- WP-Cron job setiap 6 jam
- Scan semua posts, pages, dan CPT content
- Keyword list: slot, togel, casino, judi, poker, sbobet, pragmatic, dan variasi
- Auto-flag post sebagai "quarantined" jika terdeteksi
- Admin notification via email

**File Integrity Monitor:**
- Hash semua file tema/plugin saat instalasi (SHA-256)
- Periodic check (daily) apakah ada file yang berubah
- Alert jika ada file baru atau modified di luar proses update resmi

**Upload Sanitization:**
- Strict whitelist file types: jpg, jpeg, png, gif, webp, pdf, doc, docx, xls, xlsx, ppt, pptx
- Scan filename untuk pattern mencurigakan
- Block executable file uploads

**Admin Activity Log:**
- Log semua: post create/edit/delete, user login/logout, plugin activate/deactivate, setting changes
- Simpan: user, IP, timestamp, action, old/new values
- Retention: 90 hari
- Viewable di admin panel

### Layer 3: Output Hardening

- Content Security Policy headers
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- Referrer-Policy: strict-origin-when-cross-origin
- Strict input sanitization (esc_html, wp_kses) di semua output
- Nonce verification di semua forms
- Prepared statements di semua custom DB queries

### Layer 4: Anti-SEO Spam Detection

- Monitor outbound links baru (alert jika domain tidak di-whitelist)
- Scan hidden content (display:none, font-size:0, position:absolute off-screen)
- Scheduled scan untuk injected JavaScript
- Webhook notification ke admin email jika anomali terdeteksi

---

## 7. SSO Integration

### 7.1 OAuth2 Flow (Laravel Passport)

```
SSO Endpoints:
- Authorize: https://sso.campusos.ac.id/oauth/authorize
- Token:     https://sso.campusos.ac.id/oauth/token
- User Info: https://sso.campusos.ac.id/api/me/roles (GET, query: client_id)
- Logout:    https://sso.campusos.ac.id/api/logmeout

Token Response:
{
  "token_type": "Bearer",
  "expires_in": 1296000,
  "access_token": "...",
  "refresh_token": "..."
}

User Info Response:
{
  "user_id": "uuid",
  "email": "user@campusos.ac.id",
  "name": "Full Name",
  "client_id": "...",
  "app_name": "...",
  "roles": ["Admin", "Editor"],
  "permissions": ["docs.read", "docs.edit"]
}
```

### 7.2 WordPress SSO Flow

1. User navigates to `/wp-admin`
2. Plugin intercepts, generates random `state`, stores in WP transient
3. Redirect to `sso.campusos.ac.id/oauth/authorize` with `client_id`, `redirect_uri`, `response_type=code`, `state`
4. User authenticates at SSO
5. SSO redirects to WP callback URL with `code` and `state`
6. Plugin validates `state` matches stored transient
7. Plugin POST to `/oauth/token` to exchange code for access token
8. Plugin GET `/api/me/roles?client_id=XXX` with Bearer token
9. Plugin creates or updates WP user:
   - `user_id` (SSO) → WP user meta `_sso_user_id`
   - `email` → WP user email
   - `name` → WP display name
   - Role mapping applied (SSO roles → WP roles, configurable)
10. `wp_set_auth_cookie()` → user logged in to WordPress
11. On logout: WP logout hook also calls `/api/logmeout` with Bearer token

### 7.3 Admin Settings

| Setting | Description |
|---------|-------------|
| SSO Base URL | `https://sso.campusos.ac.id` |
| Client ID | Dari registrasi app di SSO |
| Client Secret | Dari registrasi app di SSO |
| Redirect URI | Auto-generated: `{site_url}/wp-admin/admin-ajax.php?action=campusos_sso_callback` |
| Role Mapping | Table: SSO Role → WP Role (e.g., "Admin" → "administrator") |
| Enable SSO | Toggle on/off |
| Fallback Admin | Username untuk emergency local login (1 akun) |

### 7.4 Security Measures

- State parameter validation (CSRF protection)
- Access token stored in WP user meta (encrypted)
- Token expiry handling with refresh token
- Session timeout configurable
- Failed auth attempt logging

---

## 8. API Integration Framework (Future)

### 8.1 Architecture

```php
abstract class CampusOS_API_Connector {
    abstract function get_base_url(): string;
    abstract function get_auth_headers(): array;

    function fetch(string $endpoint, array $params = []): array {
        // HTTP request with WP HTTP API
        // Response cached in WP transients (configurable TTL)
        // Error handling & logging
    }

    function test_connection(): bool { ... }
}

class SIAKAD_Connector extends CampusOS_API_Connector { ... }
class SIGAP_Connector extends CampusOS_API_Connector { ... }
```

### 8.2 Admin Panel

Tab "Integrasi API" dengan:
- Input fields: Base URL, API Key/Token per sistem
- Test Connection button
- Status indicator (connected/disconnected/error)
- Cache TTL setting
- Currently disabled/placeholder until APIs available

### 8.3 Frontend (Prepared)

Shortcode: `[campusos_data source="siakad" type="mahasiswa_count"]`
Elementor widget: "CampusOS Data" → shows "Belum terhubung" until configured

---

## 9. Export/Import & Deployment

### 9.1 Setup Wizard

Muncul saat tema pertama kali diaktifkan:
1. **Pilih Mode**: Fakultas / Program Studi
2. **Warna**: Primary & Secondary color picker
3. **Identitas**: Upload logo, nama fakultas/prodi, alamat
4. **Demo Content**: Import halaman standar, menu, widget secara otomatis
5. **Selesai**: Site siap diisi konten

### 9.2 Export/Import Tool

**Export** menghasilkan `.json`:
- Customizer settings (warna, logo, site mode)
- CPT data (semua records)
- Page assignments & content
- Menu structure
- Widget configuration
- Elementor templates
- Media sebagai URL references

**Import**: Upload `.json` → auto create/update semua konten

### 9.3 Auto-Update System

```
Tema/Plugin → cek update setiap 12 jam
  → GET {update_server}/api/check?slug=campusos-academic&version=1.0.0
  → Response: { version, download_url, changelog, requires_wp, requires_php }
  → Admin sees "Update available" notification
  → One-click update via standard WP update mechanism
```

Update server: Simple PHP endpoint di server CampusOS yang serve version info + ZIP file.

Plugin dan tema punya update check terpisah.

---

## 10. Tech Stack Summary

| Component | Technology |
|-----------|-----------|
| CMS | WordPress 6.x |
| Page Builder | Elementor (Free or Pro) |
| Theme | Custom PHP theme (`campusos-academic`) |
| Plugin | Custom PHP plugin (`campusos-academic-core`) |
| CSS | CSS custom properties + vanilla CSS (no framework) |
| JS | Vanilla JS (minimal dependencies) |
| Auth | OAuth2 via Laravel Passport SSO |
| API | WordPress REST API + WP HTTP API |
| i18n | WordPress native i18n (ID, EN) |
| Multi-language frontend | Compatible with gTranslate/Polylang |

---

## 11. Implementation Phases

### Phase 1: Foundation
- Theme scaffold & build system
- Companion plugin scaffold
- WordPress Customizer (colors, site mode)
- Basic page templates & header/footer

### Phase 2: Custom Post Types & CRUD
- All 14 CPTs with admin interfaces
- All 10 page meta boxes
- Drag-and-drop ordering

### Phase 3: Elementor Widgets
- All 11 custom widgets
- Template library (homepage + page templates)

### Phase 4: Security
- WordPress hardening
- Content scanner (anti judi)
- File integrity monitor
- Activity log
- Security headers

### Phase 5: SSO Integration
- OAuth2 flow with Laravel Passport
- Role mapping
- Login redirect & fallback

### Phase 6: Export/Import & Update
- Setup wizard
- JSON export/import
- Auto-update server integration

### Phase 7: API Framework
- Abstract connector class
- Admin settings UI (placeholder)
- Shortcode & widget (placeholder)

### Phase 8: Testing & Documentation
- Cross-browser testing
- Security audit
- Admin user documentation (Bahasa Indonesia)
- Developer documentation
