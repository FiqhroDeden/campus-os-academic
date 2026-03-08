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
    echo json_encode( [ 'success' => false, 'status' => 'invalid', 'message' => 'License key tidak ditemukan.' ] );
    exit;
}

$status = $license['status'];
if ( $status === 'active' && ! empty( $license['expires_at'] ) && strtotime( $license['expires_at'] ) < time() ) {
    $status = 'expired';
    DB::query( 'UPDATE licenses SET status = ? WHERE id = ?', [ 'expired', $license['id'] ] );
}

$domain_match = empty( $domain ) || $license['activated_domain'] === $domain;

echo json_encode( [
    'success'      => true,
    'status'       => $status,
    'expires_at'   => $license['expires_at'] ?? '',
    'domain'       => $license['activated_domain'] ?? '',
    'domain_match' => $domain_match,
] );
