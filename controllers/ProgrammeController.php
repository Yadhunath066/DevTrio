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
        
        <h2 style="color: #2d3748; margin-bottom: 30px; font-size: 2.2rem; border-bottom: 3px solid #667eea; padding-bottom: 15px;">Featured Programmes</h2>
        
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
    
    // SHOW ALL PROGRAMMES with FILTER and SEARCH
    public function programmes() {
        // Get all programmes
        $allProgrammes = $this->programmeModel->getAll();
        
        // Get filter and search from URL
        $filter = $_GET['filter'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        // Apply filter (UG/PG)
        if ($filter == 'ug') {
            $allProgrammes = array_filter($allProgrammes, function($p) {
                return $p['LevelName'] == 'Undergraduate';
            });
        } elseif ($filter == 'pg') {
            $allProgrammes = array_filter($allProgrammes, function($p) {
                return $p['LevelName'] == 'Postgraduate';
            });
        }
        
        // Apply search
        if (!empty($search)) {
            $allProgrammes = array_filter($allProgrammes, function($p) use ($search) {
                return stripos($p['ProgrammeName'], $search) !== false || 
                       stripos($p['Description'], $search) !== false;
            });
        }
        
        // Separate by level for display
        $undergraduate = [];
        $postgraduate = [];
        foreach($allProgrammes as $p) {
            if($p['LevelName'] == 'Undergraduate') {
                $undergraduate[] = $p;
            } else {
                $postgraduate[] = $p;
            }
        }
        
        ob_start();
        ?>
        
        <!-- Filter and Search Bar -->
        <div class="filter-search-bar">
            <div class="filters">
                <a href="index.php?url=programmes&filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                <a href="index.php?url=programmes&filter=ug" class="filter-btn <?php echo $filter == 'ug' ? 'active' : ''; ?>">Undergraduate</a>
                <a href="index.php?url=programmes&filter=pg" class="filter-btn <?php echo $filter == 'pg' ? 'active' : ''; ?>">Postgraduate</a>
            </div>
            
            <form method="GET" class="search-form">
                <input type="hidden" name="url" value="programmes">
                <?php if($filter != 'all'): ?>
                    <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                <?php endif; ?>
                <input type="text" name="search" placeholder="Search programmes..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">🔍 Search</button>
                <?php if(!empty($search)): ?>
                    <a href="index.php?url=programmes&filter=<?php echo $filter; ?>" class="clear-btn">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Results count -->
        <div class="results-count">
            Found <?php echo count($undergraduate) + count($postgraduate); ?> programme(s)
        </div>
        
        <div class="programme-section">
            <h2>Undergraduate Programmes</h2>
            <div class="programme-grid">
                <?php if(empty($undergraduate)): ?>
                    <p class="no-results">No undergraduate programmes found.</p>
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
                    <p class="no-results">No postgraduate programmes found.</p>
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
        
        <style>
            .filter-search-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 30px;
                padding: 20px;
                background: white;
                border-radius: 15px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }
            
            .filters {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            .filter-btn {
                padding: 10px 24px;
                background: #e2e8f0;
                color: #2d3748;
                border-radius: 30px;
                text-decoration: none;
                transition: all 0.3s;
                font-weight: 500;
            }
            
            .filter-btn:hover {
                background: #667eea;
                color: white;
                transform: translateY(-2px);
            }
            
            .filter-btn.active {
                background: #667eea;
                color: white;
            }
            
            .search-form {
                display: flex;
                gap: 10px;
                align-items: center;
                flex-wrap: wrap;
            }
            
            .search-form input {
                padding: 10px 16px;
                border: 2px solid #e2e8f0;
                border-radius: 30px;
                width: 250px;
                font-size: 1rem;
            }
            
            .search-form input:focus {
                outline: none;
                border-color: #667eea;
            }
            
            .search-form button {
                padding: 10px 24px;
                background: #667eea;
                color: white;
                border: none;
                border-radius: 30px;
                cursor: pointer;
                font-weight: 500;
            }
            
            .search-form button:hover {
                background: #5a67d8;
            }
            
            .clear-btn {
                padding: 10px 20px;
                background: #e2e8f0;
                color: #2d3748;
                border-radius: 30px;
                text-decoration: none;
            }
            
            .results-count {
                margin-bottom: 20px;
                color: #718096;
                font-size: 0.9rem;
            }
            
            .no-results {
                text-align: center;
                padding: 40px;
                color: #718096;
                background: white;
                border-radius: 10px;
            }
            
            @media (max-width: 768px) {
                .filter-search-bar {
                    flex-direction: column;
                    align-items: stretch;
                }
                
                .filters {
                    justify-content: center;
                }
                
                .search-form {
                    justify-content: center;
                }
                
                .search-form input {
                    width: 100%;
                }
            }
        </style>
        
        <?php
        $content = ob_get_clean();
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
        
        // Get modules
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
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
}
?>