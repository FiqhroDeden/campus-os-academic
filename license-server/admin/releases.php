<?php
session_start();
if ( empty( $_SESSION['admin_logged_in'] ) ) {
    header( 'Location: /admin/login' );
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
