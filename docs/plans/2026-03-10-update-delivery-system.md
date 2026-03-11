# Update Delivery System Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Enable client WordPress sites to receive theme/plugin updates from the license server at campusos.devlecta.com.

**Architecture:** Add release management (upload ZIP, version tracking, secure download) to the existing pure-PHP license server. The WordPress-side updater classes already exist and need minimal changes. The server gets a new `releases` table, admin upload UI, and download/info API endpoints.

**Tech Stack:** Pure PHP 8.0+, MySQL (PDO), Apache (.htaccess), no framework

---

### Task 1: Add `releases` table to database schema

**Files:**
- Modify: `license-server/schema.sql` (append new table)

**Step 1: Add the releases table SQL**

Append to `license-server/schema.sql`:

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

**Step 2: Verify schema file is valid**

Run: Open `license-server/schema.sql` and confirm all 4 tables (licenses, admin_users, api_logs, releases) are present.

**Step 3: Commit**

```bash
git add license-server/schema.sql
git commit -m "feat(license-server): add releases table to schema"
```

---

### Task 2: Create `releases/` directory with .htaccess protection

**Files:**
- Create: `license-server/releases/.htaccess`

**Step 1: Create the releases directory and .htaccess**

Create `license-server/releases/.htaccess`:

```apache
Order Allow,Deny
Deny from all
```

**Step 2: Add .gitkeep to ensure directory is tracked**

Create empty file `license-server/releases/.gitkeep`.

**Step 3: Commit**

```bash
git add license-server/releases/.htaccess license-server/releases/.gitkeep
git commit -m "feat(license-server): add protected releases directory"
```

---

### Task 3: Add product slugs config for both plugin and theme

**Files:**
- Modify: `license-server/config.php` (lines 11-18)

**Step 1: Update products config to support both slugs**

The WordPress plugin uses slug `campusos-academic-core` (plugin) and `campusos-academic` (theme). The current config only has `campusos-academic`. Update `config.php` products array:

```php
'products' => [
    'campusos-academic-core' => [
        'name' => 'CampusOS Academic Core',
        'type' => 'plugin',
    ],
    'campusos-academic' => [
        'name' => 'CampusOS Academic',
        'type' => 'theme',
    ],
],
```

Remove `current_version`, `download_url`, `changelog_url` from config — these now come from the `releases` table.

**Step 2: Commit**

```bash
git add license-server/config.php
git commit -m "refactor(license-server): move product versions to database, add plugin slug"
```

---

### Task 4: Rewrite `/api/check-update.php` to read from `releases` table

**Files:**
- Modify: `license-server/api/check-update.php` (full rewrite)

**Step 1: Rewrite check-update.php**

Replace entire contents of `license-server/api/check-update.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$config = require __DIR__ . '/../config.php';

$slug        = $_GET['slug'] ?? '';
$version     = $_GET['version'] ?? '';
$license_key = $_GET['license_key'] ?? '';

// Validate product slug exists in config
$product = $config['products'][ $slug ] ?? null;
if ( ! $product ) {
    echo json_encode( [] );
    exit;
}

// Get latest active release for this product
$release = DB::fetch(
    'SELECT * FROM releases WHERE product_slug = ? AND is_active = 1 ORDER BY created_at DESC LIMIT 1',
    [ $slug ]
);

if ( ! $release ) {
    echo json_encode( [] );
    exit;
}

// Check if client already has this version or newer
if ( ! empty( $version ) && version_compare( $release['version'], $version, '<=' ) ) {
    echo json_encode( [] );
    exit;
}

// Build download URL only if license is valid
$download_url = '';
if ( ! empty( $license_key ) ) {
    $license = DB::fetch(
        'SELECT * FROM licenses WHERE license_key = ? AND status = ?',
        [ $license_key, 'active' ]
    );
    if ( $license && ( empty( $license['expires_at'] ) || strtotime( $license['expires_at'] ) > time() ) ) {
        $base_url = rtrim( ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . dirname( $_SERVER['SCRIPT_NAME'] ), '/' );
        $download_url = $base_url . '/api/download?slug=' . urlencode( $slug ) . '&license_key=' . urlencode( $license_key );
    }
}

echo json_encode( [
    'name'         => $product['name'],
    'version'      => $release['version'],
    'download_url' => $download_url,
    'changelog_url' => '',
    'tested'       => $release['tested_wp'],
    'requires'     => $release['requires_wp'],
    'requires_php' => $release['requires_php'],
] );
```

