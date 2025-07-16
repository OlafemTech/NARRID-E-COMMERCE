<?php
// db.php – simple PDO singleton. Update credentials before deploying.
class DB {
    private const HOST = 'localhost';
    private const NAME = 'narrid_db';
    private const USER = 'db_user';
    private const PASS = 'db_pass';

    private static ?\PDO $instance = null;

    public static function instance(): \PDO {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::NAME . ';charset=utf8mb4';
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$instance = new \PDO($dsn, self::USER, self::PASS, $options);
        }
        return self::$instance;
    }
}
?>
