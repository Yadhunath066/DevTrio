<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../models/Programme.php';

class SimpleController {
    public function showProgrammes() {
        $model = new Programme();
        $programmes = $model->getAll();
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Programmes</title>
            <style>
                body { font-family: Arial; padding: 20px; background: #f5f5f5; }
                .card { background: white; padding: 20px; margin: 10px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
                h1 { color: #333; }
                .ug { border-left: 5px solid blue; }
                .pg { border-left: 5px solid green; }
            </style>
        </head>
        <body>
            <h1>Programmes List</h1>
            <?php foreach($programmes as $p): ?>
                <div class="card <?php echo strtolower($p['LevelName']) == 'undergraduate' ? 'ug' : 'pg'; ?>">
                    <h2><?php echo $p['ProgrammeName']; ?></h2>
                    <p><strong>Level:</strong> <?php echo $p['LevelName']; ?></p>
                    <p><?php echo $p['Description']; ?></p>
                    <a href="index.php?page=detail&id=<?php echo $p['ProgrammeID']; ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        </body>
        </html>
        <?php
    }
    
    public function showDetail($id) {
        $model = new Programme();
        $programme = $model->getByIdWithModules($id);
        
        if(!$programme) {
            echo "Programme not found";
            return;
        }
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title><?php echo $programme['ProgrammeName']; ?></title>
            <style>
                body { font-family: Arial; padding: 20px; background: #f5f5f5; }
                .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
                h1 { color: #333; }
                .module { background: #f9f9f9; padding: 15px; margin: 10px 0; border-radius: 5px; }
                .year { background: #667eea; color: white; padding: 5px 10px; border-radius: 3px; display: inline-block; }
            </style>
        </head>
        <body>
            <div class="container">
                <a href="index.php?page=home">← Back</a>
                <h1><?php echo $programme['ProgrammeName']; ?></h1>
                <p><strong>Level:</strong> <?php echo $programme['LevelName']; ?></p>
                <p><?php echo $programme['Description']; ?></p>
                
                <h2>Modules</h2>
                <?php foreach($programme['modules'] as $module): ?>
                    <div class="module">
                        <span class="year">Year <?php echo $module['year']; ?></span>
                        <h3><?php echo $module['name']; ?></h3>
                        <p><?php echo $module['description']; ?></p>
                        <p><em>Leader: <?php echo $module['leader']; ?></em></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </body>
        </html>
        <?php
    }
}
?>