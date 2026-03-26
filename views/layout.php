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
        
        /* Header with logo styles - UPDATED */
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .university-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 50%;
            background: white;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
        }
        
        .logo-text h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(135deg, #fff 0%, #f0e6ff 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .tagline {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 5px;
            letter-spacing: 1px;
            font-weight: 400;
            color: rgba(255,255,255,0.9);
            font-style: italic;
        }
        
        /* Hero Banner Styles */
        .hero-banner {
            margin-bottom: 30px;
            width: 100%;
            overflow: hidden;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .banner-image {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }
        
        .hero-banner:hover .banner-image {
            transform: scale(1.02);
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
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .logo-area {
                justify-content: center;
            }
            
            .university-logo {
                width: 55px;
                height: 55px;
            }
            
            .logo-text h1 {
                font-size: 1.5rem;
            }
            
            .tagline {
                font-size: 0.7rem;
            }
            
            .banner-image {
                max-height: 200px;
            }
            
            .hero-banner {
                border-radius: 10px;
                margin-bottom: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .university-logo {
                width: 45px;
                height: 45px;
            }
            
            .logo-text h1 {
                font-size: 1.2rem;
            }
            
            .tagline {
                font-size: 0.65rem;
            }
            
            .banner-image {
                max-height: 150px;
            }
            
            .hero-banner {
                border-radius: 8px;
                margin-bottom: 15px;
            }
        }
        
        @media (min-width: 1200px) {
            .banner-image {
                max-height: 450px;
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
            <div class="header-content">
                <div class="logo-area">
                    <img src="/DevTrio/images/universitylogo.jpg" 
                         alt="University Logo" 
                         class="university-logo">
                    <div class="logo-text">
                        <h1>DevTrio University</h1>
                        <div class="tagline">Your Future Starts Here</div>
                    </div>
                </div>
                <nav>
                    <a href="/DevTrio/index.php?url=home">Home</a>
                    <a href="/DevTrio/index.php?url=programmes">Programmes</a>
                    <a href="/DevTrio/index.php?url=about">About Us</a>
                    
                    <!-- Dropdown for desktop -->
                    <div class="login-dropdown">
                        <a href="#" class="login-btn">Login ▼</a>
                        <div class="login-dropdown-content">
                            <a href="/DevTrio/index.php?url=admin/login">Admin Login</a>
                            <a href="/DevTrio/index.php?url=staff/login">Staff Login</a>
                        </div>
                    </div>
                    
                    <!-- Separate buttons for mobile -->
                    <div class="login-links">
                        <a href="/DevTrio/index.php?url=admin/login" class="login-link">Admin Login</a>
                        <a href="/DevTrio/index.php?url=staff/login" class="login-link">Staff Login</a>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    
    <main id="main-content" class="container">
        <?php echo $content; ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2026 DevTrio University. All rights reserved.</p>
            <p>Developed by Team DevTrio WEBTECH 46</p>
        </div>
    </footer>
    
    <!-- JavaScript Validation -->
    <script src="/DevTrio/js/validation.js"></script>
</body>
</html>