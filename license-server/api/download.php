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
