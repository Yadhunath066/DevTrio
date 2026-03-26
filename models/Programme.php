<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/DBConnection.php';

class Programme {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    // Get all programmes
    public function getAll() {
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                WHERE p.published = 1";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Get programme by ID with modules
    public function getByIdWithModules($id) {
        $sql = "SELECT p.*, l.LevelName,
                       m.ModuleID, m.ModuleName, m.Description as ModuleDescription,
                       s.Name as ModuleLeaderName,
                       pm.Year
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                LEFT JOIN programme_modules pm ON p.ProgrammeID = pm.ProgrammeID
                LEFT JOIN Modules m ON pm.ModuleID = m.ModuleID
                LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
                WHERE p.ProgrammeID = ?
                ORDER BY pm.Year, m.ModuleName";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $results = $stmt->fetchAll();
        
        if(empty($results)) {
            return null;
        }
        
        // Build programme data structure
        $programme = [
            'ProgrammeID' => $results[0]['ProgrammeID'],
            'ProgrammeName' => $results[0]['ProgrammeName'],
            'Description' => $results[0]['Description'],
            'LevelName' => $results[0]['LevelName'],
            'modules' => []
        ];
        
        foreach($results as $row) {
            if($row['ModuleID']) {
                $programme['modules'][] = [
                    'ModuleName' => $row['ModuleName'],
                    'ModuleDescription' => $row['ModuleDescription'],
                    'ModuleLeaderName' => $row['ModuleLeaderName'],
                    'Year' => $row['Year']
                ];
            }
        }
        
        return $programme;
    }
    
    // Search programmes
    public function search($keyword) {
        $searchTerm = "%$keyword%";
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                WHERE (p.ProgrammeName LIKE ? OR p.Description LIKE ?) 
                AND p.published = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    // Filter by level
    public function getByLevel($level) {
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                WHERE l.LevelName = ? AND p.published = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$level]);
        return $stmt->fetchAll();
    }
    
    // Get programmes that contain a specific module (for staff dashboard)
    public function getByModuleId($module_id) {
        $sql = "SELECT p.ProgrammeID, p.ProgrammeName, p.Description, l.LevelName, pm.Year
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                JOIN programme_modules pm ON p.ProgrammeID = pm.ProgrammeID
                WHERE pm.ModuleID = ?
                ORDER BY pm.Year, p.ProgrammeName";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$module_id]);
        return $stmt->fetchAll();
    }
    
    // Get all programmes for admin (including unpublished)
    public function getAllForAdmin() {
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                ORDER BY p.ProgrammeID";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    // Create new programme (admin)
    public function create($data) {
        $sql = "INSERT INTO Programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, published) 
                VALUES (:name, :level_id, :leader_id, :description, :image, :published)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':level_id' => $data['level_id'],
            ':leader_id' => $data['leader_id'],
            ':description' => $data['description'],
            ':image' => $data['image'] ?? null,
            ':published' => $data['published'] ?? 1
        ]);
    }
    
    // Update programme (admin)
    public function update($id, $data) {
        $sql = "UPDATE Programmes 
                SET ProgrammeName = :name, 
                    LevelID = :level_id, 
                    ProgrammeLeaderID = :leader_id, 
                    Description = :description, 
                    Image = :image, 
                    published = :published 
                WHERE ProgrammeID = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'],
            ':level_id' => $data['level_id'],
            ':leader_id' => $data['leader_id'],
            ':description' => $data['description'],
            ':image' => $data['image'] ?? null,
            ':published' => $data['published'] ?? 1
        ]);
    }
    
    // Delete programme (admin)
    public function delete($id) {
        $sql = "DELETE FROM Programmes WHERE ProgrammeID = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Toggle publish status (admin)
    public function togglePublish($id) {
        $sql = "UPDATE Programmes SET published = NOT published WHERE ProgrammeID = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>