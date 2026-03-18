<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Simple Model Test</h1>";

// Try to include the model
$modelPath = 'models/Programme.php';
echo "Trying to include: " . $modelPath . "<br>";

if (file_exists($modelPath)) {
    echo "✅ File exists!<br>";
    require_once $modelPath;
    echo "✅ File included successfully!<br>";
    
    // Try to create object
    echo "Creating Programme object...<br>";
    $programme = new Programme();
    echo "✅ Object created!<br>";
    
    // Try to get data
    echo "Calling getAll()...<br>";
    $data = $programme->getAll();
    echo "✅ Got data!<br>";
    
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    
} else {
    echo "❌ File does not exist at: " . realpath($modelPath) . "<br>";
    
    // Show current directory
    echo "Current directory: " . __DIR__ . "<br>";
    
    // List files in models folder
    echo "Files in models folder:<br>";
    $files = scandir('models');
    foreach($files as $file) {
        echo " - " . $file . "<br>";
    }
}
?>