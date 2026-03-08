<?php
class DB {
    private static $pdo = null;

    public static function connect() {
        if ( self::$pdo !== null ) return self::$pdo;
        $config = require __DIR__ . '/config.php';
        $db = $config['db'];
        try {
            self::$pdo = new PDO(
                "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}",
                $db['user'],
                $db['password'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch ( PDOException $e ) {
            http_response_code( 500 );
            echo json_encode( [ 'error' => 'Database connection failed' ] );
            exit;
        }
        return self::$pdo;
    }

    public static function query( $sql, $params = [] ) {
        $stmt = self::connect()->prepare( $sql );
        $stmt->execute( $params );
        return $stmt;
    }

    public static function fetch( $sql, $params = [] ) {
        return self::query( $sql, $params )->fetch();
    }

    public static function fetchAll( $sql, $params = [] ) {
        return self::query( $sql, $params )->fetchAll();
    }
}
