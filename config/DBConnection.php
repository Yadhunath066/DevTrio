<?php
class DBConnection {
    private $host = 'localhost';
    private $dbname = 'student_course_hub';
    private $username = 'root';      // XAMPP default
    private $password = '';          // XAMPP default (empty)
    private $pdo;
   
    public function __construct() {
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
            die("Connection failed: " . $e->getMessage());
        }
    }
   
    public function getConnection() {
        return $this->pdo;
    }
}
?>
