<?php
require_once __DIR__ . '/../config/DBConnection.php';

class Interest {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    // Save student interest
    public function save($studentName, $email, $programmeId) {
        // Check for duplicate
        if($this->checkDuplicate($email, $programmeId)) {
            return false;
        }
        
        $sql = "INSERT INTO InterestedStudents (StudentName, Email, ProgrammeID) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$studentName, $email, $programmeId]);
    }
    
    // Check if student already registered for this programme
    private function checkDuplicate($email, $programmeId) {
        $sql = "SELECT COUNT(*) FROM InterestedStudents 
                WHERE Email = ? AND ProgrammeID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $programmeId]);
        return $stmt->fetchColumn() > 0;
    }
    
    // Get all interests
    public function getAll() {
        $sql = "SELECT i.*, p.ProgrammeName 
                FROM InterestedStudents i
                JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
                ORDER BY i.RegisteredAt DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Delete interest
    public function delete($id) {
        $sql = "DELETE FROM InterestedStudents WHERE InterestID = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>