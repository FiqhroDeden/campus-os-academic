<?php
require_once __DIR__ . '/../db.php';

$input = json_decode( file_get_contents( 'php://input' ), true );
$license_key = trim( $input['license_key'] ?? '' );
$domain      = trim( $input['domain'] ?? '' );

if ( empty( $license_key ) ) {
    http_response_code( 400 );
    echo json_encode( [ 'success' => false, 'message' => 'License key is required.' ] );
    exit;
}

$license = DB::fetch( 'SELECT * FROM licenses WHERE license_key = ?', [ $license_key ] );

if ( ! $license ) {
    http_response_code( 404 );
    echo json_encode( [ 'success' => false, 'message' => 'License key tidak ditemukan.' ] );
    exit;
}

DB::query(
    'UPDATE licenses SET activated_domain = NULL, activated_at = NULL, status = ? WHERE id = ?',
    [ 'inactive', $license['id'] ]
);

DB::query(
    'INSERT INTO api_logs (endpoint, license_key, domain, ip_address, response_code) VALUES (?, ?, ?, ?, ?)',
    [ 'deactivate', $license_key, $domain, $_SERVER['REMOTE_ADDR'] ?? '', 200 ]
);

echo json_encode( [ 'success' => true, 'message' => 'License deactivated. Domain binding cleared.' ] );
