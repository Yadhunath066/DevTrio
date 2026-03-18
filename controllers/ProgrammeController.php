<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fix the path to models - use correct relative path
require_once __DIR__ . '/../models/Programme.php';

class ProgrammeController {
    private $programmeModel;
    
       public function __construct() {
        $this->programmeModel = new Programme();
    }
    
    // HOME PAGE with welcome banner & featured programmes
    public function home() {
        $programmes = $this->programmeModel->getAll();
        
        // Take first 3 programmes as featured
        $featured = array_slice($programmes, 0, 3);
        
        ob_start();
        ?>
        
        <div class="welcome-section" style="text-align: center; margin-bottom: 50px; padding: 60px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);">
            <h1 style="font-size: 3.2rem; margin-bottom: 20px; font-weight: 700;">Welcome to Student Course Hub</h1>
            <p style="font-size: 1.4rem; max-width: 800px; margin: 0 auto 30px; opacity: 0.95; line-height: 1.6;">Discover your perfect programme at our university. Explore undergraduate and postgraduate courses designed for your future.</p>
            <a href="index.php?url=programmes" style="display: inline-block; background: white; color: #667eea; padding: 16px 45px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 1.2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">Browse All Programmes →</a>
        </div>
        
        <h2 style="color: #2d3748; margin-bottom: 30px; font-size: 2.2rem; border-bottom: 3px solid #667eea; padding-bottom: 15px;">🌟 Featured Programmes</h2>
        
        <div class="programme-grid">
            <?php foreach($featured as $prog): ?>
            <div class="programme-card">
                <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 120)) . '...'; ?></p>
                <p><strong>Level:</strong> <span style="background: #667eea; color: white; padding: 3px 10px; border-radius: 20px;"><?php echo $prog['LevelName']; ?></span></p>
                <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details →</a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin: 50px 0;">
            <a href="index.php?url=programmes" class="btn" style="padding: 14px 40px;">View All Programmes →</a>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
         
    
    // Show all programmes (homepage)
    public function programmes() {
        $programmes = $this->programmeModel->getAll();
        
        // Separate by level
        $undergraduate = [];
        $postgraduate = [];
        
        foreach($programmes as $p) {
            if($p['LevelName'] == 'Undergraduate') {
                $undergraduate[] = $p;
            } else {
                $postgraduate[] = $p;
            }
        }
        
        // Start output buffering
        ob_start();
        ?>
        
        <div class="programme-section">
            <h2>Undergraduate Programmes</h2>
            <div class="programme-grid">
                <?php if(empty($undergraduate)): ?>
                    <p>No undergraduate programmes available.</p>
                <?php else: ?>
                    <?php foreach($undergraduate as $prog): ?>
                    <div class="programme-card">
                        <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 100)) . '...'; ?></p>
                        <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details</a>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <h2>Postgraduate Programmes</h2>
            <div class="programme-grid">
                <?php if(empty($postgraduate)): ?>
                    <p>No postgraduate programmes available.</p>
                <?php else: ?>
                    <?php foreach($postgraduate as $prog): ?>
                    <div class="programme-card">
                        <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 100)) . '...'; ?></p>
                        <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details</a>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <?php
        // Get the buffered content and clean the buffer
        $content = ob_get_clean();
        
        // Include the layout which will display $content
        require_once __DIR__ . '/../views/layout.php';
    }
    
    // Show single programme with modules
    public function show($id) {
        $programmeData = $this->programmeModel->getByIdWithModules($id);
        
        if(!$programmeData) {
            ob_start();
            echo "<h1>Programme Not Found</h1>";
            echo "<p>The programme you're looking for doesn't exist.</p>";
            echo '<a href="index.php?url=home" class="btn">Back to Home</a>';
            $content = ob_get_clean();
            require_once __DIR__ . '/../views/layout.php';
            return;
        }
        
        // Extract programme info
        $programme = [
            'ProgrammeID' => $programmeData['ProgrammeID'],
            'ProgrammeName' => $programmeData['ProgrammeName'],
            'Description' => $programmeData['Description'],
            'LevelName' => $programmeData['LevelName']
        ];
        
        // Get modules - FIXED version
        $modules = [];
        if(isset($programmeData['modules']) && is_array($programmeData['modules'])) {
            foreach($programmeData['modules'] as $mod) {
                $modules[] = [
                    'name' => $mod['ModuleName'] ?? 'Unknown Module',
                    'description' => $mod['ModuleDescription'] ?? 'No description available',
                    'leader' => $mod['ModuleLeaderName'] ?? 'TBA',
                    'year' => $mod['Year'] ?? 1
                ];
            }
        }
        
        // Start output buffering
        ob_start();
        ?>
        
        <div class="programme-detail">
            <h1><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h1>
            <p class="level"><?php echo htmlspecialchars($programme['LevelName']); ?></p>
            
            <div class="description">
                <h2>Programme Description</h2>
                <p><?php echo htmlspecialchars($programme['Description']); ?></p>
            </div>
            
            <div class="modules">
                <h2>Modules by Year</h2>
                
                <?php if(empty($modules)): ?>
                    <p>No modules available for this programme.</p>
                <?php else: ?>
                    <?php 
                    $currentYear = 0;
                    foreach($modules as $module): 
                        if($module['year'] != $currentYear):
                            if($currentYear != 0) echo '</div>';
                            $currentYear = $module['year'];
                            echo '<div class="year-group">';
                            echo '<h3>Year ' . $currentYear . '</h3>';
                        endif;
                    ?>
                    
                    <div class="module-card">
                        <h4><?php echo htmlspecialchars($module['name']); ?></h4>
                        <p><?php echo htmlspecialchars($module['description']); ?></p>
                        <p class="module-leader">Module Leader: <?php echo htmlspecialchars($module['leader']); ?></p>
                    </div>
                    
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="interest-form">
                <h2>Register Your Interest</h2>
                <form action="index.php?url=interest" method="POST">
                    <input type="hidden" name="programme_id" value="<?php echo $programme['ProgrammeID']; ?>">
                    
                    <div class="form-group">
                        <label for="name">Your Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Your Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <button type="submit" class="btn">Register Interest</button>
                </form>
                <p style="margin-top: 15px; font-style: italic; color: #666;">
                    Note: Interest registration is currently in demo mode.
                </p>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="index.php?url=home" class="btn">← Back to All Programmes</a>
            </div>
        </div>
        
        <?php
        // Get the buffered content and clean the buffer
        $content = ob_get_clean();
        
        // Include the layout which will display $content
        require_once __DIR__ . '/../views/layout.php';
    }
}
?>