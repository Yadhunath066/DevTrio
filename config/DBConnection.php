<?php
// Database connection for Student Course Hub
// Created by DevTrio Team
// Last updated: March 2026

class DBConnection {
    private static $instance = null;
    private $pdo;
    
    private $host = 'localhost';
    private $dbname = 'student_course_hub';
    private $username = 'root';      // XAMPP default
    private $password = '';          // XAMPP default (empty)
    
    // Singleton pattern - only one connection throughout the application
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Private constructor to prevent multiple instances
    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            // Log error to file for debugging
            error_log("Database connection failed: " . $e->getMessage() . "\n", 3, __DIR__ . "/db_errors.log");
            die("Database connection failed. Please try again later.");
        }
    }
    
    // Get the database connection
    public function getConnection() {
        return $this->pdo;
    }
    
    // Test if database exists and has tables
    public function testConnection() {
        try {
            $stmt = $this->pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll();
            return count($tables) > 0;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Get database info for debugging
    public function getDatabaseInfo() {
        return [
            'host' => $this->host,
            'database' => $this->dbname,
            'connected' => $this->pdo ? true : false,
            'tables_exist' => $this->testConnection()
        ];
    }
    
    // Prevent cloning of the instance
    private function __clone() {}
    
    // Prevent unserializing of the instance
    public function __wakeup() {}
}
?>