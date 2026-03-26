<?php
// Debug - check if images exist (remove after fixing)
$debug = true;
if($debug) {
    // Use absolute path for checking
    $basePath = $_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/';
    echo "<!-- Debug Info -->";
    echo "<!-- dr_alice.jpg exists: " . (file_exists($basePath . 'dr_alice.jpg') ? 'YES' : 'NO') . " -->";
    echo "<!-- dr_brian.jpg exists: " . (file_exists($basePath . 'dr_brian.jpg') ? 'YES' : 'NO') . " -->";
    echo "<!-- dr_carol.jpg exists: " . (file_exists($basePath . 'dr_carol.jpg') ? 'YES' : 'NO') . " -->";
    echo "<!-- aboutuniversity.jpg exists: " . (file_exists($basePath . 'aboutuniversity.jpg') ? 'YES' : 'NO') . " -->";
    echo "<!-- campuslife.jpg exists: " . (file_exists($basePath . 'campuslife.jpg') ? 'YES' : 'NO') . " -->";
}
?>

<div class="about-page">
    <!-- Main About University Image -->
    <div class="about-header" style="margin-bottom: 40px;">
        <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/aboutuniversity.jpg')): ?>
            <img src="/DevTrio/images/aboutuniversity.jpg" 
                 alt="About Our University" 
                 style="width: 100%; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <?php else: ?>
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 400px; display: flex; align-items: center; justify-content: center; border-radius: 20px;">
                <h1 style="color: white;">About Our University</h1>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- About Content with Campus Life Image -->
    <div class="about-content" style="display: flex; gap: 40px; flex-wrap: wrap; margin-bottom: 50px;">
        <div class="about-text" style="flex: 1.5;">
            <h1 style="color: #2d3748; margin-bottom: 20px; font-size: 2.5rem;">About DevTrio University</h1>
            <p style="margin-bottom: 20px; line-height: 1.8; font-size: 1.1rem; color: #4a5568;">
                Welcome to DevTrio University, a world-class institution dedicated to excellence in education, research, and innovation. 
                Our university has been nurturing bright minds for over 50 years, preparing students for successful careers in the digital age.
            </p>
            <p style="margin-bottom: 20px; line-height: 1.8; color: #4a5568;">
                We offer a wide range of undergraduate and postgraduate programmes designed to meet the demands of today's rapidly evolving 
                technology landscape. Our curriculum is regularly updated to ensure students gain relevant, industry-ready skills.
            </p>
            
            <h3 style="color: #2d3748; margin: 30px 0 15px; font-size: 1.5rem;">Our Mission</h3>
            <p style="margin-bottom: 20px; line-height: 1.8; color: #4a5568;">
                To provide high-quality education that empowers students to become leaders in their fields, contributing to society through 
                knowledge, innovation, and ethical practice.
            </p>
            
            <h3 style="color: #2d3748; margin: 30px 0 15px; font-size: 1.5rem;">Our Vision</h3>
            <p style="margin-bottom: 20px; line-height: 1.8; color: #4a5568;">
                To be a globally recognized center of excellence in computing education, producing graduates who shape the future of technology.
            </p>
        </div>
        
        <div class="about-image" style="flex: 1;">
            <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/campuslife.jpg')): ?>
                <img src="/DevTrio/images/campuslife.jpg" 
                     alt="Campus Life" 
                     style="width: 100%; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <?php else: ?>
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 250px; display: flex; align-items: center; justify-content: center; border-radius: 15px; margin-bottom: 20px;">
                    <p style="color: white;">Campus Life</p>
                </div>
            <?php endif; ?>
            
            <div style="background: #f7fafc; padding: 20px; border-radius: 15px; margin-top: 20px;">
                <h3 style="color: #2d3748; margin-bottom: 10px;">Quick Facts</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;">✓ 10+ Programmes Offered</li>
                    <li style="margin-bottom: 10px;">✓ 20+ Expert Faculty Members</li>
                    <li style="margin-bottom: 10px;">✓ 50+ Years of Excellence</li>
                    <li style="margin-bottom: 10px;">✓ 5000+ Alumni Worldwide</li>
                    <li style="margin-bottom: 10px;">✓ Industry Partnerships</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Faculty Section with Individual Photos -->
    <div class="faculty-section" style="margin-bottom: 50px; background: #f7fafc; padding: 40px; border-radius: 20px;">
        <h2 style="color: #2d3748; margin-bottom: 30px; text-align: center; font-size: 2rem;">Our Distinguished Faculty</h2>
        
        <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/facultyimage.jpg')): ?>
            <img src="/DevTrio/images/facultyimage.jpg" 
                 alt="Our Faculty" 
                 style="width: 100%; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 40px;">
            <!-- Dr. Alice Johnson -->
            <div style="text-align: center; padding: 25px; background: white; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.3s;">
                <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/dr_alice.jpg')): ?>
                    <img src="/DevTrio/images/dr_alice.jpg" 
                         alt="Dr. Alice Johnson" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 4px solid #667eea;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <span style="color: white; font-size: 3rem;">👩‍🏫</span>
                    </div>
                <?php endif; ?>
                <h3 style="color: #2d3748; margin-bottom: 5px;">Dr. Alice Johnson</h3>
                <p style="color: #667eea; font-weight: 500; margin-bottom: 10px;">Professor of Computer Science</p>
                <p style="color: #4a5568; font-size: 0.9rem;">PhD in Artificial Intelligence, 15+ years of teaching experience. Expert in machine learning and data science.</p>
            </div>
            
            <!-- Dr. Brian Lee -->
            <div style="text-align: center; padding: 25px; background: white; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.3s;">
                <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/dr_brian.jpg')): ?>
                    <img src="/DevTrio/images/dr_brian.jpg" 
                         alt="Dr. Brian Lee" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 4px solid #667eea;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <span style="color: white; font-size: 3rem;">👨‍🏫</span>
                    </div>
                <?php endif; ?>
                <h3 style="color: #2d3748; margin-bottom: 5px;">Dr. Brian Lee</h3>
                <p style="color: #667eea; font-weight: 500; margin-bottom: 10px;">Head of Software Engineering</p>
                <p style="color: #4a5568; font-size: 0.9rem;">Specializes in software architecture, agile methodologies, and full-stack development.</p>
            </div>
            
            <!-- Dr. Carol White -->
            <div style="text-align: center; padding: 25px; background: white; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.3s;">
                <?php if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/DevTrio/images/dr_carol.jpg')): ?>
                    <img src="/DevTrio/images/dr_carol.jpg" 
                         alt="Dr. Carol White" 
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 4px solid #667eea;">
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); width: 150px; height: 150px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <span style="color: white; font-size: 3rem;">👩‍💻</span>
                    </div>
                <?php endif; ?>
                <h3 style="color: #2d3748; margin-bottom: 5px;">Dr. Carol White</h3>
                <p style="color: #667eea; font-weight: 500; margin-bottom: 10px;">AI & Machine Learning Specialist</p>
                <p style="color: #4a5568; font-size: 0.9rem;">Expert in neural networks, deep learning, and computer vision. Published researcher.</p>
            </div>
        </div>
        
        <p style="text-align: center; color: #4a5568; font-size: 1rem; max-width: 800px; margin: 40px auto 0;">
            Our dedicated faculty members are experts in their fields, bringing years of industry experience and academic excellence to the classroom. 
            They are committed to student success and innovation in teaching.
        </p>
    </div>
    
    <!-- Call to Action -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 50px; border-radius: 20px; text-align: center; color: white;">
        <h2 style="font-size: 2rem; margin-bottom: 20px;">Ready to Start Your Journey?</h2>
        <p style="font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9;">Explore our programmes and find the perfect fit for your future career.</p>
        <a href="index.php?url=programmes" class="btn" style="background: white; color: #667eea; padding: 15px 40px; font-size: 1.1rem;">Browse Programmes →</a>
    </div>
</div>

<style>
    .about-page {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .faculty-section div[style*="grid-template-columns"] > div:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .about-content {
            flex-direction: column;
        }
        
        .about-header img {
            height: 200px !important;
        }
        
        .faculty-section {
            padding: 20px !important;
        }
        
        .faculty-section div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
        
        .about-text h1 {
            font-size: 1.8rem !important;
        }
    }
</style>