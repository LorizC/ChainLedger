<!----connect to database---->
<?php
class Database {
    // Hold a single instance of the mysqli connection
    private static ?mysqli $instance = null;

    // Private constructor prevents direct instantiation
    private function __construct() {}

    // Prevent cloning the instance
    private function __clone() {}

    // Get the database connection
    public static function getConnection(): mysqli {
        if (self::$instance === null) {
            self::$instance = new mysqli("127.0.0.1", "root", "", "ChainledgerDB", "3306");

            if (self::$instance->connect_error) {
                die(" Database connection failed: " . self::$instance->connect_error);
            }

            // set charset for better security and encoding support
            self::$instance->set_charset("utf8mb4");
        }
        return self::$instance;
    }
}
?>