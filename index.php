<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session for admin login
session_start();

// Simple routing
$url = isset($_GET['url']) ? $_GET['url'] : 'home';

echo "<!-- DEBUG: URL = " . $url . " -->";

if ($url == 'home') {
    echo "<!-- DEBUG: Loading home route -->";
    require_once 'controllers/ProgrammeController.php';
    $controller = new ProgrammeController();
    $controller->home();
}
elseif ($url == 'programmes') {
    echo "<!-- DEBUG: Loading programmes route -->";
    require_once 'controllers/ProgrammeController.php';
    $controller = new ProgrammeController();
    $controller->programmes();
}
elseif ($url == 'about') {
    echo "<!-- DEBUG: Loading about route -->";
    ob_start();
    require 'views/public/about.php';
    $content = ob_get_clean();
    require 'views/layout.php';
}
elseif (strpos($url, 'programme/') === 0) {
    echo "<!-- DEBUG: Loading programme detail route with ID: " . $url . " -->";
    $id = str_replace('programme/', '', $url);
    require_once 'controllers/ProgrammeController.php';
    $controller = new ProgrammeController();
    $controller->show($id);
}

// ========== SEPARATE LOGIN PAGES ==========
elseif ($url == 'admin/login') {
    echo "<!-- DEBUG: Loading admin login route -->";
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->adminLogin();
}
elseif ($url == 'staff/login') {
    echo "<!-- DEBUG: Loading staff login route -->";
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->staffLogin();
}
elseif ($url == 'admin/authenticate') {
    echo "<!-- DEBUG: Processing admin authenticate route -->";
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->adminAuthenticate();
}
elseif ($url == 'staff/authenticate') {
    echo "<!-- DEBUG: Processing staff authenticate route -->";
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->staffAuthenticate();
}
elseif ($url == 'logout') {
    echo "<!-- DEBUG: Processing logout route -->";
    require_once 'controllers/AuthController.php';
    $controller = new AuthController();
    $controller->logout();
}

// ========== ADMIN MANAGEMENT ROUTES ==========
elseif (strpos($url, 'admin/') === 0 && $url != 'admin/login' && $url != 'admin/authenticate') {
    echo "<!-- DEBUG: Loading admin route: " . $url . " -->";
    // Check if logged in as admin
    if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true || $_SESSION['admin_role'] != 'admin') {
        header('Location: index.php?url=admin/login');
        exit;
    }
   
    $admin_page = str_replace('admin/', '', $url);
   
    if($admin_page == 'dashboard') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->dashboard();
    }
    elseif($admin_page == 'programmes') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->programmes();
    }
    elseif($admin_page == 'programme_add') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->programme_add();
    }
    elseif($admin_page == 'programme_edit') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->programme_edit();
    }
    elseif($admin_page == 'programme_delete') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->programme_delete();
    }
    elseif($admin_page == 'modules') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->modules();
    }
    elseif($admin_page == 'module_add') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->module_add();
    }
    elseif($admin_page == 'module_edit') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->module_edit();
    }
    elseif($admin_page == 'module_delete') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->module_delete();
    }
    elseif($admin_page == 'interests') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->interests();
    }
    elseif($admin_page == 'interest_delete') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->interest_delete();
    }
    elseif($admin_page == 'export_csv') {
        require_once 'controllers/AdminController.php';
        $controller = new AdminController();
        $controller->export_csv();
    }
    else {
        echo "Admin page not found: " . $admin_page;
    }
}

// ========== STAFF ROUTES ==========
elseif (strpos($url, 'staff/') === 0 && $url != 'staff/login' && $url != 'staff/authenticate') {
    echo "<!-- DEBUG: Loading staff route: " . $url . " -->";
    // Check if logged in as staff
    if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true || $_SESSION['admin_role'] != 'staff') {
        header('Location: index.php?url=staff/login');
        exit;
    }
    
    if($url == 'staff/dashboard') {
        require_once 'controllers/StaffController.php';
        $controller = new StaffController();
        $controller->dashboard();
    }
    else {
        echo "Staff page not found";
    }
}

// ========== INTEREST REGISTRATION ROUTE ==========
elseif ($url == 'interest') {
    echo "<!-- DEBUG: Loading interest route -->";
    require_once 'controllers/InterestController.php';
    $controller = new InterestController();
    $controller->store();
}

// ========== 404 PAGE ==========
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