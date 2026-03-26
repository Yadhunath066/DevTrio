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
        // Get stats - FIXED: changed InterestedStudents to interested_students
        $progCount = $this->db->query("SELECT COUNT(*) FROM Programmes")->fetchColumn();
        $moduleCount = $this->db->query("SELECT COUNT(*) FROM Modules")->fetchColumn();
        $interestCount = $this->db->query("SELECT COUNT(*) FROM interested_students")->fetchColumn();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h1 style="color: #2d3748; margin-bottom: 20px;">Admin Dashboard</h1>
            <p style="margin-bottom: 30px;">Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>!</p>
            
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
        
        // First delete related ProgrammeModules - FIXED: changed to programme_modules
        $this->db->prepare("DELETE FROM programme_modules WHERE ProgrammeID = ?")->execute([$id]);
        
        // Then delete the programme
        $this->db->prepare("DELETE FROM Programmes WHERE ProgrammeID = ?")->execute([$id]);
        
        $_SESSION['success'] = "Programme deleted successfully!";
        header('Location: index.php?url=admin/programmes');
        exit;
    }
    
    // ========== MODULE MANAGEMENT ==========
    
    public function modules() {
        $sql = "SELECT m.*, 
                       pm.ProgrammeID,
                       p.ProgrammeName, 
                       s.Name as StaffName,
                       pm.Year
                FROM Modules m
                LEFT JOIN programme_modules pm ON m.ModuleID = pm.ModuleID
                LEFT JOIN Programmes p ON pm.ProgrammeID = p.ProgrammeID
                LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID
                GROUP BY m.ModuleID
                ORDER BY m.ModuleID";
        $modules = $this->db->query($sql)->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #2d3748;">Manage Modules</h1>
                <a href="index.php?url=admin/module_add" style="background: #48bb78; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Add New Module</a>
            </div>
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left;">ID</th>
                        <th style="padding: 12px; text-align: left;">Module Name</th>
                        <th style="padding: 12px; text-align: left;">Programme</th>
                        <th style="padding: 12px; text-align: left;">Year</th>
                        <th style="padding: 12px; text-align: left;">Module Leader</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($modules as $m): ?>
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px;"><?php echo $m['ModuleID']; ?></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($m['ModuleName']); ?></td>
                        <td style="padding: 12px;"><?php echo $m['ProgrammeName'] ?? 'N/A'; ?></td>
                        <td style="padding: 12px;"><?php echo $m['Year'] ?? 'N/A'; ?></td>
                        <td style="padding: 12px;"><?php echo $m['StaffName'] ?? 'TBA'; ?></td>
                        <td style="padding: 12px; text-align: center;">
                            <a href="index.php?url=admin/module_edit&id=<?php echo $m['ModuleID']; ?>" style="background: #4299e1; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; margin-right: 5px;">Edit</a>
                            <a href="index.php?url=admin/module_delete&id=<?php echo $m['ModuleID']; ?>" onclick="return confirm('Are you sure?')" style="background: #f56565; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;">Delete</a>
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
    
    public function module_add() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $programmeId = $_POST['programme_id'] ?? '';
            $staffId = $_POST['staff_id'] ?? null;
            $description = trim($_POST['description'] ?? '');
            $year = $_POST['year'] ?? 1;
            
            if(!empty($name) && !empty($programmeId)) {
                // First insert into Modules table
                $sql = "INSERT INTO Modules (ModuleName, ModuleLeaderID, Description) 
                        VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$name, $staffId ?: null, $description]);
                
                $newModuleId = $this->db->lastInsertId();
                
                // Then add to programme_modules table to link with programme - FIXED
                $sql2 = "INSERT INTO programme_modules (ProgrammeID, ModuleID, Year) VALUES (?, ?, ?)";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([$programmeId, $newModuleId, $year]);
                
                $_SESSION['success'] = "Module added successfully!";
                header('Location: index.php?url=admin/modules');
                exit;
            } else {
                $_SESSION['error'] = "Please fill all required fields";
            }
        }
        
        // Get programmes and staff for dropdowns
        $programmes = $this->db->query("SELECT * FROM Programmes ORDER BY ProgrammeName")->fetchAll();
        $staff = $this->db->query("SELECT * FROM Staff ORDER BY Name")->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            <h1 style="color: #2d3748; margin-bottom: 20px;">Add New Module</h1>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div style="background: #fed7d7; color: #c53030; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Module Name *</label>
                    <input type="text" name="name" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Programme *</label>
                    <select name="programme_id" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <option value="">Select Programme</option>
                        <?php foreach($programmes as $p): ?>
                        <option value="<?php echo $p['ProgrammeID']; ?>"><?php echo htmlspecialchars($p['ProgrammeName']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Year</label>
                    <select name="year" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <option value="1">Year 1</option>
                        <option value="2">Year 2</option>
                        <option value="3">Year 3</option>
                        <option value="4">Year 4</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Module Leader</label>
                    <select name="staff_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <option value="">Select Staff (Optional)</option>
                        <?php foreach($staff as $s): ?>
                        <option value="<?php echo $s['StaffID']; ?>"><?php echo htmlspecialchars($s['Name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;"></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #48bb78; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Save Module</button>
                    <a href="index.php?url=admin/modules" style="background: #a0aec0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    public function module_edit() {
        $id = $_GET['id'] ?? 0;
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name'] ?? '');
            $programmeId = $_POST['programme_id'] ?? '';
            $staffId = $_POST['staff_id'] ?? null;
            $description = trim($_POST['description'] ?? '');
            $year = $_POST['year'] ?? 1;
            
            if(!empty($name) && !empty($programmeId)) {
                // Update Modules table
                $sql = "UPDATE Modules SET ModuleName = ?, ModuleLeaderID = ?, Description = ? 
                        WHERE ModuleID = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$name, $staffId ?: null, $description, $id]);
                
                // Update programme_modules table - FIXED
                $sql2 = "UPDATE programme_modules SET ProgrammeID = ?, Year = ? WHERE ModuleID = ?";
                $stmt2 = $this->db->prepare($sql2);
                $stmt2->execute([$programmeId, $year, $id]);
                
                $_SESSION['success'] = "Module updated successfully!";
                header('Location: index.php?url=admin/modules');
                exit;
            }
        }
        
        // Get module data with its programme
        $sql = "SELECT m.*, pm.ProgrammeID, pm.Year
                FROM Modules m
                LEFT JOIN programme_modules pm ON m.ModuleID = pm.ModuleID
                WHERE m.ModuleID = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $module = $stmt->fetch();
        
        if(!$module) {
            header('Location: index.php?url=admin/modules');
            exit;
        }
        
        // Get programmes and staff for dropdowns
        $programmes = $this->db->query("SELECT * FROM Programmes ORDER BY ProgrammeName")->fetchAll();
        $staff = $this->db->query("SELECT * FROM Staff ORDER BY Name")->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            <h1 style="color: #2d3748; margin-bottom: 20px;">Edit Module</h1>
            
            <form method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Module Name *</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($module['ModuleName']); ?>" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Programme *</label>
                    <select name="programme_id" required style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <?php foreach($programmes as $p): ?>
                        <option value="<?php echo $p['ProgrammeID']; ?>" <?php echo $module['ProgrammeID'] == $p['ProgrammeID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['ProgrammeName']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Year</label>
                    <select name="year" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <option value="1" <?php echo $module['Year'] == 1 ? 'selected' : ''; ?>>Year 1</option>
                        <option value="2" <?php echo $module['Year'] == 2 ? 'selected' : ''; ?>>Year 2</option>
                        <option value="3" <?php echo $module['Year'] == 3 ? 'selected' : ''; ?>>Year 3</option>
                        <option value="4" <?php echo $module['Year'] == 4 ? 'selected' : ''; ?>>Year 4</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Module Leader</label>
                    <select name="staff_id" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                        <option value="">Select Staff (Optional)</option>
                        <?php foreach($staff as $s): ?>
                        <option value="<?php echo $s['StaffID']; ?>" <?php echo $module['ModuleLeaderID'] == $s['StaffID'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['Name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Description</label>
                    <textarea name="description" rows="5" style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;"><?php echo htmlspecialchars($module['Description']); ?></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #4299e1; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Update Module</button>
                    <a href="index.php?url=admin/modules" style="background: #a0aec0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Cancel</a>
                </div>
            </form>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    public function module_delete() {
        $id = $_GET['id'] ?? 0;
        
        // First delete from programme_modules - FIXED
        $this->db->prepare("DELETE FROM programme_modules WHERE ModuleID = ?")->execute([$id]);
        
        // Then delete the module
        $this->db->prepare("DELETE FROM Modules WHERE ModuleID = ?")->execute([$id]);
        
        $_SESSION['success'] = "Module deleted successfully!";
        header('Location: index.php?url=admin/modules');
        exit;
    }
    
    // ========== INTERESTED STUDENTS MANAGEMENT ==========
    
    public function interests() {
        $sql = "SELECT i.*, p.ProgrammeName 
                FROM interested_students i
                JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
                ORDER BY i.RegisteredAt DESC";
        $students = $this->db->query($sql)->fetchAll();
        
        ob_start();
        ?>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #2d3748;">Interested Students</h1>
                <div>
                    <a href="index.php?url=admin/export_csv" style="background: #48bb78; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;">📥 Export to CSV</a>
                    <a href="index.php?url=admin/dashboard" style="background: #a0aec0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Back</a>
                </div>
            </div>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div style="background: #c6f6d5; color: #22543d; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if(empty($students)): ?>
                <div style="text-align: center; padding: 50px;">
                    <p>No students have registered interest yet.</p>
                </div>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 12px; text-align: left;">ID</th>
                            <th style="padding: 12px; text-align: left;">Student Name</th>
                            <th style="padding: 12px; text-align: left;">Email</th>
                            <th style="padding: 12px; text-align: left;">Programme</th>
                            <th style="padding: 12px; text-align: left;">Registered On</th>
                            <th style="padding: 12px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($students as $s): ?>
                        <tr style="border-bottom: 1px solid #e2e8f0;">
                            <td style="padding: 12px;"><?php echo $s['InterestID']; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($s['StudentName']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($s['Email']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($s['ProgrammeName']); ?></td>
                            <td style="padding: 12px;"><?php echo date('d M Y, H:i', strtotime($s['RegisteredAt'])); ?></td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="index.php?url=admin/interest_delete&id=<?php echo $s['InterestID']; ?>" onclick="return confirm('Are you sure?')" style="background: #f56565; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px;">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div style="margin-top: 20px;">
                    <p><strong>Total:</strong> <?php echo count($students); ?> interested students</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    public function interest_delete() {
        $id = $_GET['id'] ?? 0;
        
        $this->db->prepare("DELETE FROM interested_students WHERE InterestID = ?")->execute([$id]);
        
        $_SESSION['success'] = "Interest record deleted successfully!";
        header('Location: index.php?url=admin/interests');
        exit;
    }
    
    public function export_csv() {
        $sql = "SELECT i.StudentName, i.Email, p.ProgrammeName, i.RegisteredAt 
                FROM interested_students i
                JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
                ORDER BY i.RegisteredAt DESC";
        $students = $this->db->query($sql)->fetchAll();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="interested_students_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, ['Student Name', 'Email', 'Programme', 'Registered Date']);
        
        // Add data rows
        foreach($students as $student) {
            fputcsv($output, [
                $student['StudentName'],
                $student['Email'],
                $student['ProgrammeName'],
                date('d M Y, H:i', strtotime($student['RegisteredAt']))
            ]);
        }
        
        fclose($output);
        exit;
    }
}
?>