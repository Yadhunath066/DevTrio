<?php
require_once __DIR__ . '/../config/DBConnection.php';

class Module {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    // Get modules by programme (for student view)
    public function getByProgramme($programmeId) {
        $sql = "SELECT m.*, s.Name as LeaderName, pm.Year
                FROM Modules m
                LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
                LEFT JOIN ProgrammeModules pm ON m.ModuleID = pm.ModuleID
                WHERE pm.ProgrammeID = ?
                ORDER BY pm.Year, m.ModuleName";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$programmeId]);
        return $stmt->fetchAll();
    }
    
    // Get all modules (for admin)
    public function getAll() {
        $sql = "SELECT m.*, p.ProgrammeName, s.Name as LeaderName 
                FROM Modules m
                LEFT JOIN ProgrammeModules pm ON m.ModuleID = pm.ModuleID
                LEFT JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
                LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
                GROUP BY m.ModuleID
                ORDER BY m.ModuleID";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get module by ID (for editing)
    public function getById($id) {
        $sql = "SELECT m.*, pm.ProgrammeID, pm.Year 
                FROM Modules m
                LEFT JOIN ProgrammeModules pm ON m.ModuleID = pm.ModuleID
                WHERE m.ModuleID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Create module
    public function create($name, $programmeId, $staffId, $description, $year) {
        $sql = "INSERT INTO Modules (ModuleName, ModuleLeaderID, Description, Year) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $staffId ?: null, $description, $year]);
        
        $moduleId = $this->db->lastInsertId();
        
        // Link to programme
        $sql2 = "INSERT INTO ProgrammeModules (ProgrammeID, ModuleID, Year) VALUES (?, ?, ?)";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute([$programmeId, $moduleId, $year]);
        
        return $moduleId;
    }
    
    // Update module
    public function update($id, $name, $programmeId, $staffId, $description, $year) {
        $sql = "UPDATE Modules SET ModuleName = ?, ModuleLeaderID = ?, Description = ?, Year = ? 
                WHERE ModuleID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $staffId ?: null, $description, $year, $id]);
        
        // Update programme link
        $sql2 = "UPDATE ProgrammeModules SET ProgrammeID = ?, Year = ? WHERE ModuleID = ?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->execute([$programmeId, $year, $id]);
        
        return true;
    }
    
    // Delete module
    public function delete($id) {
        $this->db->prepare("DELETE FROM ProgrammeModules WHERE ModuleID = ?")->execute([$id]);
        $this->db->prepare("DELETE FROM Modules WHERE ModuleID = ?")->execute([$id]);
        return true;
    }
    
    // Get modules by staff ID (for staff dashboard)
    public function getByStaffId($staff_id) {
        $sql = "SELECT m.*, s.Name as ModuleLeaderName
                FROM Modules m
                LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
                WHERE m.ModuleLeaderID = ?
                ORDER BY m.ModuleName";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$staff_id]);
        return $stmt->fetchAll();
    }
}
?>