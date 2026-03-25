<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



require_once __DIR__ . '/../config/DBConnection.php';

class StaffController {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
        
        // Check if logged in and is staff
        if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_role'] != 'staff') {
            header('Location: index.php?url=login');
            exit;
        }
    }
    
    // Staff Dashboard - show their modules
    public function dashboard() {
        $staff_id = $_SESSION['staff_id'];
        
        // Get modules where this staff is module leader
        $sql = "SELECT m.*, 
                       s.Name as ModuleLeaderName
                FROM Modules m
                LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
                WHERE m.ModuleLeaderID = :staff_id
                ORDER BY m.ModuleName";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':staff_id' => $staff_id]);
        $modules = $stmt->fetchAll();
        
        ob_start();
        ?>
        
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px;">
                <h1 style="margin: 0 0 10px 0;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 👋</h1>
                <p style="margin: 0; opacity: 0.9;">Here are the modules you are leading this year.</p>
            </div>
            
            <?php if(empty($modules)): ?>
                <div style="background: #fef3c7; padding: 30px; border-radius: 10px; text-align: center;">
                    <p style="margin: 0; font-size: 1.1rem;">You are not assigned as module leader for any modules yet.</p>
                    <p style="margin-top: 10px; color: #718096;">Please contact the administrator.</p>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 20px;">
                    <?php foreach($modules as $module): ?>
                        <?php
                        // Get programmes that include this module
                        $progSql = "SELECT p.ProgrammeName, pm.Year 
                                    FROM ProgrammeModules pm
                                    JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
                                    WHERE pm.ModuleID = :module_id
                                    ORDER BY pm.Year, p.ProgrammeName";
                        $progStmt = $this->db->prepare($progSql);
                        $progStmt->execute([':module_id' => $module['ModuleID']]);
                        $programmes = $progStmt->fetchAll();
                        ?>
                        
                        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-left: 5px solid #667eea; transition: transform 0.2s;">
                            <h3 style="color: #2d3748; margin: 0 0 10px 0; font-size: 1.3rem;"><?php echo htmlspecialchars($module['ModuleName']); ?></h3>
                            <p style="color: #4a5568; margin-bottom: 15px;"><?php echo htmlspecialchars($module['Description'] ?? 'No description available'); ?></p>
                            <p style="margin-bottom: 10px;"><strong>Module Leader:</strong> <?php echo htmlspecialchars($module['ModuleLeaderName'] ?? 'Not assigned'); ?></p>
                            
                            <?php if(!empty($programmes)): ?>
                                <div style="background: #f7fafc; padding: 15px; border-radius: 8px; margin-top: 10px;">
                                    <p style="margin: 0 0 8px 0; font-weight: 600; color: #2d3748;">📚 Taught in these programmes:</p>
                                    <?php foreach($programmes as $prog): ?>
                                        <p style="margin: 5px 0; color: #4a5568;">• <?php echo htmlspecialchars($prog['ProgrammeName']); ?> <span style="color: #718096;">(Year <?php echo $prog['Year']; ?>)</span></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div style="background: #fef3c7; padding: 12px; border-radius: 8px; margin-top: 10px;">
                                    <p style="margin: 0; color: #92400e;">⚠️ Not currently assigned to any programme.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 40px; padding: 20px; background: #f7fafc; border-radius: 10px; text-align: center;">
                <a href="index.php?url=logout" style="background: #e53e3e; color: white; padding: 10px 25px; text-decoration: none; border-radius: 6px; display: inline-block;">Logout</a>
            </div>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
}
?>