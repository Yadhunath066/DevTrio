<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/DBConnection.php';

class InterestController {
    private $db;
   
    public function __construct() {
        $this->db = DBConnection::getInstance()->getConnection();
    }
   
    public function store() {
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=home');
            exit;
        }
       
        // ========== STEP 1: GET AND SANITIZE INPUT (Prevents XSS) ==========
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $programme_id = isset($_POST['programme_id']) ? intval($_POST['programme_id']) : 0;
       
        // Sanitize for XSS prevention
        $name_sanitized = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $email_sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
       
        // ========== STEP 2: VALIDATE INPUT ==========
        $errors = [];
       
        // Validate Name
        if (empty($name)) {
            $errors[] = "Name is required";
        } elseif (strlen($name) < 2) {
            $errors[] = "Name must be at least 2 characters";
        } elseif (strlen($name) > 100) {
            $errors[] = "Name must be less than 100 characters";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
            $errors[] = "Name can only contain letters and spaces";
        }
       
        // Validate Email
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } elseif (strlen($email) > 255) {
            $errors[] = "Email must be less than 255 characters";
        }
       
        // Validate Programme ID
        if ($programme_id <= 0) {
            $errors[] = "Invalid programme selection";
        } else {
            // Check if programme exists and is published
            try {
                $checkSql = "SELECT ProgrammeID, ProgrammeName FROM Programmes WHERE ProgrammeID = ? AND published = 1";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute([$programme_id]);
                $programme = $checkStmt->fetch();
               
                if (!$programme) {
                    $errors[] = "Selected programme does not exist or is not available";
                }
            } catch (PDOException $e) {
                $errors[] = "Database error. Please try again later.";
                error_log("InterestController: " . $e->getMessage());
            }
        }
       
        // ========== STEP 3: CHECK FOR DUPLICATE INTEREST ==========
        if (empty($errors)) {
            try {
                $duplicateSql = "SELECT InterestID FROM InterestedStudents
                                 WHERE Email = ? AND ProgrammeID = ?";
                $duplicateStmt = $this->db->prepare($duplicateSql);
                $duplicateStmt->execute([$email, $programme_id]);
                if ($duplicateStmt->fetch()) {
                    $errors[] = "You have already registered interest for this programme";
                }
            } catch (PDOException $e) {
                $errors[] = "Database error. Please try again later.";
                error_log("InterestController: " . $e->getMessage());
            }
        }
       
        // ========== STEP 4: IF NO ERRORS, SAVE TO DATABASE ==========
        if (empty($errors)) {
            try {
                // Using prepared statements to prevent SQL injection
                $sql = "INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email, RegisteredAt)
                        VALUES (:programme_id, :name, :email, NOW())";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute([
                    ':programme_id' => $programme_id,
                    ':name' => $name_sanitized,
                    ':email' => $email_sanitized
                ]);
               
                if ($result) {
                    // Get programme name for success message
                    $progSql = "SELECT ProgrammeName FROM Programmes WHERE ProgrammeID = ?";
                    $progStmt = $this->db->prepare($progSql);
                    $progStmt->execute([$programme_id]);
                    $programmeName = $progStmt->fetchColumn();
                   
                    // Success - show thank you message
                    ob_start();
                    ?>
                    <div style="text-align: center; padding: 50px; max-width: 600px; margin: 0 auto;">
                        <div style="background: #d4edda; color: #155724; padding: 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                            <h2 style="margin-bottom: 15px;">✅ Thank You!</h2>
                            <p style="font-size: 1.1rem;">Thank you <strong><?php echo $name_sanitized; ?></strong> for your interest!</p>
                            <p>We'll send updates to: <strong><?php echo $email_sanitized; ?></strong></p>
                            <p style="margin-top: 15px;">Programme: <strong><?php echo htmlspecialchars($programmeName, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                        </div>
                        <a href="index.php?url=programmes" class="btn" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Browse More Programmes →</a>
                        <br><br>
                        <a href="index.php?url=home" class="btn" style="background: #718096; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Go Back Home</a>
                    </div>
                    <?php
                    $content = ob_get_clean();
                    require_once __DIR__ . '/../views/layout.php';
                    return;
                }
            } catch (PDOException $e) {
                // Database error - log it
                error_log("Interest registration error: " . $e->getMessage());
                $errors[] = "Something went wrong. Please try again later.";
            }
        }
       
        // ========== STEP 5: SHOW ERRORS IF ANY ==========
        if (!empty($errors)) {
            ob_start();
            ?>
            <div style="text-align: center; padding: 50px; max-width: 600px; margin: 0 auto;">
                <div style="background: #f8d7da; color: #721c24; padding: 30px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <h2 style="margin-bottom: 15px;">❌ Error</h2>
                    <ul style="text-align: left; margin: 15px 0 0 20px;">
                        <?php foreach($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="javascript:history.back()" class="btn" style="background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">← Go Back and Try Again</a>
                <br><br>
                <a href="index.php?url=home" class="btn" style="background: #718096; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Go Back Home</a>
            </div>
            <?php
            $content = ob_get_clean();
            require_once __DIR__ . '/../views/layout.php';
            return;
        }
    }
}
?>