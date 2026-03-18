<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Course Hub</title>
    <style>
        /* RESET & BASE STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* HEADER STYLES */
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 600;
        }
        
        nav {
            margin-top: 15px;
        }
        
        nav a {
            color: white;
            margin-right: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        nav a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }
        
        /* MAIN CONTENT */
        main {
            min-height: 500px;
            padding: 40px 0;
        }
        
        /* FOOTER STYLES */
        footer {
            background: #2d3748;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
            text-align: center;
        }
        
        /* PROGRAMME GRID */
        .programme-section {
            margin-bottom: 50px;
        }
        
        .programme-section h2 {
            color: #2d3748;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            font-size: 1.8rem;
        }
        
        .programme-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin: 20px 0 40px;
        }
        
        .programme-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #e2e8f0;
        }
        
        .programme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }
        
        .programme-card h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 1.4rem;
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
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .interest-form .btn {
            width: 100%;
            padding: 14px;
            font-size: 1.1rem;
        }
        
        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.5rem;
            }
            
            nav a {
                margin-right: 15px;
                font-size: 0.9rem;
            }
            
            .programme-grid {
                grid-template-columns: 1fr;
            }
            
            .programme-detail {
                padding: 20px;
            }
            
            .programme-detail h1 {
                font-size: 1.8rem;
            }
            
            .year-group h3 {
                font-size: 1.1rem;
            }
            
            .module-card {
                padding: 15px;
            }
        }
        
        /* SMALLER MOBILE DEVICES */
        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }
            
            .programme-card {
                padding: 20px;
            }
            
            .interest-form {
                padding: 20px;
            }
        }
        
        /* ACCESSIBILITY - FOCUS INDICATORS */
        a:focus, button:focus, input:focus {
            outline: 3px solid #667eea;
            outline-offset: 2px;
        }
        
        /* KEYBOARD NAVIGATION */
        a:focus-visible, button:focus-visible {
            outline: 3px solid #667eea;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
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
    
    <main class="container">
        <?php echo $content; ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
            <p>Developed by DevTrio Team</p>
        </div>
    </footer>
</body>
</html>