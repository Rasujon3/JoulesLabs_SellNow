<?php

namespace SellNow\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $conn;

    /*
    private $host = '127.0.0.1'; // TODO: Move to env
    private $db_name = 'sellnow_db';
    private $username = 'root';
    private $password = ''; // user might need to change this
    private $charset = 'utf8mb4';
    */

    private function __construct()
    {
        // Load environment variables
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $driver = $_ENV['DB_CONNECTION'] ?? 'mysql';
        try {
            if ($driver === 'mysql') {
                $this->connectMySQL();
            } elseif ($driver === 'sqlite') {
                $this->connectSQLite();
            } else {
                throw new \Exception("Unsupported database driver: {$driver}");
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            die("Database connection failed.");
        }
    }

    private function connectMySQL()
    {
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        $this->conn = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    private function connectSQLite()
    {
        $dbPath = __DIR__ . '/../../database/database.sqlite';
        $this->conn = new PDO("sqlite:" . $dbPath);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // Helper to just run a query
    public function query($sql)
    {
        return $this->conn->query($sql); // No preparation? Risk!
    }
}
