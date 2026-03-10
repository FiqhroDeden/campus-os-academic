<?php
if ( ! defined( 'ABSPATH' ) ) exit;

http_response_code( 503 );
header( 'Retry-After: 3600' );
header( 'Content-Type: text/html; charset=utf-8' );
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html( get_bloginfo( 'name' ) ); ?> — Dalam Konfigurasi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f0f2f5;
            color: #1d2327;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 64px;
            height: 64px;
            background: #003d82;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        .icon svg { width: 32px; height: 32px; fill: #fff; }
        h1 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #003d82;
        }
        p {
            font-size: 15px;
            color: #50575e;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background: #003d82;
            color: #fff;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn:hover { background: #002b5c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
        </div>
        <h1>Situs Sedang Dalam Konfigurasi</h1>
        <p>Situs ini sedang dalam proses pengaturan. Silakan kembali beberapa saat lagi.</p>
        <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn">Login Administrator</a>
    </div>
</body>
</html>
<?php exit; ?>
