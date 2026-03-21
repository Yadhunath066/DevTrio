<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session is already started in index.php

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
                    <a href="index.php?url=admin/programmes" style="color: white; text-decoration: underline;">Manage</a>
                </div>
                <div style="background: #48bb78; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                    <h2 style="font-size: 2rem;"><?php echo $moduleCount; ?></h2>
                    <p>Modules</p>
                    <a href="index.php?url=admin/modules" style="color: white; text-decoration: underline;">Manage</a>
                </div>
                <div style="background: #ed8936; color: white; padding: 20px; border-radius: 10px; text-align: center;">
                    <h2 style="font-size: 2rem;"><?php echo $interestCount; ?></h2>
                    <p>Interested Students</p>
                    <a href="index.php?url=admin/interests" style="color: white; text-decoration: underline;">View</a>
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
    
    // ========== PROGRAMME MANAGEMENT ==========
    
    public function programmes() {
        $sql = "SELECT p.*, l.LevelName 
                FROM Programmes p
                JOIN Levels l ON p.LevelID = l.LevelID
                ORDER BY p.ProgrammeID";
        $programmes = $this->db->query($sql)->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #2d3748;">Manage Programmes</h1>
                <a href="index.php?url=admin/programme_add" style="background: #48bb78; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Add New Programme</a>
            </div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Programme Name</th>
                        <th style="padding: 12px; text-align: left;">Level</th>
                        <th style="padding: 12px; text-align: left;">Published</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($programmes as $p): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px;"><?php echo $p['ProgrammeID']; ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($p['ProgrammeName']); ?></td>
                        <td style="padding: 12px;"><?php echo $p['LevelName']; ?></td>
                        <td style="padding: 12px;">
                            <span style="background: <?php echo isset($p['published']) && $p['published'] ? '#48bb78' : '#f56565'; ?>; color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.8rem;">
                                <?php echo isset($p['published']) && $p['published'] ? 'Published' : 'Unpublished'; ?>
                            </span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <a href="index.php?url=admin/programme_edit&id=<?php echo $p['ProgrammeID']; ?>" style="background: #4299e1; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;">Edit</a>
                            <a href="index.php?url=admin/programme_delete&id=<?php echo $p['ProgrammeID']; ?>" onclick="return confirm('Are you sure?')" style="background: #f56565; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <a href="index.php?url=admin/dashboard" style="color: #667eea;">← Back to Dashboard</a>
            </div>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    public function programme_add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $levelId = $_POST['level_id'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $published = isset($_POST['published']) ? 1 : 0;
            
            if(!empty($name) && !empty($levelId)) {
                $sql = "INSERT INTO Programmes (ProgrammeName, LevelID, Description, published) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$name, $levelId, $description, $published]);
                
                $_SESSION['success'] = "Programme added successfully!";
                header('Location: index.php?url=admin/programmes');
                exit;
            } else {
                $_SESSION['error'] = "Please fill all required fields";
            }
        }
        
        // Get levels for dropdown
        $levels = $this->db->query("SELECT * FROM Levels")->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            <h1 style="color: #2d3748; margin-bottom: 20px;">Add New Programme</h1>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div style="background: #fed7d7; color: #c53030; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Programme Name *</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Level *</label>
                    <select name="level_id" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <option value="">Select Level</option>
                        <?php foreach($levels as $level): ?>
                        <option value="<?php echo $level['LevelID']; ?>"><?php echo $level['LevelName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;"></textarea>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: inline-block;">
                        <input type="checkbox" name="published" value="1"> Publish immediately
                    </label>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #48bb78; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Save Programme</button>
                    <a href="index.php?url=admin/programmes" style="background: #a0aec0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    public function programme_edit() {
        $id = $_GET['id'] ?? 0;
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $levelId = $_POST['level_id'] ?? '';
            $description = trim($_POST['description'] ?? '');
            $published = isset($_POST['published']) ? 1 : 0;
            
            if(!empty($name) && !empty($levelId)) {
                $sql = "UPDATE Programmes SET ProgrammeName = ?, LevelID = ?, Description = ?, published = ? 
                        WHERE ProgrammeID = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$name, $levelId, $description, $published, $id]);
                
                $_SESSION['success'] = "Programme updated successfully!";
                header('Location: index.php?url=admin/programmes');
                exit;
            }
        }
        
        // Get programme data
        $sql = "SELECT * FROM Programmes WHERE ProgrammeID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $programme = $stmt->fetch();
        
        if(!$programme) {
            header('Location: index.php?url=admin/programmes');
            exit;
        }
        
        // Get levels for dropdown
        $levels = $this->db->query("SELECT * FROM Levels")->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            <h1 style="color: #2d3748; margin-bottom: 20px;">Edit Programme</h1>
            
            <form method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Programme Name *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($programme['ProgrammeName']); ?>" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Level *</label>
                    <select name="level_id" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <?php foreach($levels as $level): ?>
                        <option value="<?php echo $level['LevelID']; ?>" <?php echo $programme['LevelID'] == $level['LevelID'] ? 'selected' : ''; ?>>
                            <?php echo $level['LevelName']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;"><?php echo htmlspecialchars($programme['Description']); ?></textarea>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: inline-block;">
                        <input type="checkbox" name="published" value="1" <?php echo isset($programme['published']) && $programme['published'] ? 'checked' : ''; ?>> Publish
                    </label>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #4299e1; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Update Programme</button>
                    <a href="index.php?url=admin/programmes" style="background: #a0aec0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    public function programme_delete() {
        $id = $_GET['id'] ?? 0;
        
        // First delete related ProgrammeModules
        $this->db->prepare("DELETE FROM ProgrammeModules WHERE ProgrammeID = ?")->execute([$id]);
        
        // Then delete the programme
        $this->db->prepare("DELETE FROM Programmes WHERE ProgrammeID = ?")->execute([$id]);
        
        $_SESSION['success'] = "Programme deleted successfully!";
        header('Location: index.php?url=admin/programmes');
        exit;
    }
}
?>