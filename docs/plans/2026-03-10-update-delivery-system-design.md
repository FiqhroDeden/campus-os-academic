# Update Delivery System Design

**Date:** 2026-03-10
**Status:** Approved

## Problem

CampusOS Academic plugin and theme are installed on client websites. The WordPress-side updater classes (`Plugin_Updater`, `Theme_Updater`) already exist and call the license server at `campusos.devlecta.com`. However, the server currently has no mechanism to:
1. Store versioned release ZIP files
2. Serve downloads with license validation
3. Let admins upload new releases via UI

## Solution

Add release management to the existing license server (pure PHP, MySQL, Apache).

## Database

### New table: `releases`

```sql
CREATE TABLE IF NOT EXISTS `releases` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_slug` VARCHAR(100) NOT NULL,
    `version` VARCHAR(20) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_size` BIGINT DEFAULT 0,
    `file_hash` VARCHAR(64) DEFAULT '',
    `changelog` TEXT DEFAULT NULL,
    `requires_wp` VARCHAR(10) DEFAULT '6.0',
    `tested_wp` VARCHAR(10) DEFAULT '6.9',
    `requires_php` VARCHAR(10) DEFAULT '8.0',
    `is_active` TINYINT(1) DEFAULT 1,
    `downloads` INT DEFAULT 0,
    `released_by` VARCHAR(100) DEFAULT '',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `idx_product_version` (`product_slug`, `version`),
    INDEX `idx_active` (`product_slug`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## File Storage

```
license-server/
├── releases/
│   ├── .htaccess          # Deny all direct access
│   ├── *.zip              # Release ZIP files
```

## API Endpoints

### `GET /api/check` (update existing)

Read from `releases` table instead of `config.php`. Return active release for requested slug.

**Query params:** `slug`, `version`, `license_key`
**Response:** `{ version, download_url, changelog_url, tested, requires, requires_php }`

### `GET /api/download` (new)

Stream ZIP file after validating license.

**Query params:** `slug`, `license_key`
**Response:** Binary ZIP file stream
**Security:** Validates license is active + not expired. Increments download counter. Logs to `api_logs`.

### `GET /api/info` (new)

Return full product metadata for WordPress plugin details modal.

**Query params:** `slug`
**Response:** `{ name, version, author, homepage, requires, tested, sections: { description, changelog }, download_url }`

## Admin Panel

### Releases page (`admin/releases.php`)

- List all releases per product with version, status, downloads, date
- Upload form: product selector, version input, ZIP file upload, changelog textarea
- Validation: ZIP format, max 50MB, valid archive
- Toggle active/inactive per release
- SHA256 hash computed on upload

## Update Flow

```
Admin uploads ZIP → releases/ folder + releases table
    ↓
WordPress client checks for updates (cron or manual)
    ↓
Plugin_Updater → GET /api/check?slug=...&version=...&license_key=...
    ↓
Server compares versions from releases table
    ↓
Response includes download_url pointing to /api/download
    ↓
WordPress downloads ZIP via /api/download (license validated)
    ↓
WordPress installs update
```

## WordPress Plugin Changes (minimal)

- Ensure `download_url` from `/api/check` response is used as-is (already the case)
- No structural changes needed — current updater classes already support this flow

## Security

- `releases/` protected by `.htaccess` (deny all)
- Downloads only through `/api/download` with valid license
- SHA256 hash stored per release for integrity verification
- Upload limited to ZIP format, max 50MB
- Admin panel protected by session authentication
