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
        
        .programme-card p {
            color: #4a5568;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        /* BUTTON STYLES */
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        /* PROGRAMME DETAIL PAGE */
        .programme-detail {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .programme-detail h1 {
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 2.2rem;
        }
        
        .programme-detail .level {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.1rem;
        }
        
        .description {
            margin-bottom: 40px;
        }
        
        .description h2 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.6rem;
        }
        
        .description p {
            color: #4a5568;
            line-height: 1.8;
        }
        
        /* MODULES SECTION */
        .modules h2 {
            color: #2d3748;
            margin-bottom: 25px;
            font-size: 1.6rem;
        }
        
        .year-group {
            margin-bottom: 30px;
        }
        
        .year-group h3 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1.3rem;
        }
        
        .module-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 0 8px 8px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .module-card h4 {
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .module-card p {
            color: #4a5568;
            margin-bottom: 10px;
        }
        
        .module-leader {
            color: #667eea;
            font-style: italic;
            margin-top: 10px;
            font-weight: 500;
        }
        
        /* INTEREST FORM */
        .interest-form {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
            border: 1px solid #e2e8f0;
        }
        
        .interest-form h2 {
            color: #2d3748;
            margin-bottom: 25px;
            font-size: 1.6rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
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
                <a href="/DevTrio/index.php?url=login">Admin Login</a>
            </nav>
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