<?php
session_start();
if ( empty( $_SESSION['admin_logged_in'] ) ) {
    header( 'Location: /admin/login.php' );
    exit;
}

if ( isset( $_GET['logout'] ) ) {
    session_destroy();
    header( 'Location: /admin/login.php' );
    exit;
}

require_once __DIR__ . '/../db.php';

$total    = DB::fetch( 'SELECT COUNT(*) as c FROM licenses' )['c'];
$active   = DB::fetch( "SELECT COUNT(*) as c FROM licenses WHERE status = 'active'" )['c'];
$expired  = DB::fetch( "SELECT COUNT(*) as c FROM licenses WHERE status = 'expired'" )['c'];
$inactive = DB::fetch( "SELECT COUNT(*) as c FROM licenses WHERE status = 'inactive'" )['c'];

$success = '';
$error   = '';
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && !empty( $_POST['action'] ) ) {
    if ( $_POST['action'] === 'generate' ) {
        $key = strtoupper( implode( '-', str_split( bin2hex( random_bytes(16) ), 4 ) ) );
        DB::query(
            'INSERT INTO licenses (license_key, customer_email, customer_name, product_type, status) VALUES (?, ?, ?, ?, ?)',
            [ $key, trim($_POST['email']??''), trim($_POST['name']??''), $_POST['product_type']??'bundle', 'inactive' ]
        );
        $success = "License key dibuat: {$key}";
    } elseif ( $_POST['action'] === 'revoke' && !empty( $_POST['license_id'] ) ) {
        DB::query( 'UPDATE licenses SET status = "revoked" WHERE id = ?', [ (int)$_POST['license_id'] ] );
        $success = 'License dicabut.';
    } elseif ( $_POST['action'] === 'change_password' ) {
        $current  = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $admin    = DB::fetch( 'SELECT * FROM admin_users WHERE username = ?', [ $_SESSION['admin_user'] ] );
        if ( ! $admin || ! password_verify( $current, $admin['password_hash'] ) ) {
            $error = 'Password lama salah.';
        } elseif ( strlen( $new_pass ) < 6 ) {
            $error = 'Password baru minimal 6 karakter.';
        } elseif ( $new_pass !== $confirm ) {
            $error = 'Konfirmasi password tidak cocok.';
        } else {
            DB::query( 'UPDATE admin_users SET password_hash = ? WHERE id = ?', [ password_hash( $new_pass, PASSWORD_DEFAULT ), $admin['id'] ] );
            $success = 'Password berhasil diubah.';
        }
    }
}

$licenses = DB::fetchAll( 'SELECT * FROM licenses ORDER BY created_at DESC LIMIT 50' );
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CampusOS License Server</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f0f2f5}
        .header{background:#003d82;color:#fff;padding:1rem 2rem;display:flex;justify-content:space-between;align-items:center}
        .header h1{font-size:1.25rem}
        .header a{color:#fff;text-decoration:none;opacity:.8}
        .container{max-width:1200px;margin:2rem auto;padding:0 1rem}
        .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem}
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
        .badge-expired{background:#f8d7da;color:#721c24}
        .badge-inactive{background:#fff3cd;color:#856404}
        .badge-revoked{background:#e2e3e5;color:#383d41}
        form.inline{display:inline}
        input[type="text"],input[type="email"],select{padding:.4rem .6rem;border:1px solid #ccc;border-radius:4px;font-size:.9rem}
        button,.btn{background:#003d82;color:#fff;border:none;padding:.4rem 1rem;border-radius:4px;cursor:pointer;font-size:.85rem}
        button:hover{background:#002a5c}
        .btn-danger{background:#dc3545}
        .btn-danger:hover{background:#c82333}
        .btn-sm{padding:.2rem .5rem;font-size:.8rem}
        .success{background:#d4edda;color:#155724;padding:.75rem 1rem;border-radius:4px;margin-bottom:1rem}
        .error-msg{background:#f8d7da;color:#721c24;padding:.75rem 1rem;border-radius:4px;margin-bottom:1rem}
        input[type="password"]{padding:.4rem .6rem;border:1px solid #ccc;border-radius:4px;font-size:.9rem}
        .form-row{display:flex;gap:.5rem;align-items:end;flex-wrap:wrap}
        .form-group{display:flex;flex-direction:column;gap:.25rem}
        .form-group label{font-size:.8rem;font-weight:500}
        code{background:#f4f4f4;padding:.1rem .3rem;border-radius:3px;font-size:.85rem}
    </style>
</head>
<body>
    <div class="header">
        <h1>CampusOS License Server</h1>
        <a href="/admin/index.php?logout=1">Logout (<?=htmlspecialchars($_SESSION['admin_user'])?>)</a>
    </div>
    <div class="container">
        <?php if($success):?><div class="success"><?=htmlspecialchars($success)?></div><?php endif;?>
        <?php if($error):?><div class="error-msg"><?=htmlspecialchars($error)?></div><?php endif;?>
        <div class="stats">
            <div class="stat-card"><div class="number"><?=$total?></div><div class="label">Total Lisensi</div></div>
            <div class="stat-card"><div class="number"><?=$active?></div><div class="label">Aktif</div></div>
            <div class="stat-card"><div class="number"><?=$expired?></div><div class="label">Expired</div></div>
            <div class="stat-card"><div class="number"><?=$inactive?></div><div class="label">Belum Aktif</div></div>
        </div>
        <div class="card">
            <h2>Buat License Key Baru</h2>
            <form method="POST">
                <input type="hidden" name="action" value="generate" />
                <div class="form-row">
                    <div class="form-group"><label>Nama Customer</label><input type="text" name="name" placeholder="Nama" /></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="email@example.com" /></div>
                    <div class="form-group"><label>Tipe Produk</label><select name="product_type"><option value="bundle">Bundle (Theme + Plugin)</option><option value="theme">Theme Only</option></select></div>
                    <div class="form-group"><label>&nbsp;</label><button type="submit">Generate Key</button></div>
                </div>
            </form>
        </div>
        <div class="card">
            <h2>Daftar Lisensi</h2>
            <table>
                <thead><tr><th>License Key</th><th>Customer</th><th>Domain</th><th>Status</th><th>Expires</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php foreach($licenses as $lic):?>
                    <tr>
                        <td><code><?=htmlspecialchars($lic['license_key'])?></code></td>
                        <td><?=htmlspecialchars($lic['customer_name']?:$lic['customer_email']?:'-')?></td>
                        <td><?=htmlspecialchars($lic['activated_domain']?:'-')?></td>
                        <td><span class="badge badge-<?=$lic['status']?>"><?=ucfirst($lic['status'])?></span></td>
                        <td><?=$lic['expires_at']?date('d M Y',strtotime($lic['expires_at'])):'-'?></td>
                        <td>
                            <?php if($lic['status']!=='revoked'):?>
                            <form method="POST" class="inline" onsubmit="return confirm('Yakin cabut lisensi ini?')">
                                <input type="hidden" name="action" value="revoke" />
                                <input type="hidden" name="license_id" value="<?=$lic['id']?>" />
                                <button type="submit" class="btn-danger btn-sm">Revoke</button>
                            </form>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="card">
            <h2>Ganti Password</h2>
            <form method="POST">
                <input type="hidden" name="action" value="change_password" />
                <div class="form-row">
                    <div class="form-group"><label>Password Lama</label><input type="password" name="current_password" required /></div>
                    <div class="form-group"><label>Password Baru</label><input type="password" name="new_password" required minlength="6" /></div>
                    <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="confirm_password" required /></div>
                    <div class="form-group"><label>&nbsp;</label><button type="submit">Ubah Password</button></div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
