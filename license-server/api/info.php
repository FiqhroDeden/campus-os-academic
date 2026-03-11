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
