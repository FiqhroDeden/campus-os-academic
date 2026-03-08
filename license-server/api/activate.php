<?php
require_once __DIR__ . '/../db.php';

$input = json_decode( file_get_contents( 'php://input' ), true );
$license_key = trim( $input['license_key'] ?? '' );
$domain      = trim( $input['domain'] ?? '' );

if ( empty( $license_key ) || empty( $domain ) ) {
    http_response_code( 400 );
    echo json_encode( [ 'success' => false, 'message' => 'License key and domain are required.' ] );
    exit;
}

$license = DB::fetch( 'SELECT * FROM licenses WHERE license_key = ?', [ $license_key ] );

if ( ! $license ) {
    http_response_code( 404 );
    echo json_encode( [ 'success' => false, 'message' => 'License key tidak valid.' ] );
    exit;
}

if ( $license['status'] === 'revoked' ) {
    http_response_code( 403 );
    echo json_encode( [ 'success' => false, 'message' => 'License telah dicabut.' ] );
    exit;
}

if ( ! empty( $license['activated_domain'] ) && $license['activated_domain'] !== $domain ) {
    http_response_code( 409 );
    echo json_encode( [
        'success' => false,
        'message' => 'License sudah aktif di domain lain: ' . $license['activated_domain'] . '. Nonaktifkan dulu sebelum pindah domain.',
    ] );
    exit;
}

$now        = date( 'Y-m-d H:i:s' );
$expires_at = date( 'Y-m-d H:i:s', strtotime( '+1 year' ) );

if ( ! empty( $license['expires_at'] ) && $license['activated_domain'] === $domain ) {
    $expires_at = $license['expires_at'];
}

DB::query(
    'UPDATE licenses SET activated_domain = ?, activated_at = ?, expires_at = ?, status = ? WHERE id = ?',
    [ $domain, $now, $expires_at, 'active', $license['id'] ]
);

DB::query(
    'INSERT INTO api_logs (endpoint, license_key, domain, ip_address, response_code) VALUES (?, ?, ?, ?, ?)',
    [ 'activate', $license_key, $domain, $_SERVER['REMOTE_ADDR'] ?? '', 200 ]
);

echo json_encode( [
    'success'    => true,
    'message'    => 'License activated successfully.',
    'status'     => 'active',
    'expires_at' => $expires_at,
    'domain'     => $domain,
] );
