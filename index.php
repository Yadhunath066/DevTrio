<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple routing
$url = isset($_GET['url']) ? $_GET['url'] : 'home';

echo "<!-- DEBUG: URL = " . $url . " -->";

if ($url == 'home') {
    echo "<!-- DEBUG: Loading home route -->";
    require_once 'controllers/ProgrammeController.php';
    $controller = new ProgrammeController();
    $controller->home();  // CHANGED: now calls home() method
} 
elseif ($url == 'programmes') {
    echo "<!-- DEBUG: Loading programmes route -->";
    require_once 'controllers/ProgrammeController.php';
    $controller = new ProgrammeController();
    $controller->programmes();  // CHANGED: now calls programmes() method
}
elseif (strpos($url, 'programme/') === 0) {
    echo "<!-- DEBUG: Loading programme detail route with ID: " . $url . " -->";
    $id = str_replace('programme/', '', $url);
    require_once 'controllers/ProgrammeController.php';
    $controller = new ProgrammeController();
    $controller->show($id);
}
else {
    echo "<!-- DEBUG: 404 route -->";
    ob_start();
    ?>
    <div style="text-align: center; padding: 50px;">
        <h1 style="font-size: 48px; color: #667eea;">404</h1>
        <h2 style="margin-bottom: 20px;">Page Not Found</h2>
        <p style="margin-bottom: 30px;">The page you're looking for doesn't exist.</p>
        <a href="index.php?url=home" class="btn">Go Back Home</a>
    </div>
    <?php
    $content = ob_get_clean();
    require 'views/layout.php';
}
?>