**Step 2: Verify by checking the file reads correctly**

Read the file and confirm it queries `releases` table, not `config.php` products.

**Step 3: Commit**

```bash
git add license-server/api/check-update.php
git commit -m "refactor(license-server): read versions from releases table instead of config"
```

---

### Task 5: Create `/api/download.php` for secure file delivery

**Files:**
- Create: `license-server/api/download.php`

**Step 1: Create download.php**

Create `license-server/api/download.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$config = require __DIR__ . '/../config.php';

$slug        = $_GET['slug'] ?? '';
$license_key = $_GET['license_key'] ?? '';

// Validate inputs
if ( empty( $slug ) || empty( $license_key ) ) {
    http_response_code( 400 );
    echo json_encode( [ 'error' => 'Slug and license_key are required.' ] );
    exit;
}

// Validate product exists
$product = $config['products'][ $slug ] ?? null;
if ( ! $product ) {
    http_response_code( 404 );
    echo json_encode( [ 'error' => 'Product not found.' ] );
    exit;
}

// Validate license
$license = DB::fetch(
    'SELECT * FROM licenses WHERE license_key = ? AND status = ?',
    [ $license_key, 'active' ]
);

if ( ! $license ) {
    http_response_code( 403 );
    echo json_encode( [ 'error' => 'Invalid or inactive license.' ] );
    exit;
}

if ( ! empty( $license['expires_at'] ) && strtotime( $license['expires_at'] ) <= time() ) {
    http_response_code( 403 );
    echo json_encode( [ 'error' => 'License has expired.' ] );
    exit;
}

// Get latest active release
$release = DB::fetch(
    'SELECT * FROM releases WHERE product_slug = ? AND is_active = 1 ORDER BY created_at DESC LIMIT 1',
    [ $slug ]
);

if ( ! $release ) {
    http_response_code( 404 );
    echo json_encode( [ 'error' => 'No release available.' ] );
    exit;
}

$file_path = __DIR__ . '/../releases/' . $release['file_name'];

if ( ! file_exists( $file_path ) ) {
    http_response_code( 404 );
    echo json_encode( [ 'error' => 'Release file not found.' ] );
    exit;
}

// Increment download counter
DB::query( 'UPDATE releases SET downloads = downloads + 1 WHERE id = ?', [ $release['id'] ] );

// Log the download
DB::query(
    'INSERT INTO api_logs (endpoint, license_key, domain, ip_address, response_code) VALUES (?, ?, ?, ?, ?)',
    [ 'download', $license_key, $license['activated_domain'] ?? '', $_SERVER['REMOTE_ADDR'] ?? '', 200 ]
);

// Stream the file
header( 'Content-Type: application/zip' );
header( 'Content-Disposition: attachment; filename="' . $release['file_name'] . '"' );
header( 'Content-Length: ' . filesize( $file_path ) );
header( 'Cache-Control: no-cache, no-store, must-revalidate' );
readfile( $file_path );
exit;
```

**Step 2: Commit**

```bash
git add license-server/api/download.php
git commit -m "feat(license-server): add secure download endpoint with license validation"
```

---

### Task 6: Create `/api/info.php` for plugin details modal

**Files:**
- Create: `license-server/api/info.php`

**Step 1: Create info.php**

Create `license-server/api/info.php`:

