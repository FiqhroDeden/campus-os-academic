<?php
session_start();
require_once __DIR__ . '/../db.php';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $username = trim( $_POST['username'] ?? '' );
    $password = $_POST['password'] ?? '';
    $user = DB::fetch( 'SELECT * FROM admin_users WHERE username = ?', [ $username ] );
    if ( $user && password_verify( $password, $user['password_hash'] ) ) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_user']      = $username;
        header( 'Location: /admin/index.php' );
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusOS License Server</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f0f2f5;display:flex;align-items:center;justify-content:center;min-height:100vh}
        .login-box{background:#fff;padding:2rem;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);width:100%;max-width:400px}
        h1{font-size:1.5rem;margin-bottom:1.5rem;color:#003d82}
        label{display:block;margin-bottom:.25rem;font-weight:500;font-size:.9rem}
        input[type="text"],input[type="password"]{width:100%;padding:.5rem .75rem;border:1px solid #ccc;border-radius:4px;font-size:1rem;margin-bottom:1rem}
        button{background:#003d82;color:#fff;border:none;padding:.6rem 1.5rem;border-radius:4px;font-size:1rem;cursor:pointer;width:100%}
        button:hover{background:#002a5c}
        .error{color:#dc3232;margin-bottom:1rem;font-size:.9rem}
    </style>
</head>
<body>
    <div class="login-box">
        <h1>CampusOS License Server</h1>
        <?php if(!empty($error)):?><p class="error"><?=htmlspecialchars($error)?></p><?php endif;?>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required />
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required />
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
