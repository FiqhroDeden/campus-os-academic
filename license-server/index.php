<?php
header( 'Content-Type: application/json; charset=utf-8' );

$uri    = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$method = $_SERVER['REQUEST_METHOD'];
$uri    = rtrim( $uri, '/' );

// Remove base path if in subdirectory
$base = dirname( $_SERVER['SCRIPT_NAME'] );
if ( $base !== '/' && $base !== '\\' ) {
    $uri = substr( $uri, strlen( $base ) );
}

$routes = [
    'POST /api/activate'      => 'api/activate.php',
    'POST /api/validate'      => 'api/validate.php',
    'POST /api/deactivate'    => 'api/deactivate.php',
    'GET /api/check-update'   => 'api/check-update.php',
    'GET /api/check'          => 'api/check-update.php',
    'GET /api/download'       => 'api/download.php',
    'GET /api/info'           => 'api/info.php',
];

$route_key = $method . ' ' . $uri;

if ( isset( $routes[ $route_key ] ) ) {
    require __DIR__ . '/' . $routes[ $route_key ];
} elseif ( strpos( $uri, '/admin' ) === 0 ) {
    header( 'Content-Type: text/html; charset=utf-8' );
    if ( $uri === '/admin' || $uri === '/admin/' ) {
        require __DIR__ . '/admin/index.php';
    } elseif ( $uri === '/admin/login' ) {
        require __DIR__ . '/admin/login.php';
    } elseif ( $uri === '/admin/releases' ) {
        require __DIR__ . '/admin/releases.php';
    } else {
        http_response_code( 404 );
        echo '<h1>404 Not Found</h1>';
    }
} else {
    http_response_code( 404 );
    echo json_encode( [ 'error' => 'Not found' ] );
}