```php
<?php
require_once __DIR__ . '/../db.php';

$config = require __DIR__ . '/../config.php';

$slug = $_GET['slug'] ?? '';

$product = $config['products'][ $slug ] ?? null;
if ( ! $product ) {
    echo json_encode( [] );
    exit;
}

$release = DB::fetch(
    'SELECT * FROM releases WHERE product_slug = ? AND is_active = 1 ORDER BY created_at DESC LIMIT 1',
    [ $slug ]
);

if ( ! $release ) {
    echo json_encode( [] );
    exit;
}

echo json_encode( [
    'name'         => $product['name'],
    'version'      => $release['version'],
    'author'       => 'CampusOS Team',
    'homepage'     => 'https://campusos.devlecta.com',
    'requires'     => $release['requires_wp'],
    'tested'       => $release['tested_wp'],
    'requires_php' => $release['requires_php'],
    'downloaded'   => (int) $release['downloads'],
    'last_updated' => date( 'Y-m-d', strtotime( $release['created_at'] ) ),
    'sections'     => [
        'description' => $product['name'] . ' - WordPress plugin untuk institusi pendidikan tinggi.',
        'changelog'   => $release['changelog'] ?? 'No changelog available.',
    ],
    'download_url' => '',
] );
```

**Step 2: Commit**

```bash
git add license-server/api/info.php
git commit -m "feat(license-server): add product info endpoint for WordPress plugin modal"
```

---

### Task 7: Update router to add new routes

**Files:**
- Modify: `license-server/index.php` (lines 14-20)

**Step 1: Add download, info, and releases admin routes**

Update the `$routes` array in `license-server/index.php`:

```php
$routes = [
    'POST /api/activate'      => 'api/activate.php',
    'POST /api/validate'      => 'api/validate.php',
    'POST /api/deactivate'    => 'api/deactivate.php',
    'GET /api/check-update'   => 'api/check-update.php',
    'GET /api/check'          => 'api/check-update.php',
    'GET /api/download'       => 'api/download.php',
    'GET /api/info'           => 'api/info.php',
];
```

Also add `/admin/releases` route in the admin section. Update the admin routing block:

```php
} elseif ( strpos( $uri, '/admin' ) === 0 ) {
    header( 'Content-Type: text/html; charset=utf-8' );
    if ( $uri === '/admin' || $uri === '/admin/' ) {
        require __DIR__ . '/admin/index.php';
    } elseif ( $uri === '/admin/login' ) {
        require __DIR__ . '/admin/login.php';
    } elseif ( $uri === '/admin/releases' ) {
        require __DIR__ . '/admin/releases.php';
    } else {
        http_response_code( 404 );
        echo '<h1>404 Not Found</h1>';
    }
}
```

**Step 2: Commit**

```bash
git add license-server/index.php
git commit -m "feat(license-server): add download, info, and releases admin routes"
```

---

### Task 8: Create admin releases page

**Files:**
- Create: `license-server/admin/releases.php`

**Step 1: Create the releases management page**

Create `license-server/admin/releases.php` with:
- Session auth check (same pattern as `admin/index.php`)
- POST handler for `upload_release` action:
  - Validate: product slug is in config, version format, file is ZIP, max 50MB
  - Save ZIP to `releases/` directory with naming `{slug}-{version}.zip`
  - Compute SHA256 hash
  - Insert into `releases` table
  - Deactivate previous active releases for same product (set `is_active = 0`)
- POST handler for `toggle_active` action:
  - Toggle `is_active` on a specific release
- POST handler for `delete_release` action:
  - Delete release record and file from disk
- Display: upload form + list of all releases ordered by date desc

