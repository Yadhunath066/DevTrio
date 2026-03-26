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
    
    // HOME PAGE with VIDEO HERO & featured programmes
    public function home() {
        $programmes = $this->programmeModel->getAll();
        
        // Take first 3 programmes as featured
        $featured = array_slice($programmes, 0, 3);
        
        ob_start();
        ?>
        
        <!-- VIDEO HERO SECTION -->
        <div class="hero-video-section">
            <video autoplay muted loop playsinline>
                <source src="/DevTrio/videos/hero-video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="overlay">
                <h1>Welcome to Student Course Hub</h1>
                <p>Discover your perfect programme at our university. Explore undergraduate and postgraduate courses designed for your future.</p>
                <a href="index.php?url=programmes" class="btn-hero">Browse All Programmes →</a>
            </div>
        </div>
        
        <h2 class="section-title">Featured Programmes</h2>
        
        <div class="programme-grid">
            <?php foreach($featured as $prog): ?>
            <div class="programme-card">
                <?php if(!empty($prog['Image'])): ?>
                    <img src="/DevTrio/<?php echo $prog['Image']; ?>" 
                         alt="<?php echo htmlspecialchars($prog['ProgrammeName']); ?>"
                         class="programme-card-img">
                <?php else: ?>
                    <div class="programme-card-placeholder">
                        <?php echo htmlspecialchars($prog['ProgrammeName']); ?>
                    </div>
                <?php endif; ?>
                <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 120)) . '...'; ?></p>
                <p><strong>Level:</strong> <span class="level-badge"><?php echo $prog['LevelName']; ?></span></p>
                <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details →</a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center" style="text-align: center; margin: 50px 0;">
            <a href="index.php?url=programmes" class="btn btn-large">View All Programmes →</a>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    // SHOW ALL PROGRAMMES with FILTER and SEARCH - HIDES EMPTY SECTIONS
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
        
        <!-- Programme Page Header Image -->
        <?php if(file_exists(__DIR__ . '/../images/programmepageheader.jpg')): ?>
        <div class="programmes-header" style="margin-bottom: 30px;">
            <img src="/DevTrio/images/programmepageheader.jpg" 
                 alt="Our Programmes" 
                 style="width: 100%; height: 300px; object-fit: cover; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        </div>
        <?php endif; ?>
        
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
                <button type="submit">Search</button>
                <?php if(!empty($search)): ?>
                    <a href="index.php?url=programmes&filter=<?php echo $filter; ?>" class="clear-btn">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <!-- Results count -->
        <div class="results-count">
            Found <?php echo count($undergraduate) + count($postgraduate); ?> programme(s)
        </div>
        
        <!-- UNDERGRADUATE SECTION - ONLY SHOW IF NOT EMPTY -->
        <?php if(!empty($undergraduate)): ?>
        <div class="programme-section">
            <h2>Undergraduate Programmes</h2>
            <div class="programme-grid">
                <?php foreach($undergraduate as $prog): ?>
                <div class="programme-card">
                    <?php if(!empty($prog['Image'])): ?>
                        <img src="/DevTrio/<?php echo $prog['Image']; ?>" 
                             alt="<?php echo htmlspecialchars($prog['ProgrammeName']); ?>"
                             class="programme-card-img">
                    <?php else: ?>
                        <div class="programme-card-placeholder">
                            <?php echo htmlspecialchars($prog['ProgrammeName']); ?>
                        </div>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 100)) . '...'; ?></p>
                    <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- POSTGRADUATE SECTION - ONLY SHOW IF NOT EMPTY -->
        <?php if(!empty($postgraduate)): ?>
        <div class="programme-section">
            <h2>Postgraduate Programmes</h2>
            <div class="programme-grid">
                <?php foreach($postgraduate as $prog): ?>
                <div class="programme-card">
                    <?php if(!empty($prog['Image'])): ?>
                        <img src="/DevTrio/<?php echo $prog['Image']; ?>" 
                             alt="<?php echo htmlspecialchars($prog['ProgrammeName']); ?>"
                             class="programme-card-img">
                    <?php else: ?>
                        <div class="programme-card-placeholder">
                            <?php echo htmlspecialchars($prog['ProgrammeName']); ?>
                        </div>
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($prog['ProgrammeName']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($prog['Description'], 0, 100)) . '...'; ?></p>
                    <a href="index.php?url=programme/<?php echo $prog['ProgrammeID']; ?>" class="btn">View Details</a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Show message if no results at all -->
        <?php if(empty($undergraduate) && empty($postgraduate)): ?>
        <div class="no-results" style="text-align: center; padding: 60px;">
            <h3>No programmes found</h3>
            <p>Try adjusting your search or filter criteria.</p>
            <a href="index.php?url=programmes&filter=all" class="btn" style="margin-top: 20px;">Clear Filters</a>
        </div>
        <?php endif; ?>
        
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
            
            .filter-btn:hover, .filter-btn.active {
                background: #667eea;
                color: white;
                transform: translateY(-2px);
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
            
            .section-title {
                color: #2d3748;
                margin-bottom: 30px;
                font-size: 2rem;
                border-bottom: 3px solid #667eea;
                padding-bottom: 15px;
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
                
                .programmes-header img {
                    height: 150px !important;
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
                        <?php if(file_exists(__DIR__ . '/../images/modulecardplaceholder.jpg')): ?>
                            <img src="/DevTrio/images/modulecardplaceholder.jpg" 
                                 alt="Module" 
                                 style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                        <?php endif; ?>
                        <h4><?php echo htmlspecialchars($module['name']); ?></h4>
                        <p><?php echo htmlspecialchars($module['description']); ?></p>
                        <p class="module-leader">Module Leader: <?php echo htmlspecialchars($module['leader']); ?></p>
                    </div>
                    
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="interest-form" style="background-image: url('/DevTrio/images/interestform.jpg'); background-size: cover; background-position: center; border-radius: 15px;">
                <div style="background: rgba(255,255,255,0.95); padding: 30px; border-radius: 15px;">
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
                        Register your interest and we'll send you updates about this programme.
                    </p>
                </div>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <a href="index.php?url=programmes" class="btn">← Back to All Programmes</a>
            </div>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
}
?>