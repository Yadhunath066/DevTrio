<?php
// Turn on ALL error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- DEBUG: Programme.php loaded successfully -->";

class Programme {
    
    public function __construct() {
        echo "<!-- DEBUG: Programme constructor called -->";
    }
    
    // Get all programmes
    public function getAll() {
        echo "<!-- DEBUG: getAll() method called -->";
        
        $data = [
            [
                'ProgrammeID' => 1,
                'ProgrammeName' => 'BSc Computer Science',
                'LevelName' => 'Undergraduate',
                'Description' => 'A broad computer science degree covering programming, AI, cybersecurity, and software engineering.'
            ],
            [
                'ProgrammeID' => 2,
                'ProgrammeName' => 'BSc Software Engineering',
                'LevelName' => 'Undergraduate',
                'Description' => 'A specialized degree focusing on the development and lifecycle of software applications.'
            ],
            [
                'ProgrammeID' => 3,
                'ProgrammeName' => 'BSc Artificial Intelligence',
                'LevelName' => 'Undergraduate',
                'Description' => 'Focuses on machine learning, deep learning, and AI applications.'
            ],
            [
                'ProgrammeID' => 4,
                'ProgrammeName' => 'MSc Machine Learning',
                'LevelName' => 'Postgraduate',
                'Description' => 'A postgraduate degree focusing on deep learning, AI ethics, and neural networks.'
            ],
            [
                'ProgrammeID' => 5,
                'ProgrammeName' => 'MSc Cyber Security',
                'LevelName' => 'Postgraduate',
                'Description' => 'Covers digital forensics, cyber threat intelligence, and security policy.'
            ]
        ];
        
        echo "<!-- DEBUG: Returning " . count($data) . " programmes -->";
        return $data;
    }
    