```php
<?php
session_start();
if ( empty( $_SESSION['admin_logged_in'] ) ) {
    header( 'Location: /admin/login.php' );
    exit;
}

require_once __DIR__ . '/../db.php';
$config = require __DIR__ . '/../config.php';

$success = '';
$error   = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['action'] ) ) {

    if ( $_POST['action'] === 'upload_release' ) {
        $slug      = $_POST['product_slug'] ?? '';
        $version   = trim( $_POST['version'] ?? '' );
        $changelog = trim( $_POST['changelog'] ?? '' );
        $requires_wp  = trim( $_POST['requires_wp'] ?? '6.0' );
        $tested_wp    = trim( $_POST['tested_wp'] ?? '6.9' );
        $requires_php = trim( $_POST['requires_php'] ?? '8.0' );

        // Validate product
        if ( ! isset( $config['products'][ $slug ] ) ) {
            $error = 'Produk tidak valid.';
        }
        // Validate version format
        elseif ( ! preg_match( '/^\d+\.\d+(\.\d+)?$/', $version ) ) {
            $error = 'Format versi tidak valid (contoh: 1.2.3).';
        }
        // Check duplicate version
        elseif ( DB::fetch( 'SELECT id FROM releases WHERE product_slug = ? AND version = ?', [ $slug, $version ] ) ) {
            $error = "Versi {$version} untuk produk ini sudah ada.";
        }
        // Validate file upload
        elseif ( empty( $_FILES['zip_file'] ) || $_FILES['zip_file']['error'] !== UPLOAD_ERR_OK ) {
            $error = 'Upload file gagal. Pastikan file ZIP valid.';
        }
        elseif ( $_FILES['zip_file']['size'] > 50 * 1024 * 1024 ) {
            $error = 'Ukuran file maksimal 50MB.';
        }
        elseif ( strtolower( pathinfo( $_FILES['zip_file']['name'], PATHINFO_EXTENSION ) ) !== 'zip' ) {
            $error = 'Hanya file ZIP yang diperbolehkan.';
        }
        else {
            $file_name = $slug . '-' . $version . '.zip';
            $dest_path = __DIR__ . '/../releases/' . $file_name;

            if ( move_uploaded_file( $_FILES['zip_file']['tmp_name'], $dest_path ) ) {
                $file_hash = hash_file( 'sha256', $dest_path );
                $file_size = filesize( $dest_path );

                // Deactivate previous active releases for this product
                DB::query(
                    'UPDATE releases SET is_active = 0 WHERE product_slug = ? AND is_active = 1',
                    [ $slug ]
                );

                // Insert new release
                DB::query(
                    'INSERT INTO releases (product_slug, version, file_name, file_size, file_hash, changelog, requires_wp, tested_wp, requires_php, is_active, released_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)',
                    [ $slug, $version, $file_name, $file_size, $file_hash, $changelog, $requires_wp, $tested_wp, $requires_php, $_SESSION['admin_user'] ]
                );

                $success = "Release {$slug} v{$version} berhasil diupload.";
            } else {
                $error = 'Gagal menyimpan file. Periksa permission folder releases/.';
            }
        }

    } elseif ( $_POST['action'] === 'toggle_active' && ! empty( $_POST['release_id'] ) ) {
        $release = DB::fetch( 'SELECT * FROM releases WHERE id = ?', [ (int) $_POST['release_id'] ] );
        if ( $release ) {
            if ( $release['is_active'] ) {
                // Deactivate
                DB::query( 'UPDATE releases SET is_active = 0 WHERE id = ?', [ $release['id'] ] );
                $success = "Release v{$release['version']} dinonaktifkan.";
            } else {
                // Activate this one, deactivate others for same product
                DB::query( 'UPDATE releases SET is_active = 0 WHERE product_slug = ? AND is_active = 1', [ $release['product_slug'] ] );
                DB::query( 'UPDATE releases SET is_active = 1 WHERE id = ?', [ $release['id'] ] );
                $success = "Release v{$release['version']} diaktifkan.";
            }
        }

    } elseif ( $_POST['action'] === 'delete_release' && ! empty( $_POST['release_id'] ) ) {
        $release = DB::fetch( 'SELECT * FROM releases WHERE id = ?', [ (int) $_POST['release_id'] ] );
        if ( $release ) {
            $file_path = __DIR__ . '/../releases/' . $release['file_name'];
            if ( file_exists( $file_path ) ) {
                unlink( $file_path );
            }
            DB::query( 'DELETE FROM releases WHERE id = ?', [ $release['id'] ] );
            $success = "Release v{$release['version']} dihapus.";
        }
    }
}

$releases = DB::fetchAll( 'SELECT * FROM releases ORDER BY created_at DESC LIMIT 100' );

// Stats
$total_releases  = DB::fetch( 'SELECT COUNT(*) as c FROM releases' )['c'];
$total_downloads = DB::fetch( 'SELECT COALESCE(SUM(downloads), 0) as c FROM releases' )['c'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Releases - CampusOS License Server</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f0f2f5}
        .header{background:#003d82;color:#fff;padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center}
        .header h1{font-size:1.25rem}
        .header-nav{display:flex;gap:1.5rem;align-items:center}
        .header-nav a{color:#fff;text-decoration:none;opacity:.8;font-size:.9rem}
        .header-nav a:hover{opacity:1}
        .header-nav a.active{opacity:1;border-bottom:2px solid #fff;padding-bottom:2px}
        .container{max-width:1200px;margin:2rem auto;padding:0 1rem}
        .stats{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:2rem}
        .stat-card{background:#fff;padding:1.5rem;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);text-align:center}
        .stat-card .number{font-size:2rem;font-weight:700;color:#003d82}
        .stat-card .label{color:#666;font-size:.85rem;margin-top:.25rem}
        .card{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);padding:1.5rem;margin-bottom:1.5rem}
        .card h2{font-size:1.1rem;margin-bottom:1rem;color:#333}
        table{width:100%;border-collapse:collapse;font-size:.9rem}
        th,td{padding:.6rem .75rem;text-align:left;border-bottom:1px solid #eee}
        th{background:#f8f9fa;font-weight:600}
        .badge{display:inline-block;padding:.15rem .5rem;border-radius:12px;font-size:.75rem;font-weight:600}
        .badge-active{background:#d4edda;color:#155724}
        .badge-inactive{background:#e2e3e5;color:#383d41}
        form.inline{display:inline}
        input[type="text"],select,textarea{padding:.4rem .6rem;border:1px solid #ccc;border-radius:4px;font-size:.9rem}
        input[type="file"]{font-size:.9rem}
        textarea{width:100%;min-height:80px;resize:vertical;font-family:inherit}
        button,.btn{background:#003d82;color:#fff;border:none;padding:.4rem 1rem;border-radius:4px;cursor:pointer;font-size:.85rem}
        button:hover{background:#002a5c}
        .btn-danger{background:#dc3545}
        .btn-danger:hover{background:#c82333}
        .btn-warning{background:#ffc107;color:#333}
        .btn-warning:hover{background:#e0a800}
        .btn-success{background:#28a745}
        .btn-success:hover{background:#218838}
        .btn-sm{padding:.2rem .5rem;font-size:.8rem}
        .success{background:#d4edda;color:#155724;padding:.75rem 1rem;border-radius:4px;margin-bottom:1rem}
        .error-msg{background:#f8d7da;color:#721c24;padding:.75rem 1rem;border-radius:4px;margin-bottom:1rem}
        .form-row{display:flex;gap:.75rem;align-items:end;flex-wrap:wrap;margin-bottom:.75rem}
        .form-group{display:flex;flex-direction:column;gap:.25rem}
        .form-group label{font-size:.8rem;font-weight:500}
        .form-group-full{width:100%;display:flex;flex-direction:column;gap:.25rem}
        .form-group-full label{font-size:.8rem;font-weight:500}
        code{background:#f4f4f4;padding:.1rem .3rem;border-radius:3px;font-size:.85rem}
        .file-size{color:#666;font-size:.8rem}
        .actions{display:flex;gap:.25rem}
    </style>
</head>
<body>
    <div class="header">
        <h1>CampusOS License Server</h1>
        <div class="header-nav">
            <a href="/admin">Lisensi</a>
            <a href="/admin/releases" class="active">Releases</a>
            <a href="/admin/index.php?logout=1">Logout (<?=htmlspecialchars($_SESSION['admin_user'])?>)</a>
        </div>
    </div>
    <div class="container">
        <?php if($success):?><div class="success"><?=htmlspecialchars($success)?></div><?php endif;?>
        <?php if($error):?><div class="error-msg"><?=htmlspecialchars($error)?></div><?php endif;?>

        <div class="stats">
            <div class="stat-card"><div class="number"><?=$total_releases?></div><div class="label">Total Releases</div></div>
            <div class="stat-card"><div class="number"><?=$total_downloads?></div><div class="label">Total Downloads</div></div>
        </div>

        <div class="card">
            <h2>Upload Release Baru</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_release" />
                <div class="form-row">
                    <div class="form-group">
                        <label>Produk</label>
                        <select name="product_slug" required>
                            <?php foreach ( $config['products'] as $pslug => $pdata ): ?>
                                <option value="<?=htmlspecialchars($pslug)?>"><?=htmlspecialchars($pdata['name'])?> (<?=$pslug?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Versi</label>
                        <input type="text" name="version" placeholder="1.2.3" required pattern="\d+\.\d+(\.\d+)?" />
                    </div>
                    <div class="form-group">
                        <label>File ZIP</label>
                        <input type="file" name="zip_file" accept=".zip" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Requires WP</label>
                        <input type="text" name="requires_wp" value="6.0" />
                    </div>
                    <div class="form-group">
                        <label>Tested WP</label>
                        <input type="text" name="tested_wp" value="6.9" />
                    </div>
                    <div class="form-group">
                        <label>Requires PHP</label>
                        <input type="text" name="requires_php" value="8.0" />
                    </div>
                </div>
                <div class="form-group-full" style="margin-bottom:.75rem">
                    <label>Changelog</label>
                    <textarea name="changelog" placeholder="Deskripsi perubahan di versi ini..."></textarea>
                </div>
                <button type="submit">Upload Release</button>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Releases</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Versi</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Downloads</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $releases as $rel ): ?>
                    <tr>
                        <td><?=htmlspecialchars($rel['product_slug'])?></td>
                        <td><strong><?=htmlspecialchars($rel['version'])?></strong></td>
                        <td>
                            <?=htmlspecialchars($rel['file_name'])?>
                            <span class="file-size">(<?=number_format($rel['file_size']/1024/1024, 2)?> MB)</span>
                        </td>
                        <td>
                            <span class="badge <?=$rel['is_active'] ? 'badge-active' : 'badge-inactive'?>">
                                <?=$rel['is_active'] ? 'Aktif' : 'Non-aktif'?>
                            </span>
                        </td>
                        <td><?=$rel['downloads']?></td>
                        <td><?=date('d M Y H:i', strtotime($rel['created_at']))?></td>
                        <td>
                            <div class="actions">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="action" value="toggle_active" />
                                    <input type="hidden" name="release_id" value="<?=$rel['id']?>" />
                                    <button type="submit" class="btn-sm <?=$rel['is_active'] ? 'btn-warning' : 'btn-success'?>">
                                        <?=$rel['is_active'] ? 'Nonaktifkan' : 'Aktifkan'?>
                                    </button>
                                </form>
                                <form method="POST" class="inline" onsubmit="return confirm('Yakin hapus release ini?')">
                                    <input type="hidden" name="action" value="delete_release" />
                                    <input type="hidden" name="release_id" value="<?=$rel['id']?>" />
                                    <button type="submit" class="btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ( empty( $releases ) ): ?>
                    <tr><td colspan="7" style="text-align:center;color:#999;padding:2rem">Belum ada release.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
```

