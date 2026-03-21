<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session is already started in index.php - don't start again!

require_once __DIR__ . '/../config/DBConnection.php';

class AuthController {
    private $db;
    
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
    
    // Show login form
    public function login() {
        // If already logged in, redirect to dashboard
        if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            header('Location: index.php?url=admin/dashboard');
            exit;
        }
        
        ob_start();
        ?>
        
        <div style="max-width: 400px; margin: 50px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="text-align: center; margin-bottom: 30px; color: #2d3748;">Admin Login</h1>
            
            <?php if(isset($_SESSION['login_error'])): ?>
                <div style="background: #fed7d7; color: #c53030; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                    <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?url=authenticate">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Username</label>
                    <input type="text" name="username" required 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                    <input type="password" name="password" required 
                           style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; font-weight: 600;">
                    Login
                </button>
            </form>
            
            <p style="text-align: center; margin-top: 20px; color: #718096; font-size: 14px;">
                Demo: username = admin, password = admin123
            </p>
        </div>
        
        <?php
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layout.php';
    }
    
    // Process login
    public function authenticate() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=login');
            exit;
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if(empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter username and password';
            header('Location: index.php?url=login');
            exit;
        }
        
        // Check user in database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_role'] = $user['role'];
            
            header('Location: index.php?url=admin/dashboard');
            exit;
        } else {
            $_SESSION['login_error'] = 'Invalid username or password';
            header('Location: index.php?url=login');
            exit;
        }
    }
    
    // Logout
    public function logout() {
        session_destroy();
        header('Location: index.php?url=home');
        exit;
    }
}
?>