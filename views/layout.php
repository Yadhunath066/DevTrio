<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Course Hub</title>
    <link rel="stylesheet" href="/DevTrio/css/style.css">
    <style>
        /* Additional styles for filter bar and search */
        .filter-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
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
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .search-form input {
            padding: 10px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 30px;
            width: 300px;
            font-size: 1rem;
            transition: border-color 0.3s;
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
            transition: all 0.3s;
        }
        
        .search-form button:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        
        /* Error message styling */
        .error-message {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 5px;
            display: block;
        }
        
        input.error {
            border-color: #e53e3e;
        }
        
        .success-message {
            background: #c6f6d5;
            color: #22543d;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        /* Skip to content link */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #667eea;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            z-index: 100;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        /* Button hover effects */
        .btn {
            transition: all 0.3s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        /* Card hover effect */
        .programme-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .programme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        /* Module card hover */
        .module-card {
            transition: transform 0.3s;
        }
        
        .module-card:hover {
            transform: translateX(5px);
        }
        
        /* Mobile responsive enhancements */
        @media (max-width: 768px) {
            .filter-bar {
                justify-content: center;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-form input {
                width: 100%;
            }
            
            .search-form button {
                width: 100%;
            }
        }
        
        /* Dropdown styling for login */
        .login-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .login-btn {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 30px;
            margin-left: 10px;
        }
        
        .login-dropdown-content {
            display: none;
            position: absolute;
            background: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .login-dropdown-content a {
            color: #2d3748;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }
        
        .login-dropdown-content a:hover {
            background: #f7fafc;
            color: #667eea;
        }
        
        .login-dropdown:hover .login-dropdown-content {
            display: block;
        }
        
        /* Separate login buttons for mobile */
        .login-links {
            display: inline-block;
        }
        
        .login-link {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 30px;
            margin-left: 10px;
        }
        
        @media (max-width: 768px) {
            .login-links {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-top: 10px;
            }
            
            .login-link {
                display: inline-block;
                text-align: center;
                margin-left: 0;
            }
            
            .login-dropdown {
                display: none;
            }
        }
        
        @media (min-width: 769px) {
            .login-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <header>
        <div class="container">
            <h1>Student Course Hub</h1>
            <nav>
                <a href="/DevTrio/index.php?url=home">Home</a>
                <a href="/DevTrio/index.php?url=programmes">Programmes</a>
                
                <!-- Dropdown for desktop -->
                <div class="login-dropdown">
                    <a href="#" class="login-btn">Login ▼</a>
                    <div class="login-dropdown-content">
                        <a href="/DevTrio/index.php?url=admin/login">🔐 Admin Login</a>
                        <a href="/DevTrio/index.php?url=staff/login">👨‍🏫 Staff Login</a>
                    </div>
                </div>
                
                <!-- Separate buttons for mobile -->
                <div class="login-links">
                    <a href="/DevTrio/index.php?url=admin/login" class="login-link">🔐 Admin Login</a>
                    <a href="/DevTrio/index.php?url=staff/login" class="login-link">👨‍🏫 Staff Login</a>
                </div>
            </nav>
        </div>
    </header>
    
    <main id="main-content" class="container">
        <?php echo $content; ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
            <p>Developed by DevTrio Team</p>
        </div>
    </footer>
    
    <!-- JavaScript Validation -->
    <script src="/DevTrio/js/validation.js"></script>
</body>
</html>