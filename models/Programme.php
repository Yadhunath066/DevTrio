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
                JOIN Levels l ON p.LevelID = l.LevelID";
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
                LEFT JOIN ProgrammeModules pm ON p.ProgrammeID = pm.ProgrammeID
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
    
    // Search programmes - SIMPLIFIED
    public function search($keyword) {
        $searchTerm = "%$keyword%";
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                WHERE p.ProgrammeName LIKE ? OR p.Description LIKE ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    // Filter by level - SIMPLIFIED
    public function getByLevel($level) {
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                WHERE l.LevelName = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$level]);
        return $stmt->fetchAll();
    }
}
?>