    // Get programme by ID with modules
    public function getByIdWithModules($id) {
        echo "<!-- DEBUG: getByIdWithModules() called with ID: $id -->";
        
        $programmes = [
            1 => [
                'ProgrammeID' => 1,
                'ProgrammeName' => 'BSc Computer Science',
                'LevelName' => 'Undergraduate',
                'Description' => 'A broad computer science degree covering programming, AI, cybersecurity, and software engineering.',
                'modules' => [
                    ['ModuleID' => 1, 'ModuleName' => 'Introduction to Programming', 'ModuleDescription' => 'Learn Python and Java', 'ModuleLeaderName' => 'Dr. Alice Johnson', 'Year' => 1],
                    ['ModuleID' => 2, 'ModuleName' => 'Mathematics for CS', 'ModuleDescription' => 'Discrete mathematics', 'ModuleLeaderName' => 'Dr. Brian Lee', 'Year' => 1],
                    ['ModuleID' => 3, 'ModuleName' => 'Databases', 'ModuleDescription' => 'SQL and database design', 'ModuleLeaderName' => 'Dr. Carol White', 'Year' => 2],
                    ['ModuleID' => 4, 'ModuleName' => 'Final Year Project', 'ModuleDescription' => 'Major individual project', 'ModuleLeaderName' => 'Dr. David Green', 'Year' => 3]
                ]
            ],
            2 => [
                'ProgrammeID' => 2,
                'ProgrammeName' => 'BSc Software Engineering',
                'LevelName' => 'Undergraduate',
                'Description' => 'A specialized degree focusing on the development and lifecycle of software applications.',
                'modules' => [
                    ['ModuleID' => 1, 'ModuleName' => 'Introduction to Programming', 'ModuleDescription' => 'Learn Python and Java', 'ModuleLeaderName' => 'Dr. Alice Johnson', 'Year' => 1],
                    ['ModuleID' => 5, 'ModuleName' => 'Software Engineering', 'ModuleDescription' => 'Agile development and design patterns', 'ModuleLeaderName' => 'Dr. Emma Scott', 'Year' => 2],
                    ['ModuleID' => 6, 'ModuleName' => 'Software Testing', 'ModuleDescription' => 'Automated testing and QA', 'ModuleLeaderName' => 'Dr. Frank Moore', 'Year' => 2],
                    ['ModuleID' => 4, 'ModuleName' => 'Final Year Project', 'ModuleDescription' => 'Major individual project', 'ModuleLeaderName' => 'Dr. David Green', 'Year' => 3]
                ]
            ],
            3 => [
                'ProgrammeID' => 3,
                'ProgrammeName' => 'BSc Artificial Intelligence',
                'LevelName' => 'Undergraduate',
                'Description' => 'Focuses on machine learning, deep learning, and AI applications.',
                'modules' => [
                    ['ModuleID' => 1, 'ModuleName' => 'Introduction to Programming', 'ModuleDescription' => 'Learn Python and Java', 'ModuleLeaderName' => 'Dr. Alice Johnson', 'Year' => 1],
                    ['ModuleID' => 8, 'ModuleName' => 'Artificial Intelligence', 'ModuleDescription' => 'Neural networks and expert systems', 'ModuleLeaderName' => 'Dr. Grace Adams', 'Year' => 2],
                    ['ModuleID' => 9, 'ModuleName' => 'Machine Learning', 'ModuleDescription' => 'Supervised and unsupervised learning', 'ModuleLeaderName' => 'Dr. Irene Hall', 'Year' => 2],
                    ['ModuleID' => 4, 'ModuleName' => 'Final Year Project', 'ModuleDescription' => 'Major individual project', 'ModuleLeaderName' => 'Dr. David Green', 'Year' => 3]
                ]
            ],
            4 => [
                'ProgrammeID' => 4,
                'ProgrammeName' => 'MSc Machine Learning',
                'LevelName' => 'Postgraduate',
                'Description' => 'A postgraduate degree focusing on deep learning, AI ethics, and neural networks.',
                'modules' => [
                    ['ModuleID' => 19, 'ModuleName' => 'Advanced Machine Learning', 'ModuleDescription' => 'Deep learning and reinforcement learning', 'ModuleLeaderName' => 'Dr. Sophia Miller', 'Year' => 1],
                    ['ModuleID' => 24, 'ModuleName' => 'AI Ethics & Society', 'ModuleDescription' => 'Ethical dilemmas in AI', 'ModuleLeaderName' => 'Dr. Chloe Thompson', 'Year' => 1],
                    ['ModuleID' => 27, 'ModuleName' => 'Neural Networks', 'ModuleDescription' => 'Convolutional networks and GANs', 'ModuleLeaderName' => 'Dr. Olivia Martin', 'Year' => 1],
                    ['ModuleID' => 31, 'ModuleName' => 'Postgraduate Dissertation', 'ModuleDescription' => 'Major research project', 'ModuleLeaderName' => 'Dr. Samuel Anderson', 'Year' => 1]
                ]
            ],
            5 => [
                'ProgrammeID' => 5,
                'ProgrammeName' => 'MSc Cyber Security',
                'LevelName' => 'Postgraduate',
                'Description' => 'Covers digital forensics, cyber threat intelligence, and security policy.',
                'modules' => [
                    ['ModuleID' => 20, 'ModuleName' => 'Cyber Threat Intelligence', 'ModuleDescription' => 'Focuses on cybersecurity risk analysis, malware detection, and threat mitigation.', 'ModuleLeaderName' => 'Dr. Benjamin Carter', 'Year' => 1],
                    ['ModuleID' => 26, 'ModuleName' => 'Cybersecurity Law & Policy', 'ModuleDescription' => 'Explores digital privacy, GDPR, and international cyber law.', 'ModuleLeaderName' => 'Dr. Daniel Robinson', 'Year' => 1],
                    ['ModuleID' => 30, 'ModuleName' => 'Digital Forensics & Incident Response', 'ModuleDescription' => 'Teaches forensic analysis, evidence gathering, and threat mitigation.', 'ModuleLeaderName' => 'Dr. Nathan Hughes', 'Year' => 1],
                    ['ModuleID' => 23, 'ModuleName' => 'Blockchain & Cryptography', 'ModuleDescription' => 'Covers decentralized applications, consensus algorithms, and security measures.', 'ModuleLeaderName' => 'Dr. Victoria Hall', 'Year' => 1],
                    ['ModuleID' => 31, 'ModuleName' => 'Postgraduate Dissertation', 'ModuleDescription' => 'Major research project', 'ModuleLeaderName' => 'Dr. Samuel Anderson', 'Year' => 1]
                ]
            ]
        ];
        
        if(isset($programmes[$id])) {
            echo "<!-- DEBUG: Found programme with ID: $id -->";
            return $programmes[$id];
        } else {
            echo "<!-- DEBUG: Programme with ID: $id NOT found -->";
            return null;
        }
    }
    
    // ========== NEW SEARCH METHOD ==========
    public function search($keyword) {
        echo "<!-- DEBUG: search() method called with keyword: $keyword -->";
        
        $allProgrammes = $this->getAll();
        $results = [];
        
        foreach($allProgrammes as $prog) {
            // Search in programme name and description (case insensitive)
            if(stripos($prog['ProgrammeName'], $keyword) !== false || 
               stripos($prog['Description'], $keyword) !== false) {
                $results[] = $prog;
            }
        }
        
        echo "<!-- DEBUG: Found " . count($results) . " matching programmes -->";
        return $results;
    }
    
    // ========== NEW FILTER METHOD ==========
    public function getByLevel($level) {
        echo "<!-- DEBUG: getByLevel() method called with level: $level -->";
        
        $allProgrammes = $this->getAll();
        $results = [];
        
        foreach($allProgrammes as $prog) {
            if($prog['LevelName'] == $level) {
                $results[] = $prog;
            }
        }
        
        echo "<!-- DEBUG: Found " . count($results) . " programmes at level: $level -->";
        return $results;
    }
}

echo "<!-- DEBUG: Programme.php execution complete -->";
?>