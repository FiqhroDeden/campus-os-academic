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
