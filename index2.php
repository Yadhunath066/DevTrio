<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

require_once 'controllers/SimpleController.php';
$controller = new SimpleController();

if($page == 'home') {
    $controller->showProgrammes();
} elseif($page == 'detail' && isset($_GET['id'])) {
    $controller->showDetail($_GET['id']);
} else {
    echo "Page not found";
}
?>