**Step 2: Commit**

```bash
git add license-server/admin/releases.php
git commit -m "feat(license-server): add releases admin page with upload, toggle, delete"
```

---

### Task 9: Add navigation to admin dashboard

**Files:**
- Modify: `license-server/admin/index.php` (line 98-101, header section)

**Step 1: Update header to include navigation links**

Replace the header div in `license-server/admin/index.php`:

```html
<div class="header">
    <h1>CampusOS License Server</h1>
    <div style="display:flex;gap:1.5rem;align-items:center">
        <a href="/admin" style="color:#fff;text-decoration:none;opacity:1;border-bottom:2px solid #fff;padding-bottom:2px;font-size:.9rem">Lisensi</a>
        <a href="/admin/releases" style="color:#fff;text-decoration:none;opacity:.8;font-size:.9rem">Releases</a>
        <a href="/admin/index.php?logout=1" style="color:#fff;text-decoration:none;opacity:.8">Logout (<?=htmlspecialchars($_SESSION['admin_user'])?>)</a>
    </div>
</div>
```

**Step 2: Commit**

```bash
git add license-server/admin/index.php
git commit -m "feat(license-server): add releases nav link to admin dashboard"
```

---

### Task 10: Update .htaccess to protect releases and new files

**Files:**
- Modify: `license-server/.htaccess`

