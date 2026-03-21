<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session is already started in index.php - don't start again!

require_once __DIR__ . '/../config/DBConnection.php';

class AdminController {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    public function dashboard() {
        // Get stats
        $progCount = $this->db->query("SELECT COUNT(*) FROM Programmes")->fetchColumn();
        $moduleCount = $this->db->query("SELECT COUNT(*) FROM Modules")->fetchColumn();
        $interestCount = $this->db->query("SELECT COUNT(*) FROM InterestedStudents")->fetchColumn();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h1 style="color: #2d3748; margin-bottom: 20px;">Admin Dashboard</h1>
            <p style="margin-bottom: 30px;">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                <div style="background: #667eea; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                    <h2 style="font-size: 2rem;"><?php echo $progCount; ?></h2>
                    <p>Programmes</p>
                </div>
                <div style="background: #48bb78; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                    <h2 style="font-size: 2rem;"><?php echo $moduleCount; ?></h2>
                    <p>Modules</p>
                </div>
                <div style="background: #ed8936; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                    <h2 style="font-size: 2rem;"><?php echo $interestCount; ?></h2>
                    <p>Interested Students</p>
                </div>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="index.php?url=logout" style="padding: 10px 20px; background: #f56565; color: white; text-decoration: none; border-radius: 5px;">Logout</a>
            </div>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
}
?>