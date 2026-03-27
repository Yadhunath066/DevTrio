
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/DBConnection.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    // Find user by username
    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }
    
    // Find user by ID
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    // Create staff user
    public function createStaff($username, $password, $staff_id) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role, staff_id, created_at) 
                VALUES (:username, :password, 'staff', :staff_id, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':username' => $username,
            ':password' => $hashedPassword,
            ':staff_id' => $staff_id
        ]);
    }
    
    // Get all staff users
    public function getAllStaff() {
        $sql = "SELECT u.*, s.Name as staff_name 
                FROM users u
                LEFT JOIN Staff s ON u.staff_id = s.StaffID
                WHERE u.role = 'staff'";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
?>