**Step 1: Add admin routing and ensure API routes work**

Update `license-server/.htaccess`:

```apache
RewriteEngine On
RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

<FilesMatch "(config\.php|db\.php|schema\.sql)">
    Order Allow,Deny
    Deny from all
</FilesMatch>

Options -Indexes
```

The change: the RewriteRule now catches ALL paths (not just `api/`) so `/admin/releases` routes through `index.php`.

**Step 2: Commit**

```bash
git add license-server/.htaccess
git commit -m "fix(license-server): route all paths through index.php for admin pages"
```

---

### Task 11: Verify WordPress plugin compatibility

**Files:**
- Review: `wp-content/plugins/campusos-academic-core/includes/updater/class-plugin-updater.php`
- Review: `wp-content/plugins/campusos-academic-core/includes/updater/class-theme-updater.php`

**Step 1: Verify check_update flow**

The WordPress plugin updater:
- `Plugin_Updater` calls `/api/check` with `slug=campusos-academic-core` → server now returns data from `releases` table ✓
- `Theme_Updater` calls `/api/check` with `slug=campusos-academic` → server now returns data from `releases` table ✓
- `download_url` is dynamically built as `/api/download?slug=...&license_key=...` → download endpoint validates and streams ✓
- `Plugin_Updater::plugin_info()` calls `/api/info` → new endpoint returns metadata ✓

