<?php
require_once __DIR__ . '/../db.php';

$config = require __DIR__ . '/../config.php';

$slug        = $_GET['slug'] ?? '';
$version     = $_GET['version'] ?? '';
$license_key = $_GET['license_key'] ?? '';

$product = $config['products'][ $slug ] ?? null;
if ( ! $product ) {
    echo json_encode( [] );
    exit;
}

if ( ! empty( $version ) && version_compare( $product['current_version'], $version, '<=' ) ) {
    echo json_encode( [] );
    exit;
}

$download_url = '';
if ( ! empty( $license_key ) ) {
    $license = DB::fetch(
        'SELECT * FROM licenses WHERE license_key = ? AND status = ?',
        [ $license_key, 'active' ]
    );
    if ( $license && ( empty( $license['expires_at'] ) || strtotime( $license['expires_at'] ) > time() ) ) {
        $download_url = $product['download_url'];
    }
}

echo json_encode( [
    'name'         => $product['name'],
    'version'      => $product['current_version'],
    'download_url' => $download_url,
    'changelog_url' => $product['changelog_url'],
    'tested'       => '6.9',
    'requires'     => '6.0',
    'requires_php' => '8.0',
] );
