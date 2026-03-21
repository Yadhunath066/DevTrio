<?php
require_once __DIR__ . '/../config/DBConnection.php';

class Staff {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    // Get all staff
    public function getAll() {
        $sql = "SELECT * FROM Staff ORDER BY Name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get staff by ID
    public function getById($id) {
        $sql = "SELECT * FROM Staff WHERE StaffID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Get modules taught by staff
    public function getModules($staffId) {
        $sql = "SELECT m.*, p.ProgrammeName 
                FROM Modules m
                LEFT JOIN ProgrammeModules pm ON m.ModuleID = pm.ModuleID
                LEFT JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
                WHERE m.ModuleLeaderID = ?
                ORDER BY m.ModuleName";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }
    
    // Create staff
    public function create($name, $email, $bio = null) {
        $sql = "INSERT INTO Staff (Name, Email, Bio) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $email, $bio]);
        return $this->db->lastInsertId();
    }
    
    // Update staff
    public function update($id, $name, $email, $bio = null) {
        $sql = "UPDATE Staff SET Name = ?, Email = ?, Bio = ? WHERE StaffID = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $email, $bio, $id]);
    }
    
    // Delete staff
    public function delete($id) {
        $sql = "DELETE FROM Staff WHERE StaffID = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>