**Step 2: Check slug alignment**

Plugin uses `campusos-academic-core` as slug. Config now has both `campusos-academic-core` (plugin) and `campusos-academic` (theme). ✓

**Step 3: No code changes needed**

The WordPress plugin is already compatible. The `package` key in transient gets `download_url` from the server response — WordPress will use this URL to download the ZIP. ✓

**Step 4: Commit (no changes, just document verification)**

No commit needed.

---

### Task 12: Create SQL migration script for production deployment

**Files:**
- Create: `license-server/migrate-releases.sql`

**Step 1: Create migration file**

Create `license-server/migrate-releases.sql`:

```sql
-- Migration: Add releases table
-- Run this on the production database at campusos.devlecta.com

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

**Step 2: Commit**

```bash
git add license-server/migrate-releases.sql
git commit -m "feat(license-server): add releases table migration for production"
```

---

### Task 13: Deployment checklist

This is not a code task — it's a manual checklist for deploying to `campusos.devlecta.com`:

1. **Upload files** to server: all new/modified files in `license-server/`
2. **Run migration**: Execute `migrate-releases.sql` on the production database
3. **Create releases directory**: `mkdir releases && chmod 755 releases` on server
4. **Verify .htaccess**: Confirm releases folder has its own `.htaccess` denying direct access
5. **Set PHP upload limit**: Ensure `php.ini` has `upload_max_filesize = 50M` and `post_max_size = 50M`
6. **Test**: Login to admin, go to Releases page, upload a test ZIP
7. **Configure WordPress client**: Set `update_server_url` to `https://campusos.devlecta.com/` in CampusOS Settings
8. **Test update check**: Go to WordPress Dashboard → Updates, verify the update appears
